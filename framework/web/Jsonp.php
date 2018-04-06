<?php

namespace framework\web;

use framework\base\BaseObject;

/**
 * JSONP 类
 * @author Liu Zhang <lz-850610@163.com>
 */
class Jsonp extends BaseObject
{

    // callback名称
    public $callbackName = 'callback';

    // 编码
    public function encode($array)
    {
        // 不转义中文、斜杠
        $jsonString = (new Json)->encode($array);
        $callback   = \Mix::app()->request->get($this->callbackName);
        if (is_null($callback)) {
            return $jsonString;
        }
        return $callback . '(' . $jsonString . ')';
    }

}
