<?php

// check if we're in development (local) environment.
$development_environment = in_array($_SERVER['REMOTE_ADDR'] ?? null, ['::1', '127.0.0.1'])
                        && strpos($_SERVER['HTTP_REFERER'] ?? null, "api/mob") === false;

// enable debug mode in development environments.
defined('YII_DEBUG') or define('YII_DEBUG', $development_environment);

// how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

// run application - change the following paths if necessary
$yii=dirname(__FILE__).'/YII/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
require_once($yii);
Yii::createWebApplication($config)->run();
