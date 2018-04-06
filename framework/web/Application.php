<?php

namespace framework\web;

/**
 * App类
 * @author Liu Zhang <lz-850610@163.com>
 */
class Application extends \framework\base\Application
{
    //NotFound错误信息
    protected $_notFoundMessage = 'Not Found (#404)';

    // 执行功能 (mix-httpd)
    public function run()
    {
        \framework\web\Error::register();
        $server = \framework\base\Bricks::app()->request->server();
        $method = strtoupper($server['request_method']);
        $action = empty($server['path_info']) ? '' : substr($server['path_info'], 1);
        \framework\base\Bricks::app()->response->content = $this->runAction($method, $action);
        \framework\base\Bricks::app()->response->send();
        $this->cleanComponents();
    }

}
