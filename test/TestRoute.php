<?php
require_once '../vendor/autoload.php';

use framework\base\Route;
use framework\base\Application;

$route = new Route();
$config  = require_once '../config/main_httpd.php';
$app = new Application($config);
echo $app->runAction('GET', 'index/index', []);