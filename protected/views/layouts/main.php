<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- blueprint CSS framework -->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />-->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />-->
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->





  <!--<link rel="stylesheet" type="text/css" href="css/navbar.css" />-->
  <!--<link rel="stylesheet" href="css/etunti-bootstrap-theme.css">-->

  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
  <link href="css/openSans.css" rel="stylesheet" type="text/css">



  <script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>


<?php
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerPackage('bootstrapJS');
Yii::app()->clientScript->registerPackage('bootstrapCSS');
?>


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>


<div class="container container-fluid" id="page">
	<!--
	<div id="header" class="row">
	<a href="index.php">Sivu</a>
	</div><!-- header -->


	<div id="mainmenu" class="row">
		<?php  $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>Yii::t('main', 'Etusivu'), 'url'=>array('/site/index')),
				array('label'=>Yii::t('main', 'Luetut'), 'url'=>array('/sivexkuitti/index'), 'visible'=>isset(Yii::app()->user->adminID)),
				//array('label'=>Yii::t('main', 'About'), 'url'=>array('/site/page', 'view'=>'about')),
				//array('label'=>Yii::t('main', 'Contact'), 'url'=>array('/site/contact')),
				array('label'=>Yii::t('main', 'Sisään'), 'url'=>array('/user/login'), 'visible'=>!isset(Yii::app()->user->adminID)),
				//array('label'=>Yii::t('main', 'Profile'), 'url'=>array('/user/profile'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>Yii::t('main', 'Ulos'), 'url'=>array('/site/logout'), 'visible'=>isset(Yii::app()->user->adminID))
			),
		)); ?>
	</div>

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?>
	<?php endif?>




<?php
$curpage = Yii::app()->getController()->getAction()->controller->id;
$curpage .= '/'.Yii::app()->getController()->getAction()->controller->action->id;
?>




	<?php echo $content; ?>



<!--
	<div class="clear"></div>

	<div id="footer" style="text-align: left;">

	<div class="col-sm-3">
		WWW.VETEL.FI <br />
		pekka.ylimartimo@vetel.fi <br />
		Puh: +358 414 370 486
	</div>

	<div class="col-sm-3">
		VETEL avoin yhtiö <br />
		Y-tunnus: 26877134<br />
		Puhelin: 041 437 0486
	</div>
	<div class="col-sm-3">
		Copyright &copy; <?php echo date('Y'); ?> by VETEL.FI.<br/>
		All Rights Reserved.
	</div>
	</div><!-- footer -->


</div><!-- page -->
</center>
</body>
</html>
