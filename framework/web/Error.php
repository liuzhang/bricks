<?php

namespace framework\web;

use framework\base\Component;
use framework\web\View;

/**
 * Error类
 * @author Liu Zhang <lz-850610@163.com>
 */
class Error extends Component
{

    // 格式值
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    // 输出格式
    public $format = self::FORMAT_HTML;

    // 注册异常处理
    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler(['framework\web\Error', 'appError']);
        set_exception_handler(['framework\web\Error', 'appException']);
        register_shutdown_function(['framework\web\Error', 'appShutdown']);
    }

    // Error Handler
    public static function appError($errno, $errstr, $errfile = '', $errline = 0)
    {
        throw new \framework\exception\ErrorException($errno, $errstr, $errfile, $errline);
    }

    // Error Handler
    public static function appShutdown()
    {
        if ($error = error_get_last()) {
            self::appException(new \framework\exception\ErrorException($error['type'], $error['message'], $error['file'], $error['line']));
        }
    }

    // Exception Handler
    public static function appException($e)
    {
        // debug处理 & exit处理
        if ($e instanceof \framework\exception\DebugException || $e instanceof \framework\exception\EndException) {
            \framework\base\Bricks::app()->response->content = $e->getMessage();
            \framework\base\Bricks::app()->response->send();
            \framework\base\Bricks::app()->cleanComponents();
            return;
        }
        // 错误参数定义
        $statusCode = $e instanceof \framework\exception\NotFoundException ? 404 : 500;
        $errors     = [
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'type'    => get_class($e),
            'trace'   => $e->getTraceAsString(),
        ];
        // 日志处理
        if (isset(\framework\base\Bricks::app()->components['log']) && !($e instanceof \framework\exception\NotFoundException)) {
            $time    = date('Y-m-d H:i:s');
            $message = "[time] {$time}" . PHP_EOL;
            $message .= "[code] {$errors['code']}" . PHP_EOL;
            $message .= "[message] {$errors['message']}" . PHP_EOL;
            $message .= "[type] {$errors['type']}" . PHP_EOL;
            $message .= "[file] {$errors['file']} line {$errors['line']}" . PHP_EOL;
            $message .= "[trace] {$errors['trace']}" . PHP_EOL;
            $message .= str_replace('Array', '$_SERVER', print_r($_SERVER, true));
            $message .= str_replace('Array', '$_GET', print_r($_GET, true));
            $message .= str_replace('Array', '$_POST', print_r($_POST, true));
            \framework\base\Bricks::app()->log->error($message);
        }
        // 清空系统错误
        ob_get_contents() and ob_clean();
        // 错误响应
        if (!DEBUG) {
            if ($statusCode == 404) {
                $errors = [
                    'code'    => 404,
                    'message' => $e->getMessage(),
                ];
            }
            if ($statusCode == 500) {
                $errors = [
                    'code'    => 500,
                    'message' => '服务器内部错误',
                ];
            }
        }
        $format                           = \framework\base\Bricks::app()->error->format;
        $tpl                              = [
            404 => "errors.{$format}.not_found",
            500 => "errors.{$format}.internal_server_error",
        ];
        $content                          = (new View())->render($tpl[$statusCode], $errors);
        \framework\base\Bricks::app()->response->statusCode = $statusCode;
        \framework\base\Bricks::app()->response->content = $content;
        switch ($format) {
            case self::FORMAT_HTML:
                \framework\base\Bricks::app()->response->setHeader('Content-Type', 'text/html;charset=utf-8');
                break;
            case self::FORMAT_JSON:
                \framework\base\Bricks::app()->response->setHeader('Content-Type', 'application/json;charset=utf-8');
                break;
            case self::FORMAT_XML:
                \framework\base\Bricks::app()->response->setHeader('Content-Type', 'text/xml;charset=utf-8');
                break;
        }
        \framework\base\Bricks::app()->response->send();
        \framework\base\Bricks::app()->cleanComponents();
    }

}
