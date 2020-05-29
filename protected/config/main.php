<?php
session_start();
error_reporting(E_ALL & ~E_WARNING);

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.


$server_name	= $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
$is_production	= in_array($server_name, ['app.etunti.fi', 'etunti.com']);
$get_domain	= trim(strtolower($_GET['dom'] ?? $_GET['domain'] ?? ''));
$actual_link	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path 		= parse_url($actual_link);
$oinlinevaraus 	= ( isset($path['path']) and strpos($path['path'], "onlinevaraus") !== false )? true: false;

// 27.05.2020
$siirto_domainit = [
	"demo", "sivex", "digisten",
// Ilmaiset
"amazing_city_oy", // ok
"amazone", // suljettu
"arjen_avuxi_oy", // ok
"arkkitiimi_osuuskunta__huoltopojat", // ok
"askeleet_polulle", // ok
"ateljee_kuvastin_oy", // ok
"awareness_recreated_oy", // ok
"badbaado_ry", // ok
"bado_palvelut_oy", // ok
"baltic_palvelu", // ok
"beauty", // ok
"blue_tower_oy",
"brosiivous", // ok
"camofin_oy", // ok
"city_saukko_oy", // ok
"cleana", // ok
"cleana_oy", // ok
"cleanmarin_oy", // ok
"costan_kotihoito", // ok
"cutiopalvelut_oy", // ok
"derram_oy", // ok
"eiole_oy", // ok
"elamyspalvelu_kolmipyora", // ok
"electric_shark_tmi", // ok
"elevent_group_oy", // ok
"esittely", // ok
"finndiiling_oy", // ok
"fritec_oy", // ok
"goldenshine", // suljettu
"greenwitch_oy", // ok
"hammslaboratorio_pure_art_oy", // ok
"happy_bowling_oy", // ok
"helin_siivouspalvelu", // suljettu
"hm_tasoite_oy", // ok
"hoitokoti", // ok
"inkan_kotitalouspalvelut_oy", // ok
"jani_malmberg", // ok
"jarvo_oy", // ok
"katinala_av_oy", // ok
"kaveko_oy", // ok
"kiurun_kuriiri_oy", // ok
"kivat_pihat", // ok
"kmvkotipalvelut_oy", // ok
"kortipalvelu_raikastuuli_profit_dealers_oy", // ok
"koti1_palvelut_oy", // ok
"kotipalvelu_pajunkissa", // ok
"kotipalvelu_sydankapy_oy", // ok
"kotipari_oy", // ok
"koti_consulting_oy", // ok
"koti_puhtaaksi_oy", // suljettu
"koti_puhtaaksi_testi", // ok
"kototiimi_oy", // ok
"kotova_oy", // ok
"krassat_oy", // ok
"kuljetus_pennanen", // ok
"kuljetus_vkujala_oy", // ok
"kvhpalvelut", // ok
"kvkoneurakointi_oy", // ok
"k__m_elokuvat_oy", // ok
"labsense_oy", // ok
"lecator_oy", // ok
"leenan_puhdistuspalvelu_oy", // ok
"luminous_palvelut_oy", // ok
"majatalo_kupittaa_oy", // ok Oli paljon tyovuoroja
"ma_testaan_tuttavalle_tsaana", // suljettu
"mitoka", // ok
"moppimuija", // ok
"mukanas", // ok
"multivision_tmi", // ok
"muuble_oy", // ok
"myhelp", // ok
"nixisiivous", // ok
"oiva_kotisiivous", // ok
"oma_yritys", // suljettu
"onnellisten_tiimi", // ok
"opepooli_oy", // ok
"oxline_oy", // ok
"paakaupunkiseudun_spsiivous_oy", // ok
"perustettava_yhtio", // ok
"pesiola_oy", // ok
"petax_oy", // ok
"pirkanmaan_aluesiivous_oy", // ok
"planca_oy", // ok
"pohjanmaan_kotisiivous", // ok
"puhdas_unelma_oy", // ok
"puskapromotion", // ok
"remaster", // ok
"rlkotipalvelut_oy", // ok
"rlyhtiot_oy_", // ok
"royal_clean_oy", // ok
"rt_work_oy", // ok
"rvk_palvelut_oy", // ok
"saaristo_kotipalvelu", // ok
"santelo", // ok
"sapindus_oy", // ok
"saronia_oy", // ok
"seoptimi_oy", // ok
"siiivouspalvelu_kuura_oy", // ok
"siivousliike_puuninki", // ok paljon tyovuoroja
"siivouspalvelu_forssan_ilona_oy", // ok
"siivouspalvelu_humalajoki_oy", // ok
"siivouspalvelu_jelppi_", // ok
"siivouspalvelu_minhof", // ok
"siivouspalvelu_tiina_vidgren", // ok
"siivouspro", // ok
"siivoussissit", // suljettu
"sinun_avuksi_oy", // ok
"sirius_media_oy", // ok
"sisustussuunnittelu_jonita_raikko", // ok
"sk_puhtaaksi_oy", // ok
"soittolinja_oy", // ok
"staffone_oy", // ok
"stone_partners_oy", // ok
"stt_sahko_mikkeli_oy", // ok
"stvalmennus_oy", // ok
"suomen_art_ry", // ok
"swappie_oy", // ok
"sysman_autoservis_oy", // ok
"takomee_tmi", // ok
"taloke_tmi", // ok
"talouspaja_oy", // ok
"tampereen_siivous", // suljettu
"the_process__health__performance_oy", // ok
"tietohallintomaisterit_oy", // ok
"tmi_ari_paatelainen", // ok
"tmi_hannan_kotiapu", // ok
"tmi_maisa_kuhno", // ok
"tulola_oy", // ok
"turvapalvelut_salminen", // ok
"upsteam_oy", // ok
"vaakkutech_oy", // ok
"vbe_service_oy", // ok
"vlp", // suljettu
"yleissiivous_oy", // ok
// Maksulliset
"must", // suljettu
"malli", // ok
"kiffel_oy", // suljettu
"unelmaworks", // suljettu
"tehopro", // suljettu
"talonmiehet", // suljettu
"soukan", // suljettu
"qclean", // suljettu
"pian_siivous_ja_kotiapu", // suljettu
"mrtiimi", // suljettu
"monessa", // suljettu
"mlpuhdistuspalvelu", // suljettu
"lindaclean", // suljettu
"lakaisupalvelu", // suljettu
"korkea_tekniikka_oy", // ok
"kifsaa", // suljettu
"koti1", // ok
"etunti_testi", // ok
"testi", // ok
"tunti", // suljettu
"kotipuhtaaksi", // ok
"siivouspalvelukota", // ok
"alueenykkosketju", // ok

/*
"asennesiivous",
"etunti",
"kairen",
"klaara",
"kotipalvelu_aura_oy",
"loimaa",
"moppi",
"realclean",
"siivouspalvelu_loiste_oy",
"talentas_oy",
"tamsii",
"toimiva",
"tomuttamo_oy",
"vayla_ry",
"washup",
*/
];

// <-- Redirect Domain; app.etunti.fi|etunti.com => apps.etunti.fi
if ($is_production and empty($_FILES) and !$oinlinevaraus) {
    if (isset($_SESSION['domain']) and in_array($_SESSION['domain'], $siirto_domainit)) {
        unset($_SESSION['domain']);
    }
    if (in_array($get_domain, $siirto_domainit)) {
        header("Access-Control-Allow-Origin: *");
        $url = "https://apps.etunti.fi" . $_SERVER['REQUEST_URI'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $_POST);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        header('Content-Type: text/html');
        echo curl_exec($ch);
        exit;
    } elseif (isset($_POST['UserLogin']['domain']) and in_array(trim(strtolower($_POST['UserLogin']['domain'])), $siirto_domainit)) {
        echo '
        <!DOCTYPE html>
        <html>
            <body onload="document.forms[0].submit()">
                <form action="https://apps.etunti.fi/index.php/user/login" method="post">
                <input type="hidden" name="UserLogin[domain]" value="' . $_POST['UserLogin']['domain'] . '">
                <input type="hidden" name="UserLogin[username]" value="' . $_POST['UserLogin']['username'] . '">
                <input type="hidden" name="UserLogin[password]" value="' . $_POST['UserLogin']['password'] . '">
                </form>
            </body>
        </html>';
        exit;
    }
}
//    Redirect Domain -->

if (
    isset($_SERVER['REQUEST_URI'])
    and
    (strpos($_SERVER['REQUEST_URI'], "WHERE") !== false
        or strpos($_SERVER['REQUEST_URI'], "where") !== false
        or strpos($_SERVER['REQUEST_URI'], "%20AND%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "%20and%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "%20union%20all") !== false
        or strpos($_SERVER['REQUEST_URI'], "UNION%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "when%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "WHEN%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "select%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "SELECT%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "BOOLEAN%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "CASE%20") !== false
        or strpos($_SERVER['REQUEST_URI'], "%20or%20") !== false)
) {
    //header('Location: https://www.google.com/');
    exit;
    //mail('laptopsr@gmail.com', 'Blocked IP', 'IP '.$_SERVER['REMOTE_ADDR']);
    //die("IP: ".$_SERVER['REMOTE_ADDR']);
}

if (isset($_SERVER['REMOTE_ADDR']) and ($_SERVER['REMOTE_ADDR'] == '5.8.8.200' or $_SERVER['REMOTE_ADDR'] == '103.224.81.80')) {
    //header('Location: https://www.google.com/');
    exit;
    //mail('laptopsr@gmail.com', 'Blocked IP', 'IP '.$_SERVER['REMOTE_ADDR']);
    //die("Может успокоишся уже?");
}

if (
    !isset($_SESSION['domain'])
    and isset($_POST['UserLogin']['domain'])
    and !empty($_POST['UserLogin']['domain'])
    and trim($_POST['UserLogin']['domain']) != 'superadmin'
) {
    $_SESSION['domain'] = trim(strtolower($_POST['UserLogin']['domain']));

    // Add 'staging_' prefix to domain on staging server.
    if (strpos($server_name, "staging") === 0)
        $_SESSION['domain'] = "staging_" . $_SESSION['domain'];
}

if (isset($_GET['lang']))
    $_SESSION['lang'] = $_GET['lang'];

if (isset($_GET['dom']))
    $_SESSION['domain'] = trim(strtolower($_GET['dom']));

if (isset($_GET['domain']))
    $_SESSION['domain'] = trim(strtolower($_GET['domain']));

if (isset($_POST['domain']))
    $_SESSION['domain'] = trim(strtolower($_POST['domain']));

$lang = 'fi';
if (isset($_SESSION['lang']) and !empty($_SESSION['lang']))
    $lang = $_SESSION['lang'];

if (isset($_SESSION['domain'])) $domain = $_SESSION['domain'];
else $domain = 'Ei esitetty';
if (isset($_SERVER['HTTP_REFERER'])) $refer = $_SERVER['HTTP_REFERER'];
else $refer = '';
if (isset($_POST)) $post = json_encode($_POST);
else $post = '';


// <-- LOG
if (
    isset($_SERVER['REMOTE_ADDR']) and
    ($_SERVER['REMOTE_ADDR'] == '::1'
        or $_SERVER['REMOTE_ADDR'] == '127.0.0.1')
) {
    $for_log = [
        [
            'class' => 'CFileLogRoute',
            'levels' => 'error, warning', //'trace, info, error, warning, vardump'
            'enabled' => YII_DEBUG,
            //'categories'=>'system.*',
        ],
        [
            'class' => 'CWebLogRoute',
            'levels' => 'error, warning', //'trace, info, error, warning, vardump'
        ],
        /*
        [
            // lists execution time of every marked code block
            // report can also be set to callstack
            'class' => 'CProfileLogRoute',
            'report' => 'summary'
        ]
*/
    ];
} else {

    $remote_addr = '';
    if (isset($_SERVER['REMOTE_ADDR']))
        $remote_addr = $_SERVER['REMOTE_ADDR'];

    $for_log = array(
        array(
            'class' => 'CFileLogRoute',
            'levels' => 'error', //'trace, info, error, warning, vardump'
            'enabled' => YII_DEBUG,
            //'categories'=>'system.*',
        ),
        array(
            'class' => 'CWebLogRoute',
            'levels' => 'error, warning', //'trace, info, error, warning, vardump'

        ),
        array(
            'class' => 'CEmailLogRoute',
            'levels' => 'error', //'trace, info, error, warning, vardump'
            'emails' => 'laptopsr@gmail.com', // vikailmoitusetunti@gmail.com pass: Otto5566
            'subject' => 'Log File Message. Domain: ' . $domain . ', IP: ' . $remote_addr . ', SID: ' . session_id() . ', refer: ' . $refer . ', post: ' . $post,
        )
    );
}
//     LOG -->


// db host|user|pw are defined in main.pw.php (not in repository).
require('main.pw.php');

if (isset($_SESSION['domain'])) {
    $db2 = $_SESSION['domain'];
}
if (isset($_GET['dom'])) {
    $conn = mysqli_connect($db_host, $etuntifw_user, $etuntifw_pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = mysqli_query($conn, "SELECT domain FROM domainit WHERE kirjautumistunnus='" . $_GET['dom'] . "' ") or die(mysqli_error($db));
    if ($row = mysqli_fetch_array($sql)) {
        $db2 = $row['domain'];
    }
}


return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Etunti',
    //'defaultController'=>'mobile/index',
    // preloading 'log' component
    'preload' => array('log'), //'log'
    'language' => $lang,

    //'theme' => $theme,

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
        'application.extensions.carouFredSel.*',
        'ext.YiiMailer.YiiMailer',
        'application.vendors.*',
        //'application.extensions.EasySlider.*',
    ),

    'modules' => array(
        #...
        'user' => array(
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

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'kristina',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'generatorPaths' => array('ext.mpgii'), //this line does the trick

        ),

    ),

    // application components
    'components' => array(


        /* pdf */
        'ePdf' => array(
            'class'         => 'ext.yii-pdf.EYiiPdf',
            'params'        => array(
                'mpdf'     => array(
                    'librarySourcePath' => 'application.vendors.mpdf.*',
                    'constants'         => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder
                    /*'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                    'mode'              => '', //  This parameter specifies the mode of the new document.
                    'format'            => 'A4', // format A4, A5, ...
                    'default_font_size' => 0, // Sets the default document font size in points (pt)
                    'default_font'      => '', // Sets the default font-family for the new document.
                    'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                    'mgr'               => 15, // margin_right
                    'mgt'               => 16, // margin_top
                    'mgb'               => 16, // margin_bottom
                    'mgh'               => 9, // margin_header
                    'mgf'               => 9, // margin_footer
                    'orientation'       => 'P', // landscape or portrait orientation
                )*/
                ),
                'HTML2PDF' => array(
                    'librarySourcePath' => 'application.vendors.html2pdf.*',
                    'classFile'         => 'html2pdf.class.php', // For adding to Yii::$classMap
                    /*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                    'orientation' => 'P', // landscape or portrait orientation
                    'format'      => 'A4', // format A4, A5, ...
                    'language'    => 'en', // language: fr, en, it ...
                    'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
                    'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
                    'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                )*/
                )
            ),
        ),
        /* pdf */


        'urlManager' => array(
            'urlFormat' => 'path',
            //'showScriptName'=>false,
            //'caseSensitive'=>false, 
            'rules' => array(

                'mob/<id:\d+>/<title:.*?>' => 'mob/view',
                'posts/<tag:.*?>' => 'mob/index',

                // REST patterns
                //array('api/list', 'pattern'=>'api/<model:\w+>', 'verb'=>'GET'),
                //array('api/view', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'GET'),
                array('api/imei', 'pattern' => 'api/<model:\w+>/imei', 'verb' => 'POST'),
                array('api/tiedosto', 'pattern' => 'api/<model:\w+>/tiedosto', 'verb' => 'POST'),
                array('api/lang', 'pattern' => 'api/<model:\w+>/lang', 'verb' => 'POST'),
                array('api/asetukset', 'pattern' => 'api/<model:\w+>/asetukset', 'verb' => 'POST'),
                array('api/paivita_tiedot', 'pattern' => 'api/<model:\w+>/paivita_tiedot', 'verb' => 'POST'),
                array('api/check_admin', 'pattern' => 'api/<model:\w+>/check_admin', 'verb' => 'POST'),
                array('api/adminkalut', 'pattern' => 'api/<model:\w+>/adminkalut', 'verb' => 'POST'),
                array('api/login', 'pattern' => 'api/<model:\w+>/login', 'verb' => 'POST'),

                // <-- DICO
                array('dico/login', 'pattern' => 'dico/<model:\w+>/login', 'verb' => 'POST'),
                array('dico/check', 'pattern' => 'dico/<model:\w+>/check', 'verb' => 'POST'),
                array('dico/historia', 'pattern' => 'dico/<model:\w+>/historia', 'verb' => 'POST'),
                array('dico/kohteet', 'pattern' => 'dico/<model:\w+>/kohteet', 'verb' => 'POST'),
                array('dico/getlaskupdf', 'pattern' => 'dico/<model:\w+>/getlaskupdf', 'verb' => 'POST'),
                array('dico/tarjoukset', 'pattern' => 'dico/<model:\w+>/tarjoukset', 'verb' => 'POST'),
                array('dico/sopimukset', 'pattern' => 'dico/<model:\w+>/sopimukset', 'verb' => 'POST'),
                array('dico/tyonkuvaukset', 'pattern' => 'dico/<model:\w+>/tyonkuvaukset', 'verb' => 'POST'),
                array('dico/muuttiedostot', 'pattern' => 'dico/<model:\w+>/muuttiedostot', 'verb' => 'POST'),
                array('dico/info', 'pattern' => 'dico/<model:\w+>/info', 'verb' => 'POST'),
                array('dico/recovery', 'pattern' => 'dico/<model:\w+>/recovery', 'verb' => 'POST'),
                array('dico/kayttoehdot', 'pattern' => 'dico/<model:\w+>/kayttoehdot', 'verb' => 'POST'),
                array('dico/omat', 'pattern' => 'dico/<model:\w+>/omat', 'verb' => 'POST'),
                array('dico/tilaus', 'pattern' => 'dico/<model:\w+>/tilaus', 'verb' => 'POST'),
                array('dico/viestinta', 'pattern' => 'dico/<model:\w+>/viestinta', 'verb' => 'POST'),

                //     DICO -->

                //array('api/update', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'PUT'),
                //array('api/updaterow', 'pattern'=>'api/<model:\w+>/updaterow/<id:\d+>', 'verb'=>'POST'),
                //array('api/delete', 'pattern'=>'api/<model:\w+>/<id:\d+>', 'verb'=>'DELETE'),
                //array('api/create', 'pattern'=>'api/<model:\w+>', 'verb'=>'POST'),
                // Other controllers
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),


        'clientScript' => array(
            'scriptMap' => array(
                'jquery.js' => false,  //disable default implementation of jquery
                'jquery.min.js' => false,  //desable any others default implementation
                'core.css' => false, //disable
                //'styles.css'=>false,  //disable
                //'pager.css'=>false,   //disable
                'default.css' => false,  //disable
            ),
            'packages' => array(
                'jquery' => array(                             // set the new jquery
                    'baseUrl' => 'js/',
                    'js' => array(
                        'jquery-1.11.2.min.js',
                        'jquery-ui.min.js',
                        'moment-with-locales.js',
                        'bootstrap-select.js',
                        'bootstrap-switch.js',
                        'bootstrap-datetimepicker.js',
                        'bootstrap-datepicker.js',
                        'bootstrap-datepicker.fi.js',
                        'bootstrap-datetimepicker.fi.js',
                        'asetukset.js',
                    ),
                ),
                'bootstrapJS' => array(                       //set others js libraries
                    'baseUrl' => 'js/',
                    'js' => array(
                        'jquery-ui.min.js',
                        'bootstrap355.min.js',
                        'bootstrap-slider.js',
                        'bootstrap-filestyle.min.js',
                        'bootstrap-select.js',
                        'bootstrap-switch.js',
                    ),
                    'depends' => array('jquery'),         // cause load jquery before load this.
                ),
                'fixedTable' => array(                       //set others js libraries
                    'baseUrl' => 'js/',
                    'js' => array(
                        'jquery-migrate-1.2.1.min.js',
                        'jquery.dataTables.min.js',
                        'FixedColumns.js',
                    ),
                    'depends' => array('jquery'),         // cause load jquery before load this.
                ),
                'tyovuoroot' => array(                       //set others js libraries
                    'baseUrl' => 'js/',
                    'js' => array(
                        'tvuoroot.js',
                    ),
                    'depends' => array('jquery'),         // cause load jquery before load this.
                ),
                'toteuma' => array(                       //set others js libraries
                    'baseUrl' => 'js/',
                    'js' => array(
                        'toteuma.js',
                    ),
                    'depends' => array('jquery'),         // cause load jquery before load this.
                ),
                'multiselect' => array(                       //set others js libraries
                    'baseUrl' => 'js/',
                    'js' => array(
                        'jquery.multiselect.min.fi.js',
                    ),
                    'depends' => array('jquery'),         // cause load jquery before load this.
                ),
                'bootstrapCSS' => array(                       //set others js libraries
                    'baseUrl' => 'css/',
                    'css' => array(
                        'jquery-ui.min.css',                      // and css
                        'bootstrap355.min.css',
                        'bootstrap-theme355.min.css',
                        //'etunti-bootstrap-theme.css',
                        'bootstrap-switch.css',
                        'bootstrap-select.min.css',
                        'bootstrap-slider.css',
                        'multiselect.css',
                        //'datepicker.css',
                        'bootstrap-datepicker.css',
                        'font-awesome.min.css',
                    ),

                ),
            ),
        ),



        //'chartjs' => array('class' => 'chartjs.components.ChartJs'),

        'session' => array(
            'class' => 'CHttpSession',
            'timeout' => 86400,
            'autoStart' => true,
        ),

        'user' => array(
            // enable cookie-based authentication
            'class' => 'WebUser',
            'allowAutoLogin' => true,
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

        'db' => array(
            'connectionString' => 'mysql:host=' . $db_host . ';dbname=' . $db,
            'emulatePrepare' => true,
            'username' => $etuntifw_user,
            'password' => $etuntifw_pass,
            'tablePrefix' => 'tbl_',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        'db1' => array(
            'connectionString' => 'mysql:host=' . $db2_host . ';dbname=' . $db2,
            'emulatePrepare' => true,
            'username' => $db2_user,
            'password' => $db2_pass,
            'tablePrefix' => '',
            //'charset' => 'utf8',
            'class' => 'CDbConnection',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => $for_log,
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'etuntiEmail' => 'etuntimarkkinointi@gmail.com', // ei kaytossa
        /*
        'RestfullYii' => array(
            'req.auth.ajax.user' => function(){
                if(isset($_SERVER['HTTP_X_REST_USERNAME']) and isset($_SERVER['HTTP_X_REST_PASSWORD'])) {
                    $username = trim($_SERVER['HTTP_X_REST_USERNAME']);
                    $password = trim($_SERVER['HTTP_X_REST_PASSWORD']);
                    $identity=new UserIdentity($username,$password);
                    if($identity->authenticate()){
                        Yii::app()->user->login($identity,0);
                        return true;
                    }
                    else{
                        return false;
                    }
                }
                return false;
            },
          ),
*/

    ),

    'aliases' => array(
        'RestfullYii' => realpath(__DIR__ . '/../extensions/starship/RestfullYii'),
    ),



);
