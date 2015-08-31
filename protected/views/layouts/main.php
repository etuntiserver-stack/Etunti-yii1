<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1">


  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
  <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/openSans.css" rel="stylesheet" type="text/css">



  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.11.2.min.js"></script>


<?php 
Yii::app()->db->createCommand("SET NAMES utf8")->execute();
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerPackage('bootstrapJS');
Yii::app()->clientScript->registerPackage('bootstrapCSS');
?>




	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>



<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    <a class="navbar-brand" rel="home" href="index" title="Etunti">
        <img style="max-width:100px; margin-top: -7px;" src="<?php echo Yii::app()->request->baseUrl; ?>/img/logo-black.png">
    </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

	<!--class="active"-->
        <li><?php echo CHtml::link(Yii::t('main', 'Etusivu'),array('/site/index')); ?></li>

	<?php if(!isset(Yii::app()->user->adminID) and !Yii::app()->User->isAdmin()) : ?>
        <li><?php echo CHtml::link(Yii::t('main', 'Kirjaudu'),array('/user/login')); ?></li>
	<?php endif; ?>

	<?php if(!isset(Yii::app()->user->adminID) and Yii::app()->User->isAdmin()) : ?>
        <li><?php echo CHtml::link(Yii::t('main', 'Profiili'),array('/user/profile')); ?></li>
        <li><?php echo CHtml::link(Yii::t('main', 'Domainit'),array('/domainit/admin')); ?></li>
        <li><?php echo CHtml::link(Yii::t('main', 'Tasot'),array('/tasot/admin')); ?></li>
	<?php endif; ?>

	<?php /* if(isset(Yii::app()->user->adminID)) : ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Mobiili'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><?php echo CHtml::link(Yii::t('main', 'Luetut kohteet'),array('/sivexkuitti/index')); ?></li>

          </ul>
        </li>
	<?php endif; */ ?>

	<?php if(isset(Yii::app()->user->adminID)) : ?>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Mobiili'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Mobiili lista'),array('/sivexkuitti/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Mobiili hallinta'),array('/sivexkuitti/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Yhteenveto tunnit'),array('/sivexkuitti/yhteenveto')); ?></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Toteuma'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Toteutuneet taulukko'),array('/toteutuneet/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Toteutuneet hallinta'),array('/toteutuneet/admin')); ?></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Työntekijät'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Työntekijät lista'),array('/tyontekijat/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Työntekijät hallinta'),array('/tyontekijat/admin')); ?></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Kohteet'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Kohteet hallinta'),array('/kohteet/admin')); ?></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Hallinnat'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        <li><?php echo CHtml::link(Yii::t('main', 'Järjestelmänvalvojat'),array('/administrators/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Valikkoot'),array('/valikkoot/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Mobiili emulattori'),array('/site/mobemu')); ?></li>
          </ul>
        </li>

	<?php
	 $tas = explode(",",Yii::app()->user->adminPaketti);
	 if(in_array('2',$tas)) : 
	?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'TASO 2'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Työvuoroon taulukko'),array('/tyovuoroot/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Työvuoroon hallinta'),array('/tyovuoroot/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Vuosilomat taulukko'),array('/vuosilomat/index')); ?></li>
          </ul>
        </li>
	<?php endif; ?>

	<?php endif; ?>

      </ul>

      <ul class="nav navbar-nav navbar-right">

	    <?php 
	    if(isset(Yii::app()->user->domain)) {
     	    $d = Domainit::model()->find("domain='".Yii::app()->user->domain."'");
            echo '<li><a href="#">'.$d->yritys.', '.Yii::t('main', 'Tasot').': '.$d->paketti.'</a></li>';
	    } 
	    ?>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Käyttäjä'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">

            <li role="separator" class="divider"></li>
            <li><a href="#"><?php echo CHtml::link(Yii::t('main', 'Ulos'),array('/site/logout')); ?></a></li>
          </ul>
        </li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container-fluid">
	<!--
	<div id="header" class="row">
	<a href="index.php">Sivu</a>
	</div><!-- header -->

<!--
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
-->

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




</div><!-- page -->
</center>
</body>
</html>
