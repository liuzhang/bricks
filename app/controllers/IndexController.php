<?php

namespace app\controllers;

/**
 * 默认控制器
 * @author 刘健 <coder.liu@qq.com>
 */
class IndexController extends \framework\web\Controller
{

    // 默认动作
    public function actionIndex()
    {
        return 'Hello World' . PHP_EOL;
    }

    // 默认动作
    public function actionTest()
    {
        return $this->render('web_site_example', ['message' => '新增成功']);
    }
}
