<?php
ini_set(’session.gc_maxlifetime’, 86400);

// change the following paths if necessary
$yii=dirname(__FILE__).'/YII/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode

if( 
	($_SERVER['REMOTE_ADDR'] == '::1' 
	or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' 
	or strpos($_SERVER['HTTP_REFERER'], "dev") !== false
	)
	and (isset($_SERVER['HTTP_REFERER']) and strpos($_SERVER['HTTP_REFERER'], "api/mob") === false )
)
{
	defined('YII_DEBUG') or define('YII_DEBUG',true);
} else {
	defined('YII_DEBUG') or define('YII_DEBUG',false);
}


	//defined('YII_DEBUG') or define('YII_DEBUG',false);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
