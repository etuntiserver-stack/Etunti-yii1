<?php

// Define global environment variables on first session launch.
if (!defined('DEV_ENV')) {

  /** @var LOCAL True if request is from local host. */
  define('LOCAL', in_array($_SERVER['REMOTE_ADDR'] ?? '', ['::1', '127.0.0.1']));

  /** @var STAGING True if "staging" in host name; "staging_" prefixed to db. */
  define('STAGING', false !== strpos($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '', 'staging'));

  /** @var DEV_ENV True if this is dev environment and debug mode is enabled. */
  define('DEV_ENV', (LOCAL || STAGING) && strpos($_SERVER['HTTP_REFERER'] ?? '', "api/mob") === false);
}

// enable debug mode in development environments.
defined('YII_DEBUG') or define('YII_DEBUG', DEV_ENV);

// how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

// run application - change the following paths if necessary
$yii = dirname(__FILE__) . '/YII/framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/main.php';
require_once($yii);
Yii::createWebApplication($config)->run();
