<?php

namespace framework\base;

/**
 * App类
 * @author Liu Zhang <lz-850610@163.com>
 *
 */
class Application
{

    // 基础路径
    public $basePath = '';

    // 控制器命名空间
    public $controllerNamespace = '';

    // 组件配置
    public $components = [];

    // 对象配置
    public $objects = [];

    // 组件容器
    protected $_components;

    // NotFound错误消息
    protected $_notFoundMessage = '';

    // 组件命名空间
    protected $_componentNamespace;

    // 构造
    public function __construct($attributes)
    {
        // 导入属性
        foreach ($attributes as $name => $attribute) {
            $this->$name = $attribute;
        }
        // 快捷引用
        \framework\base\Bricks::setApp($this);
    }

    // 设置组件命名空间
    public function setComponentNamespace($namespace)
    {
        $this->_componentNamespace = $namespace;
    }

    // 创建对象
    public function createObject($name)
    {
        return \framework\base\Bricks::createObject($this->objects[$name]);
    }

    // 装载组件
    public function loadComponent($name)
    {
        // 未注册
        if (!isset($this->components[$name])) {
            throw new \framework\exception\ComponentException("组件不存在：{$name}");
        }
        // 使用配置创建新对象
        $object = \framework\base\Bricks::createObject($this->components[$name]);
        // 组件效验
        if (!($object instanceof Component)) {
            throw new \framework\exception\ComponentException("不是组件类型：{$this->components[$name]['class']}");
        }
        // 装入容器
        $this->_components[$name] = $object;
    }

    // 执行功能并返回
    public function runAction($method, $action, $controllerAttributes = [])
    {
        $action = "{$method} {$action}";
        // 路由匹配
        $items = \framework\base\Bricks::app()->route->match($action);
        foreach ($items as $item) {
            list($action, $queryParams) = $item;
            // 执行功能
            if ($action) {
                // 路由参数导入请求类
                \framework\base\Bricks::app()->request->setRoute($queryParams);
                // 实例化控制器
                $action    = "{$this->controllerNamespace}\\{$action}";
                $classFull = \framework\base\Route::dirname($action);
                $classPath = \framework\base\Route::dirname($classFull);
                $className = \framework\base\Route::snakeToCamel(\framework\base\Route::basename($classFull), true);
                $method    = \framework\base\Route::snakeToCamel(\framework\base\Route::basename($action), true);
                $class     = "{$classPath}\\{$className}Controller";
                $method    = "action{$method}";
                if (class_exists($class)) {
                    $controller = new $class($controllerAttributes);
                    // 判断方法是否存在
                    if (method_exists($controller, $method)) {
                        // 执行前置动作
                        $controller->beforeAction();
                        // 执行控制器的方法
                        $result = $controller->$method();
                        // 执行后置动作
                        $controller->afterAction();
                        // 返回执行结果
                        return $result;
                    }
                }
            }
        }
        throw new \framework\exception\NotFoundException($this->_notFoundMessage);
    }

    // 获取配置目录路径
    public function getConfigPath()
    {
        return $this->basePath . 'config' . DIRECTORY_SEPARATOR;
    }

    // 获取运行目录路径
    public function getRuntimePath()
    {
        return $this->basePath . 'runtime' . DIRECTORY_SEPARATOR;
    }

    // 打印变量的相关信息
    public function varDump($var, $send = false)
    {
        ob_start();
        var_dump($var);
        $content = ob_get_clean();
        \framework\base\Bricks::app()->response->content .= $content;
        if ($send) {
            throw new \framework\exception\DebugException(\framework\base\Bricks::app()->response->content);
        }
    }

    // 打印关于变量的易于理解的信息
    public function varPrint($var, $send = false)
    {
        ob_start();
        print_r($var);
        $content = ob_get_clean();
        \framework\base\Bricks::app()->response->content .= $content;
        if ($send) {
            throw new \framework\exception\DebugException(\framework\base\Bricks::app()->response->content);
        }
    }

    // 终止程序
    public function end($response = null)
    {
        throw new \framework\exception\EndException($response);
    }

    // 获取组件
    public function __get($name)
    {
        if (!is_null($this->_componentNamespace)) {
            $name = "{$this->_componentNamespace}.{$name}";
        }
        // 返回单例
        if (isset($this->_components[$name])) {
            // 触发请求开始事件
            if ($this->_components[$name]->getStatus() == Component::STATUS_READY) {
                $this->_components[$name]->onRequestStart();
            }
            // 返回对象
            return $this->_components[$name];
        }
        // 装载组件
        $this->loadComponent($name);
        // 触发请求开始事件
        $this->_components[$name]->onRequestStart();
        // 返回对象
        return $this->_components[$name];
    }

    // 装载全部组件
    public function loadAllComponent()
    {
        foreach (array_keys($this->components) as $name) {
            $this->loadComponent($name);
        }
    }

    // 清扫组件容器
    public function cleanComponents()
    {
        foreach ($this->_components as $component) {
            if ($component->getStatus() == Component::STATUS_RUNNING) {
                $component->onRequestEnd();
            }
        }
    }

    // 获取公开目录路径
    public function getPublicPath()
    {
        return $this->basePath . 'public' . DIRECTORY_SEPARATOR;
    }

    // 获取视图目录路径
    public function getViewPath()
    {
        return $this->basePath . 'app/views' . DIRECTORY_SEPARATOR;
    }

}
