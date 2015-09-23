<nav id="topNav" class="navbar" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header" id="etunti-navbar">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    <a class="navbar-brand" rel="home" href="index" title="Etunti">
        <!--<img style="max-width:100px; margin-top: -7px;" src="<?php echo Yii::app()->request->baseUrl; ?>/img/logo-black.png">-->
    </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

	<!--class="active"-->


	<?php if(!isset(Yii::app()->user->adminID) and !Yii::app()->User->isAdmin()) : ?>
        <li><?php echo CHtml::link(Yii::t('main', 'Kirjaudu'),array('/user/login')); ?></li>
	<?php endif; ?>

	<?php if(!isset(Yii::app()->user->adminID) and Yii::app()->User->isAdmin()) : ?>
        <li><?php echo CHtml::link(Yii::t('main', 'Profiili'),array('/user/profile')); ?></li>
        <li><?php echo CHtml::link(Yii::t('main', 'Domainit'),array('/domainit/admin')); ?></li>
        <li><?php echo CHtml::link(Yii::t('main', 'Tasot'),array('/tasot/admin')); ?></li>
        <li><?php echo CHtml::link(Yii::t('main', 'Tietokannat'),array('/domainit/tietokannat')); ?></li>
	<?php endif; ?>


	<?php if(isset(Yii::app()->user->adminID)) : ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Asiakkaat'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Luo asiakas'),array('/asiakkaat/create')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Asiakkaat'),array('/asiakkaat/admin')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Luo kohde'),array('/kohteet/create')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kohteet'),array('/kohteet/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kartta'),array('/kohteet/googlemap')); ?></li>
          </ul>
        </li>


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Tunnit'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Tunnit'),array('/mobile/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Tuntien hyväksyntä'),array('/toteutuneet/index')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Yhteenveto työntekijät'),array('/mobile/yhteenveto')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Yhteenveto kohteet'),array('/mobile/kyhteenveto')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kuukauden tunnit'),array('/toteutuneet/kk')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Raportit tunneista'),array('/mobile/raportit')); ?></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Työntekijät'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Luo työntekijä'),array('/tyontekijat/create')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Työntekijät hallinta'),array('/tyontekijat/admin')); ?></li>
          </ul>
        </li>


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Viestit'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Luo viesti'),array('/viestinta/create')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Viestit hallinta'),array('/viestinta/admin')); ?></li>
          </ul>
        </li>



	<?php if(isset(Yii::app()->user->adminPaketti)) : ?>
	<?php
	 $tas = explode(",",Yii::app()->user->adminPaketti);
	 if(in_array('2',$tas)) : 
	?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Työvuorot'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Työvuorot'),array('/tyovuoroot/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Työvuorot VIIKKO'),array('/tyovuoroot/viikkottain')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Vuosilomat'),array('/vuosilomat/index')); ?></li>
          </ul>
        </li>
	<?php endif; ?>
	<?php endif; ?>


	<?php endif; ?>

      </ul>

      <ul class="nav navbar-nav navbar-right">

	<?php if(isset(Yii::app()->user->adminPaketti)) : ?>
	    <?php 
	    if(isset(Yii::app()->user->domain)) {
     	    $d = Domainit::model()->find("domain='".Yii::app()->user->domain."'");
            echo '<li><a href="#">'.$d->yritys.', '.Yii::t('main', 'Tasot').': '.$d->paketti.'</a></li>';
	    } 
	    ?>
	<?php endif; ?>

	<?php if(isset(Yii::app()->user->adminID)) : ?>	
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Asetukset'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Järjestelmänvalvojat'),array('/administrators/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Valikkoot'),array('/valikkoot/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Mobiili emulattori'),array('/site/mobemu')); ?></li>
          </ul>
        </li>
	<?php endif; ?>

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
