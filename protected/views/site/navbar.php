<?php 
     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);
?>


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
    <?php echo CHtml::link('',array('/site/etusivu'),array('class'=>'navbar-brand','rel'=>'home')); ?>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

	<!--class="active"-->


	<?php if(!isset(Yii::app()->user->adminID) and !Yii::app()->User->isAdmin()) : ?>
        <li><?php echo CHtml::link(Yii::t('main', 'Kirjaudu'),array('/user/login')); ?></li>
	<?php endif; ?>

	<?php if(!isset(Yii::app()->user->adminID) and Yii::app()->User->isAdmin()) : ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Hallinta'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Profiili'),array('/user/profile')); ?></li>
	        <li><?php echo CHtml::link(Yii::t('main', 'Domainit'),array('/domainit/admin')); ?></li>
	        <li><?php echo CHtml::link(Yii::t('main', 'Domaini Seuranta'),array('/domainit/domaini_seuranta')); ?></li>
	        <li><?php echo CHtml::link(Yii::t('main', 'Tasot'),array('/tasot/admin')); ?></li>
	        <li><?php echo CHtml::link(Yii::t('main', 'Tietokannat'),array('/domainit/tietokannat')); ?></li>
	        <li><?php echo CHtml::link(Yii::t('main', 'Asetukset'),array('/AsetuksetForAll/admin')); ?></li>
          </ul>
        </li>
	<?php endif; ?>


	<?php if(isset(Yii::app()->user->adminID)) : ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Asiakkaat'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Asiakkaat'),array('/asiakkaat/admin')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kohteet'),array('/kohteet/admin')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Asiakkaiden hyväksymät tunnit'),array('/asiakasHyvaksynta/index')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kartta'),array('/kohteet/googlemap')); ?></li>


		<?php if(in_array('4',$tas)) : ?>
	        	<li role="separator" class="divider"></li>
	        	<li><?php echo CHtml::link(Yii::t('main', 'CRM'),array('/site/index')); ?></li>
	        	<li><?php echo CHtml::link(Yii::t('main', 'Tarjoukset'),array('/site/index')); ?></li>
	        	<li><?php echo CHtml::link(Yii::t('main', 'Sopimukset'),array('/site/index')); ?></li>
		<?php endif; ?>
	
          </ul>
        </li>


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Tunnit'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Tunnit'),array('/mobile/index')); ?></li>

		<?php if(in_array('2',$tas)) : ?>
        	<li><?php echo CHtml::link(Yii::t('main', 'Tuntien hyväksyntä'),array('/toteutuneet/index')); ?></li>
		<?php endif; ?>

	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Yhteenveto työntekijät'),array('/mobile/yhteenveto')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Yhteenveto kohteet'),array('/mobile/kyhteenveto')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Yhteenveto matkat'),array('/mobile/yhteenveto_m')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Palkkataulukko'),array('/mobile/palkkataulukko')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kuukauden tunnit'),array('/toteutuneet/kk')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Raportit tunneista'),array('/mobile/raportit')); ?></li>
          </ul>
        </li>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Työntekijät'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Työntekijät'),array('/tyontekijat/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Työsuhdelomake'),array('/tyosuhdet/index')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Merkkipäivät'),array('/tyontekijat/merkkipaivat')); ?></li>
          </ul>
        </li>


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Viestintä'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Viestit'),array('/viestinta/admin')); ?></li>
          </ul>
        </li>



	<?php if(in_array('2',$tas)) : ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Työvuorot'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Työvuorot'),array('/tyovuoroot/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Viikkonäkymä'),array('/tyovuoroot/viikkottain')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Kuukausinäkymä'),array('/tyovuoroot/kk')); ?></li>
	        <li role="separator" class="divider"></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Vuosilomat'),array('/vuosilomat/index')); ?></li>
          </ul>
        </li>
	<?php endif; ?>


	<?php if(in_array('3',$tas)) : ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Laskutus'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Laskut'),array('/lasku/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Tuotteet ja palvelut'),array('/LaskutusTuotteet/admin')); ?></li>
          </ul>
        </li>
	<?php endif; ?>


	<?php if(in_array('6',$tas)) : ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', 'Raportointi'); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Työntekijät ja työt'),array('/site/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Alkaneet ja päättyneet kohteet'),array('/site/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Matkaat ja lounaat'),array('/site/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Korjatut tunnit'),array('/site/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Lähetetyt laskut'),array('/site/index')); ?></li>
          </ul>
        </li>
	<?php endif; ?>


	<?php endif; ?>

      </ul>

      <ul class="nav navbar-nav navbar-right">


	<?php if(isset(Yii::app()->user->adminID)) : ?>	
        <li class="dropdown">
          <a href="#" class="dropdown-toggle glyphicon glyphicon-cog" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="caret"></span></a>
          <ul class="dropdown-menu">
        	<li><?php echo CHtml::link(Yii::t('main', 'Asetukset'),array('/asetukset/update',"id"=>"1")); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Järjestelmänvalvojat'),array('/administrators/admin')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Valikkoot'),array('/valikkoot/index')); ?></li>
        	<li><?php echo CHtml::link(Yii::t('main', 'Mobiili emulattori'),array('/site/mobemu')); ?></li>
          </ul>
        </li>
	<?php endif; ?>

        <li class="dropdown">
          <a href="#" class="dropdown-toggle glyphicon glyphicon-user" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo Yii::t('main', ''); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
	    <?php if(isset(Yii::app()->user->adminPaketti)) : ?>
	    <?php 
	    if(isset(Yii::app()->user->domain)) {
     	    $d = Domainit::model()->find("domain='".Yii::app()->user->domain."'");
            echo '<li><a href="#">'.$d->yritys.', '.Yii::t('main', 'Tasot').': '.$d->paketti.'</a></li>';
	    } 
	    ?>
	    <?php endif; ?>
          </ul>
        </li>

        <li><?php echo CHtml::link(Yii::t('main', ''),array('/site/logout') , array("class" =>"glyphicon glyphicon-off")); ?></li>

      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
