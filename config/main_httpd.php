<?php

// mix-httpd 下运行的 Web 应用配置
return [

    // 基础路径
    'basePath'            => dirname(__DIR__) . DIRECTORY_SEPARATOR,

    // 控制器命名空间
    'controllerNamespace' => 'app\controllers',
    // 组件配置
    'components'          => [

        // 路由
        'route'    => [
            // 类路径
            'class'          => 'framework\base\Route',
            // 默认变量规则
            'defaultPattern' => '[\w-]+',
            // 路由变量规则
            'patterns'       => [
                'id' => '\d+',
            ],
            // 路由规则
            'rules'          => [
            ],
            // URL后缀
            'suffix'         => '.html',
        ],

        // 请求
        'request'  => [
            // 类路径
            'class' => '\framework\web\Request',
        ],

        // 响应
        'response' => [
            // 类路径
            'class'         => '\framework\web\Response',
            // 默认输出格式
            'defaultFormat' => \framework\web\Response::FORMAT_JSON,
            // json
            'json'          => [
                // 类路径
                'class' => '\framework\web\Json',
            ],
            // jsonp
            'jsonp'         => [
                // 类路径
                'class'        => '\framework\web\Jsonp',
                // callback名称
                'callbackName' => 'callback',
            ],
            // xml
            'xml'           => [
                // 类路径
                'class' => '\framework\web\Xml',
            ],
        ],

        // 错误
        'error'    => [
            // 类路径
            'class'  => 'framework\web\Error',
            // 输出格式
            'format' => \framework\web\Error::FORMAT_HTML,
        ],

    ],

    // 对象配置
    'objects'             => [

        // HttpServer
        'httpServer' => [

            // 类路径
            'class'        => '\servers\HttpServer',
            // 主机
            'host'         => '127.0.0.1',
            // 端口
            'port'         => 9501,

            // 运行时的各项参数：https://wiki.swoole.com/wiki/page/274.html
            'setting'      => [
                // 连接处理线程数
                'reactor_num' => 8,
                // 工作进程数
                'worker_num'  => 8,
                // 设置worker进程的最大任务数
                'max_request' => 10000,
                // 日志文件路径
                'log_file'    => '/tmp/mix-httpd.log',
                // 子进程运行用户
                /* 'user'        => 'www', */
            ],


        ],

    ],
];
