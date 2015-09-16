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
            <li><?php echo CHtml::link(Yii::t('main', 'Luetut kohteet'),array('/mobile/index')); ?></li>

          </ul>
        </li>
	<?php endif; */ ?>

	<?php if(isset(Yii::app()->user->adminID)) : ?>


        	<li><?php echo CHtml::link(Yii::t('main', 'Mobiili'),array('/mobile/index')); ?></li>


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Toteuma'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Toteutuneet taulukko'),array('/toteutuneet/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Toteutuneet (kk)'),array('/toteutuneet/kk')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Raportit'),array('/mobile/raportit')); ?></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Yhteenvedot'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Työtunnit'),array('/mobile/yhteenveto')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kohteet'),array('/mobile/kyhteenveto')); ?></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Hallinnat'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Työntekijät hallinta'),array('/tyontekijat/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kohteet hallinta'),array('/kohteet/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Mobiili hallinta'),array('/mobile/admin')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Järjestelmänvalvojat'),array('/administrators/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Valikkoot'),array('/valikkoot/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Viestintä hallinta'),array('/viestinta/admin')); ?></li>
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
