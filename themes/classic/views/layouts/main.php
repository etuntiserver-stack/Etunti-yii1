<!DOCTYPE html>
<html lang="en">
    <head>
      
<?php
if( isset(Yii::app()->user->domain) )
{
	$asetukset = Asetukset::model()->findByPk(1);
	$curpage_controller = Yii::app()->getController()->getAction()->controller->id;
}
?>

	<?php if(
		isset($curpage_controller)
		and $curpage_controller == 'onlinevaraus' 
		and isset($asetukset->gtm) 
		and !empty($asetukset->gtm) 
		and isset(Yii::app()->user->domain)
		) 
	: ?>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo $asetukset->gtm; ?>');</script>
        <!-- End Google Tag Manager -->

	<?php else : ?>

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WBRRX85');</script>
        <!-- End Google Tag Manager -->

	<?php endif; ?>

        
        <meta charset="utf-8">
        <title>Etunti - Liikkuva työ hallussa</title>
        <meta name="keywords" content="HTML5 Template" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


<?php
/*
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
    	$agent = $_SERVER['HTTP_USER_AGENT'];
    }

    if (strlen(strstr($agent, 'Firefox')) > 0) {
    echo "<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,300,700' rel='stylesheet' type='text/css'>";
    } else {
    echo "<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,700' rel='stylesheet' type='text/css'>";
    }
*/
?>
	<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,700' rel='stylesheet' type='text/css'>

        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/css/style.css?v=2.13"/>
        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/css/theme-responsive.css"/>
        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/css/skins/blue/blue.css?v=2" />
        <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/inc/lomake/lomake.css?v=2" />
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/modernizr.js"></script>

	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/img/favicon.ico">

        <!-- styles for IE -->
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="../css/ie/ie.css" type="text/css" media="screen" />
        <![endif]-->


        <!--[if lte IE 8]>
            <script src="../js/responsive/html5shiv.js"></script>
            <script src="../js/responsive/respond.js"></script>
        <![endif]-->
    </head>
    <body>


	<?php if(
		isset($curpage_controller)
		and $curpage_controller == 'onlinevaraus' 
		and isset($asetukset->gtm) 
		and !empty($asetukset->gtm) 
		and isset(Yii::app()->user->domain)
		) 
	: ?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $asetukset->gtm; ?>"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php else : ?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WBRRX85"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php endif; ?>


<?php
/*
$sub = explode(".",$_SERVER['HTTP_HOST']);
if (
		(! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off') 
		and ($_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1')
		and $sub[0] != 'staging'
) {

    if (strlen(strstr($agent, 'Firefox')) > 0) {

    } else {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url");
    exit();
    }

}
*/
?>

<?php 
//Yii::app()->clientScript->registerPackage('bootstrapCSS');
Yii::app()->clientScript->registerPackage('jquery');
?>


	<?php
	foreach(Yii::app()->user->getFlashes() as $key => $message) {
        	echo '<p><div class="container"><div class="alert bg-' . $key . '">' . $message . "</div></div></p>\n";
    	}
	?>
	<?php echo $content; ?>



    <!-- ======================= JQuery libs =========================== -->
<?php
/*
    <!-- jQuery local-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/jquery.js"></script>
*/
?>
    <!--Nav-->
     <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/nav/tinynav.js"></script>

    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/nav/jquery.sticky.js" type="text/javascript"></script>
    <!--Totop-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/totop/jquery.ui.totop.js" ></script>
    <!--Slide Revolution-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/rs-plugin/js/jquery.themepunch.tools.min.js" ></script>
    <script type='text/javascript' src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
    <!--Ligbox-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/fancybox/jquery.fancybox.js"></script>
    <!-- carousel.js-->
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/carousel/carousel.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/inc/lomake/lomake.js"></script>
    <!-- Parallax-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/parallax/jquery.inview.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/parallax/nbw-parallax.js"></script>
    <!--Theme Options-->
<!--     <script type="text/javascript" src="js/theme-options/theme-options.js"></script>
    <script type="text/javascript" src="js/theme-options/jquery.cookies.js"></script> -->
    <!-- Bootstrap.js-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/bootstrap/bootstrap.js"></script>
    <!--MAIN FUNCTIONS-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/main.js?v=1.13"></script>
    <!-- ======================= End JQuery libs =========================== -->

    <!--Slider Function-->
    <script type="text/javascript">
        var revapi;
        jQuery(document).ready(function() {
           revapi = jQuery('.tp-banner').revolution(
            {
                delay:9000,
                startwidth:1170,
                startheight:580,
                spinner:"spinner4",
                hideThumbs:10,
                fullWidth:"on",
                navigationType:"none",
                navigationArrows:"solo",
                navigationStyle:"preview4",
                forceFullWidth:"on"
            });
        });
    </script>
    <!--End Slider Function-->


    </body>
</html>
