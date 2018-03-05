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
  
	<link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/css/style.css?v=2.13"/>
	<link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_classic/css/theme-responsive.css"/>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
  
	<!-- Haetaan Onlinevarauksen ihan oma CSS -->
	<link type="text/css" media="screen" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_onlinevaraus/css/onlinevaraus_ver2.css"/>
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/img/favicon.ico">

	<!-- Firman oma taustekuva -->
	<?php if (isset(Yii::app()->user->domain) and file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain."/ov_tauste.jpg")) : ?>
	<style>
	body {
	  background: url(<?='../../tiedostot/firma/'.Yii::app()->user->domain.'/ov_tauste.jpg'?>) no-repeat center center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;       
	  min-height: 1000px; 
	}
	</style>
	<?php endif; ?>
	<!-- Firman oma taustekuva -->

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
    //Yii::app()->clientScript->registerPackage('bootstrapCSS');
    Yii::app()->clientScript->registerPackage('jquery');
  ?>

	<?php $this->renderPartial('/onlinevaraus/header'); ?>


	<?php
	foreach(Yii::app()->user->getFlashes() as $key => $message) {
        	echo '<p><div class="container"><div class="alert bg-' . $key . '">' . $message . "</div></div></p>\n";
    	}
	?>

	<div class="container" id="paa_container">
	<?php echo $content; ?>
	</div>

	<?php // $this->renderPartial('/onlinevaraus/footer'); ?>



	<footer class="footer online-footer">
	 <div class="container">

	  <div class="form-inline">
	   <a class="form-group" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/onlinevaraus/rekisteriseloste" target="_blank">
		<?php echo Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'); ?> 
	   </a>

	  <div class="form-group">
	  <?php
	   foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/onlinevarausehdot.*')) as $file) 
	   {
		$explNimi = explode("/",$file);
		// <-- file_safe_opener
		$filepath = 'tiedostot/firma/'.Yii::app()->user->domain.'/'.end($explNimi);
		echo '<br>'.CHtml::link(Yii::t('main', 'Onlinevarausehdot'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	
	   }

	   foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/Konevuokraus_toimitusehdot.*')) as $file) 
	   {
		$explNimi = explode("/",$file);
		// <-- file_safe_opener
		$filepath = 'tiedostot/firma/'.Yii::app()->user->domain.'/'.end($explNimi);
		echo '<br>'.CHtml::link(Yii::t('main', 'Konevuokraus toimitusehdot'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	
	   }
	  ?>
	  </div>
	 </div>
	</footer>

    </body>
</html>
