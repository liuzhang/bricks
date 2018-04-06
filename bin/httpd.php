#!/usr/bin/env php
<?php
define('DEBUG', true);
define('ENV', 'dev');
require __DIR__ . '/../vendor/autoload.php';

use servers\Script;
use servers\libraries\CLI;
use framework\web\Application;
$params = CLI::handleArgv($argv);
$InitScipt = new Script();
$config = require '../config/main_httpd.php';
\framework\base\Bricks::setApp(new Application($config));

switch ($params['type'])
{
    case 'start':
        $InitScipt->start();
        break;

    case 'stop':
        $InitScipt->stop();
        break;

    case 'reload':
    case 'restart':
        $InitScipt->restart();
        break;
}



