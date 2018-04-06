<?php

namespace framework\base;

/**
 * 对象基类
 * @author Liu Zhang <lz-850610@163.com>
 */
class BaseObject
{

    // 构造
    public function __construct($attributes = [])
    {
        $this->onConstruct();
        foreach ($attributes as $name => $attribute) {
            $this->$name = $attribute;
        }
        $this->onInitialize();
    }

    // 构造事件
    public function onConstruct()
    {
    }

    // 初始化事件
    public function onInitialize()
    {
    }

    // 析构事件
    public function onDestruct()
    {
    }

    // 析构
    public function __destruct()
    {
        $this->onDestruct();
    }

}
