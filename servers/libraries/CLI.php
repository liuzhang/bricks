<?php
/**
 * 处理命令端参数传递问题
 * User: liuzhang
 * Date: 2018/4/5
 * Time: 下午4:55
 */

namespace servers\libraries;


class CLI
{
    public static function handleArgv($args)
    {
        $actionTypes = ['start', 'stop', 'restart', 'reload'];
        $modes = ['-d'];
        $ret = [];

        foreach ($args as $arg) {

            if (in_array($arg, $actionTypes) && empty($ret['type']))
            {
                $ret['type'] = $arg;
            }

            if (in_array($arg, $modes) && empty($ret['mode']))
            {
                $ret['mode']  = $arg;
            }
        }

        return $ret;
    }
}