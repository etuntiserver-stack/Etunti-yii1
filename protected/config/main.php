<?php
session_start();
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

Yii::setPathOfAlias('chartjs', dirname(__FILE__).'/../extensions/yii-chartjs');

  if(isset($_POST['UserLogin']['domain']) and !empty($_POST['UserLogin']['domain']) and $_POST['UserLogin']['domain'] != 'superadmin')
  $_SESSION['domain'] = $_POST['UserLogin']['domain'];


  $db = 'etuntifw';
  $db_host = 'localhost';
  $etuntifw_user = 'root';
  $etuntifw_pass = '';

if(isset($_SESSION['domain']))
  $db2 = $_SESSION['domain'];
else
  $db2 = '';
  $db2_host = 'localhost';
  $db2_user = 'root';
  $db2_pass = '';

/*
  $db = 'etuntifw';
  $db_host = 'eu-cdbr-azure-north-d.cloudapp.net';
  $etuntifw_user = 'bb4018da4bf8cb';
  $etuntifw_pass = '97ea15c4';

  $db2 = $_SESSION['domain'];
  $db2_host = 'eu-cdbr-azure-north-d.cloudapp.net';
  $db2_user = 'root';
  $db2_pass = '';
*/

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Etunti',
	//'defaultController'=>'sivexkuitti/index',
	// preloading 'log' component
	'preload'=>array('log','chartjs'),
	'language' => 'fi',
	// autoloading model and component classes
	'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
	'application.extensions.carouFredSel.*',
	//'application.extensions.EasySlider.*',

	),

    'modules'=>array(
        #...
        'user'=>array(
            # encrypting method (php hash function)
            'hash' => 'md5',
 
            # send activation email
            'sendActivationMail' => true,
 
            # allow access for non-activated users
            'loginNotActiv' => false,
 
            # activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => false,
 
            # automatically login from registration
            'autoLogin' => true,
 
            # registration path
            'registrationUrl' => array('/user/registration'),
 
            # recovery password path
            'recoveryUrl' => array('/user/recovery'),
 
            # login form path
            'loginUrl' => array('/user/login'),
 
            # page after login
            'returnUrl' => array('/user/profile'),
 
            # page after logout
            'returnLogoutUrl' => array('/user/login'),
        ),
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'kristina',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
			'generatorPaths' =>array('ext.mpgii'),//this line does the trick

		),
		
	),

	// application components
	'components'=>array(
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>require(
                dirname(__FILE__).'/../extensions/starship/restfullyii/config/routes.php'
            ),
        ),


	'clientScript' => array(
          'scriptMap' => array(
            'jquery.js'=>false,  //disable default implementation of jquery
            'jquery.min.js'=>false,  //desable any others default implementation
            'core.css'=>false, //disable
            //'styles.css'=>false,  //disable
            //'pager.css'=>false,   //disable
            'default.css'=>false,  //disable
        ),
        'packages'=>array(
            'jquery'=>array(                             // set the new jquery
                'baseUrl'=>'js/',
                'js'=>array('jquery-1.11.2.min.js'),
            ),
            'bootstrapJS'=>array(                       //set others js libraries
                'baseUrl'=>'js/',
                'js'=>array(
		    'bootstrap355.min.js',
		    'bootstrap-slider.js',
		    'bootstrap-filestyle.min.js',
		    'bootstrap-select.js',
		    'bootstrap-switch.js',
	  	),
                'depends'=>array('jquery'),         // cause load jquery before load this.
            ),
            'fixedTable'=>array(                       //set others js libraries
                'baseUrl'=>'js/',
                'js'=>array(
		    'jquery-migrate-1.2.1.min.js',
		    'jquery.dataTables.min.js',
		    'FixedColumns.js',
	  	),
                'depends'=>array('jquery'),         // cause load jquery before load this.
            ),
            'bootstrapCSS'=>array(                       //set others js libraries
                'baseUrl'=>'css/',
                'css'=>array(  
                    'jquery-ui.min.css',                      // and css
                    'bootstrap355.min.css',
                    'bootstrap-theme355.min.css',
                    'bootstrap-switch.css',
                    'bootstrap-select.min.css',
                    'bootstrap-slider.css',
                ),

            ),
        ),
    ),



	'chartjs' => array('class' => 'chartjs.components.ChartJs'),

        'user'=>array(
            // enable cookie-based authentication
            'class' => 'WebUser',
            'allowAutoLogin'=>true,
            'loginUrl' => array('/user/login'),
        ),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host='.$db_host.';dbname='.$db,
			'emulatePrepare' => true,
			'username' => $etuntifw_user,
			'password' => $etuntifw_pass,
		        'tablePrefix' => 'tbl_',
			//'charset' => 'utf8',
		),
        	'db1'=>array(
	            'connectionString' => 'mysql:host='.$db2_host.';dbname='.$db2,
	            'emulatePrepare' => true,
	            'username' => $db2_user,
	            'password' => $db2_pass,
	            'tablePrefix' => '',
		    'class'=> 'CDbConnection'
        	),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),

	'aliases' => array(
        'RestfullYii' =>realpath(__DIR__ . '/../extensions/starship/RestfullYii'),
	),

);
