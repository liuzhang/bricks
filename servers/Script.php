<?php
/**
 * Created by PhpStorm.
 * User: liuzhang
 * Date: 2018/4/5
 * Time: 下午4:55
 */

namespace servers;
use servers\libraries\Service;

class Script
{
    // 是否后台运行
    protected $d = false;

    // 是否热更新
    protected $u = false;

    public  function start()
    {
        if ($pid = Service::getPid()) {
            return "mix-httpd is running, PID : {$pid}." . PHP_EOL;
        }
        $server = \framework\base\Bricks::app()->createObject('httpServer');
        if ($this->u) {
            $server->setting['max_request'] = 1;
        }
        $server->setting['daemonize'] = $this->d;
        return $server->start();
    }

    public function stop()
    {
        if ($pid = Service::getPid()) {
            Service::killMaster($pid);
            while (Service::isRunning($pid)) {
            }
            return 'mix-httpd stop completed.' . PHP_EOL;
        } else {
            return 'mix-httpd is not running.' . PHP_EOL;
        }
    }

    public  function restart()
    {
        $this->stop();
        $this->start();
    }
}