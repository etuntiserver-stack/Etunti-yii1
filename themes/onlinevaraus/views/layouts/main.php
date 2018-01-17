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
        <title>Onlinevaraus</title>
        <meta name="keywords" content="Onlinevaraus" />
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


	<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,700' rel='stylesheet' type='text/css'>
  
  <!-- Haetaan Onlinevarauksen ihan oma CSS -->
  <link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_onlinevaraus/css/onlinevaraus.css"/>
        

	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/img/favicon.ico">

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

	<?php if(!isset($curpage_controller)): ?>
	  <?php $this->renderPartial('/site/header'); ?>
	<?php endif; ?>

	<?php if(isset($curpage_controller) and $curpage_controller == 'onlinevaraus'): ?>
	<style>
	body{ background : none; }
	</style>
	<?php endif; ?>

	<?php
	foreach(Yii::app()->user->getFlashes() as $key => $message) {
        	echo '<p><div class="container"><div class="alert bg-' . $key . '">' . $message . "</div></div></p>\n";
    	}
	?>


	<?php echo $content; ?>

    <!-- ======================= JQuery libs =========================== -->

    <!-- jQuery local-->
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/js/jquery.js"></script>
    </body>
</html>
