<?php 

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);


  $img = Yii::app()->baseUrl.'/img/noname.jpg';
  if(isset(Yii::app()->user->domain) and file_exists('img/admins/'.Yii::app()->user->domain.'/'.Yii::app()->user->id.".jpg") 
	and isset(Yii::app()->user->id))
  {
  $img = Yii::app()->baseUrl.'/img/admins/'.Yii::app()->user->domain.'/'.Yii::app()->user->id.".jpg";
  }

?>
  
  <!-- For Demo Purposes - Theme Settings Pane -->
<!--
  <div id="skin-toolbox">
    <div class="panel">
      <div class="panel-heading">
        <span class="panel-icon">
          <i class="fa fa-gear text-primary"></i>
        </span>
        <span class="panel-title"> Theme Options</span>
      </div>
      <div class="panel-body pn">
        <ul class="nav nav-list nav-list-sm pl15 pt10" role="tablist">
          <li class="active">
            <a href="#toolbox-header" role="tab" data-toggle="tab">Navbar</a>
          </li>
          <li>
            <a href="#toolbox-sidebar" role="tab" data-toggle="tab">Sidebar</a>
          </li>
          <li>
            <a href="#toolbox-settings" role="tab" data-toggle="tab">Misc</a>
          </li>
        </ul>
        <div class="tab-content p20 ptn pb15">
          <div role="tabpanel" class="tab-pane active" id="toolbox-header">
            <form id="toolbox-header-skin">
              <h4 class="mv20">Header Skins</h4>
              <div class="skin-toolbox-swatches">
                <div class="checkbox-custom checkbox-disabled fill mb5">
                  <input type="radio" name="headerSkin" id="headerSkin8" checked value="">
                  <label for="headerSkin8">Light</label>
                </div>
                <div class="checkbox-custom fill checkbox-primary mb5">
                  <input type="radio" name="headerSkin" id="headerSkin1" value="bg-primary">
                  <label for="headerSkin1">Primary</label>
                </div>
                <div class="checkbox-custom fill checkbox-info mb5">
                  <input type="radio" name="headerSkin" id="headerSkin3" value="bg-info">
                  <label for="headerSkin3">Info</label>
                </div>
                <div class="checkbox-custom fill checkbox-warning mb5">
                  <input type="radio" name="headerSkin" id="headerSkin4" value="bg-warning">
                  <label for="headerSkin4">Warning</label>
                </div>
                <div class="checkbox-custom fill checkbox-danger mb5">
                  <input type="radio" name="headerSkin" id="headerSkin5" value="bg-danger">
                  <label for="headerSkin5">Danger</label>
                </div>
                <div class="checkbox-custom fill checkbox-alert mb5">
                  <input type="radio" name="headerSkin" id="headerSkin6" value="bg-alert">
                  <label for="headerSkin6">Alert</label>
                </div>
                <div class="checkbox-custom fill checkbox-system mb5">
                  <input type="radio" name="headerSkin" id="headerSkin7" value="bg-system">
                  <label for="headerSkin7">System</label>
                </div>
                <div class="checkbox-custom fill checkbox-success mb5">
                  <input type="radio" name="headerSkin" id="headerSkin2" value="bg-success">
                  <label for="headerSkin2">Success</label>
                </div>
                <div class="checkbox-custom fill mb5">
                  <input type="radio" name="headerSkin" id="headerSkin9" value="bg-dark">
                  <label for="headerSkin9">Dark</label>
                </div>
              </div>
            </form>
          </div>
          <div role="tabpanel" class="tab-pane" id="toolbox-sidebar">
            <form id="toolbox-sidebar-skin">
              <h4 class="mv20">Sidebar Skins</h4>
              <div class="skin-toolbox-swatches">
                <div class="checkbox-custom fill mb5">
                  <input type="radio" name="sidebarSkin" checked id="sidebarSkin3" value="">
                  <label for="sidebarSkin3">Dark</label>
                </div>
                <div class="checkbox-custom fill checkbox-disabled mb5">
                  <input type="radio" name="sidebarSkin" id="sidebarSkin1" value="sidebar-light">
                  <label for="sidebarSkin1">Light</label>
                </div>
                <div class="checkbox-custom fill checkbox-light mb5">
                  <input type="radio" name="sidebarSkin" id="sidebarSkin2" value="sidebar-light light">
                  <label for="sidebarSkin2">Lighter</label>
                </div>
              </div>
            </form>
          </div>
          <div role="tabpanel" class="tab-pane" id="toolbox-settings">
            <form id="toolbox-settings-misc">
              <h4 class="mv20 mtn">Layout Options</h4>
              <div class="form-group">
                <div class="checkbox-custom fill mb5">
                  <input type="checkbox" checked="" id="header-option">
                  <label for="header-option">Fixed Header</label>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox-custom fill mb5">
                  <input type="checkbox" checked="" id="sidebar-option">
                  <label for="sidebar-option">Fixed Sidebar</label>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox-custom fill mb5">
                  <input type="checkbox" id="breadcrumb-option">
                  <label for="breadcrumb-option">Fixed Breadcrumbs</label>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox-custom fill mb5">
                  <input type="checkbox" id="breadcrumb-hidden">
                  <label for="breadcrumb-hidden">Hide Breadcrumbs</label>
                </div>
              </div>
              <h4 class="mv20">Layout Options</h4>
              <div class="form-group">
                <div class="radio-custom mb5">
                  <input type="radio" id="fullwidth-option" checked name="layout-option">
                  <label for="fullwidth-option">Fullwidth Layout</label>
                </div>
              </div>
              <div class="form-group mb20">
                <div class="radio-custom radio-disabled mb5">
                  <input type="radio" id="boxed-option" name="layout-option" disabled>
                  <label for="boxed-option">Boxed Layout
                    <b class="text-muted">(Coming Soon)</b>
                  </label>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="form-group mn br-t p15">
          <a href="#" id="clearLocalStorage" class="btn btn-primary btn-block pb10 pt10">Clear LocalStorage</a>
        </div>
      </div>
    </div>
  </div>
-->
  <!-- End: Theme Settings Pane -->


       
  <!-- Start: Header -->
    <header class="navbar navbar-fixed-top navbar-shadow">

      <div class="navbar-branding">
        <a class="navbar-brand" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php?site/index">
	<img src="/../img/logo.png" height="40">
        </a>
        <span id="toggle_sidemenu_l" class="ad ad-lines"></span>
      </div>
      <ul class="nav navbar-nav navbar-left">
        <li>
          <a class="sidebar-menu-toggle hidden" href="#">
            <span class="ad ad-ruby fs18"></span>
          </a>
        </li>
        <li>
          <a class="topbar-menu-toggle" href="#">
            <span class="ad ad-wand fs16"></span>
          </a>
        </li>
        <li class="hidden-xs">
          <a class="request-fullscreen toggle-active" href="#">
            <span class="ad ad-screen-full fs18"></span>
          </a>
        </li>
      </ul>
<!--
      <form class="navbar-form navbar-left navbar-search" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Haku...">
        </div>
      </form>
-->
      <ul class="nav navbar-nav navbar-right">

        <li class="dropdown menu-merge">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
             <span class="">Tausteet</span> 

	  </a>
          <ul class="dropdown-menu pv5 animated animated-short flipInX" role="menu">
            <li>

  <div id="skin-toolbox">
    <div class="panel">
      <div class="panel-heading">

	<a href="#" id="clearLocalStorage" class="btn btn-primary">Clear LocalStorage</a>

      </div>
      <div class="panel-body pn myBgColors">

        <div class="">
          <div class="col-sm-4">
            <form id="toolbox-header-skin">
              <h4 class="mv20">Header Skins</h4>
              <div class="skin-toolbox-swatches">
                <div class="checkbox-custom checkbox-disabled fill mb5">
                  <input type="radio" name="headerSkin" id="headerSkin8" checked value="">
                  <label for="headerSkin8">Light</label>
                </div>
                <div class="checkbox-custom fill checkbox-primary mb5">
                  <input type="radio" name="headerSkin" id="headerSkin1" value="bg-primary">
                  <label for="headerSkin1">Primary</label>
                </div>
                <div class="checkbox-custom fill checkbox-info mb5">
                  <input type="radio" name="headerSkin" id="headerSkin3" value="bg-info">
                  <label for="headerSkin3">Info</label>
                </div>
                <div class="checkbox-custom fill checkbox-warning mb5">
                  <input type="radio" name="headerSkin" id="headerSkin4" value="bg-warning">
                  <label for="headerSkin4">Warning</label>
                </div>
                <div class="checkbox-custom fill checkbox-danger mb5">
                  <input type="radio" name="headerSkin" id="headerSkin5" value="bg-danger">
                  <label for="headerSkin5">Danger</label>
                </div>
                <div class="checkbox-custom fill checkbox-alert mb5">
                  <input type="radio" name="headerSkin" id="headerSkin6" value="bg-alert">
                  <label for="headerSkin6">Alert</label>
                </div>
                <div class="checkbox-custom fill checkbox-system mb5">
                  <input type="radio" name="headerSkin" id="headerSkin7" value="bg-system">
                  <label for="headerSkin7">System</label>
                </div>
                <div class="checkbox-custom fill checkbox-success mb5">
                  <input type="radio" name="headerSkin" id="headerSkin2" value="bg-success">
                  <label for="headerSkin2">Success</label>
                </div>
                <div class="checkbox-custom fill mb5">
                  <input type="radio" name="headerSkin" id="headerSkin9" value="bg-dark">
                  <label for="headerSkin9">Dark</label>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-4">
            <form id="toolbox-sidebar-skin">
              <h4 class="mv20">Sidebar Skins</h4>
              <div class="skin-toolbox-swatches">
                <div class="checkbox-custom fill mb5">
                  <input type="radio" name="sidebarSkin" checked id="sidebarSkin3" value="">
                  <label for="sidebarSkin3">Dark</label>
                </div>
                <div class="checkbox-custom fill checkbox-disabled mb5">
                  <input type="radio" name="sidebarSkin" id="sidebarSkin1" value="sidebar-light">
                  <label for="sidebarSkin1">Light</label>
                </div>
                <div class="checkbox-custom fill checkbox-light mb5">
                  <input type="radio" name="sidebarSkin" id="sidebarSkin2" value="sidebar-light light">
                  <label for="sidebarSkin2">Lighter</label>
                </div>
              </div>
            </form>
          </div>
          <div class="col-sm-4">
            <form id="toolbox-settings-misc">
              <h4 class="mv20 mtn">Layout Options</h4>
              <div class="form-group">
                <div class="checkbox-custom fill mb5">
                  <input type="checkbox" checked="" id="header-option">
                  <label for="header-option">Fixed Header</label>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox-custom fill mb5">
                  <input type="checkbox" checked="" id="sidebar-option">
                  <label for="sidebar-option">Fixed Sidebar</label>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox-custom fill mb5">
                  <input type="checkbox" id="breadcrumb-option">
                  <label for="breadcrumb-option">Fixed Breadcrumbs</label>
                </div>
              </div>
              <div class="form-group">
                <div class="checkbox-custom fill mb5">
                  <input type="checkbox" id="breadcrumb-hidden">
                  <label for="breadcrumb-hidden">Hide Breadcrumbs</label>
                </div>
              </div>
              <h4 class="mv20">Layout Options</h4>
              <div class="form-group">
                <div class="radio-custom mb5">
                  <input type="radio" id="fullwidth-option" checked name="layout-option">
                  <label for="fullwidth-option">Fullwidth Layout</label>
                </div>
              </div>
              <div class="form-group mb20">
                <div class="radio-custom radio-disabled mb5">
                  <input type="radio" id="boxed-option" name="layout-option" disabled>
                  <label for="boxed-option">Boxed Layout
                    <b class="text-muted">(Coming Soon)</b>
                  </label>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>



	</li>
      </ul>
     </li>



        <li class="dropdown menu-merge">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <span class="ad ad-radio-tower fs18" id="notiFyClick"></span>
          </a>
          <ul class="dropdown-menu media-list w350 animated animated-shorter fadeIn" role="menu">
            <li class="dropdown-header">
              <span class="dropdown-title"> Notifications</span>
              <span class="label label-warning">5</span>
            </li>
	    <get id="viestiGet"></get>
	    	
          </ul>
        </li>
        <li class="dropdown menu-merge">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
             <span class=""></span> 
		<?php
		$lang = 'fi';
		if(isset($_SESSION['lang']) and !empty($_SESSION['lang']))
  		$lang = $_SESSION['lang'];
		echo strtoupper ($lang);
		?>
          </a>
          <ul class="dropdown-menu pv5 animated animated-short flipInX" role="menu">
            <li>
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu?lang=fi">
                <span class="mr10"></span> Suomi </a>
            </li>
            <li>
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu?lang=ee">
                <span class="mr10"></span> Eesti </a>
            </li>
            <li>
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu?lang=en">
                <span class="mr10"></span> English </a>
            </li>
          </ul>
        </li>


        <li class="menu-divider hidden-xs">
          <i class="fa fa-circle"></i>
        </li>
        <li class="dropdown menu-merge">
          <a href="#" class="dropdown-toggle " data-toggle="dropdown"> 
		 <?php echo Yii::t('main','Asetukset'); ?>
            <span class="caret caret-tp hidden-xs"></span>
          </a>
          <ul class="dropdown-menu list-group dropdown-persist w250" role="menu">
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/update?id=1" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Asetukset'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/administrators/index" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Järjestelmänvalvojat'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/ohjesivu" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Ohjeet'); ?> </a>
            </li>
          </ul>
        </li>
        <li id="toggle_sidemenu_t">  
        		<span class="fa fa-caret-up"></span>
        </li>

        <li class="menu-divider hidden-xs">
          <i class="fa fa-circle"></i>
        </li>
        <li class="dropdown menu-merge">
          <a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown"> <img src="<?php echo $img; ?>" alt="avatar" class="mw30 br64 mr15"> <?php if(isset(Yii::app()->user->nimi)) echo Yii::app()->user->nimi; ?>
            <span class="caret caret-tp hidden-xs"></span>
          </a>
          <ul class="dropdown-menu list-group dropdown-persist w250" role="menu">
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/administrators/update?id=<?php echo Yii::app()->user->id; ?>" class="animated animated-short fadeInUp">
                <span class="fa fa-gear"></span> <?php echo Yii::t('main','Omat asetukset'); ?> </a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/user/logout" class="animated animated-short fadeInUp">
                <span class="fa fa-power-off"></span> <?php echo Yii::t('main','Kirjaudu ulos'); ?> </a>
            </li>
          </ul>
        </li>
        <li id="toggle_sidemenu_t">  
        		<span class="fa fa-caret-up"></span>
        </li>



      </ul>

    </header>
    <!-- End: Header -->

    <!-- Start: Sidebar -->
    <aside id="sidebar_left" class="">

      <!-- Start: Sidebar Left Content -->
      <div class="sidebar-left-content nano-content">

        <!-- Start: Sidebar Menu -->
        <ul class="nav sidebar-menu">

          <li>
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu">
              <span class="fa fa-home"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Etusivu'); ?></span>
            </a>
          </li>



          <li>
            <a class="accordion-toggle" href="#">
              <span class="fa fa-user"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Asiakkaat'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/index">
                  <span class="glyphicon glyphicon-home"></span> <?php echo Yii::t('main', 'Asiakkaat'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kohteet/index">
                  <span class="glyphicon glyphicon-home"></span> <?php echo Yii::t('main', 'Kohteet'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kohteet/avaimet">
                  <span class="fa fa-key"></span> <?php echo Yii::t('main', 'Avaimet'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kohteet/googlemap">
                  <span class="fa fa-map"></span> <?php echo Yii::t('main', 'Kartta'); ?></a>
              </li>
	      <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/laskutettu">
                  <span class="glyphicon glyphicon-phone"></span> <?php echo Yii::t('main', 'Laskutettavat kohteet'); ?></a>
              </li>

            </ul>
          </li>



          <li>
            <a class="accordion-toggle" href="#">
              <span class="fa fa-mobile"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Tunnit'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/index">
                  <span class="glyphicon glyphicon-phone"></span> <?php echo Yii::t('main', 'Tunnit'); ?></a>
              </li>


          <li>
            <a class="accordion-toggle" href="#">
              <span class="fa fa-bars"></span>
              <span><?php echo Yii::t('main', 'Tuntien hyväksyntä'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/toteutuneet/index">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntien hyväksyntä'); ?></a>
              </li>       
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/kyhteenveto">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Lähetä hyväksyttäväksi'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakasHyvaksynta/index">
                  <span class="glyphicon glyphicon-ok"></span> <?php echo Yii::t('main', 'Asiakkaiden hyväksymät tunnit'); ?></a>
              </li>
            </ul>
          </li>

              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/toteutuneet/kk">
                  <span class="fa fa-calendar-check-o"></span> <?php echo Yii::t('main', 'Tuntien toteuma'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/palkkataulukko">
                  <span class="fa fa-eur"></span> <?php echo Yii::t('main', 'Tiedot palkanlaskentaan'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/raportit">
                  <span class="fa fa-th-list"></span> <?php echo Yii::t('main', 'Raportit'); ?></a>
              </li>

          <li>
            <a class="accordion-toggle" href="#">
              <span class="fa fa-bars"></span>
              <span><?php echo Yii::t('main', 'Yhteenvedot'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/yhteenveto">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto työntekijät'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/kyhteenveto">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto kohteet'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/kyhteenveto_tuntemattomat">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto kohteet tuntemattomat'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/yhteenveto_m">
                  <span class="glyphicon glyphicon-time"></span> <?php echo Yii::t('main', 'Tuntiyhteenveto matkat'); ?></a>
              </li>
            </ul>
          </li>

            </ul>
          </li>



          <li>
            <a class="accordion-toggle" href="#">
              <span class="fa fa-male"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Työntekijät'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/index">
                  <span class="fa fa-male"></span> <?php echo Yii::t('main', 'Työntekijät'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyosuhdet/index">
                  <span class="fa fa-list"></span> <?php echo Yii::t('main', 'Työsuhdelomake'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/merkkipaivat">
                  <span class="fa fa-indent"></span> <?php echo Yii::t('main', 'Merkkipäivät'); ?></a>
              </li>
            </ul>
          </li>



          <li>
            <a class="accordion-toggle" href="#">
              <span class="fa fa-envelope"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Viestintä'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/viestinta/index">
                  <span class="glyphicon glyphicon-envelope"></span> <?php echo Yii::t('main', 'Viestit'); ?></a>
              </li>
            </ul>
          </li>

	<?php if(in_array('2',$tas)) : ?>
          <li>
            <a class="accordion-toggle" href="#">
              <span class="fa fa-clock-o"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Työvuorot'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/index" target="_blank">
                  <span class="fa fa-clock-o"></span> <?php echo Yii::t('main', 'Työvuorot'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/viikkottain">
                  <span class="fa fa-paper-plane"></span> <?php echo Yii::t('main', 'Työvuorojen lähetys'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyovuoroot/kk">
                  <span class="fa fa-calendar-o"></span> <?php echo Yii::t('main', 'Kuukausinäkymä'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/vuosilomat/index">
                  <span class="fa fa-table"></span> <?php echo Yii::t('main', 'Vuosilomat'); ?></a>
              </li>
            </ul>
          </li>
	<?php endif; ?>

	<?php if(in_array('3',$tas)) : ?>
          <li>
            <a class="accordion-toggle" href="#">
              <span class="glyphicon glyphicon-barcode"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Laskutus'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/lasku/index">
                  <span class="glyphicon glyphicon-barcode"></span> <?php echo Yii::t('main', 'Laskut'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/LaskutusTuotteet/admin">
                  <span class="fa fa-shopping-cart"></span> <?php echo Yii::t('main', 'Tuotteet ja palvelut'); ?></a>
              </li>
            </ul>
          </li>
	<?php endif; ?>


            </ul>
          </li>

        </ul>
        <!-- End: Sidebar Menu -->

      </div>
      <!-- End: Sidebar Left Content -->

    </aside>

    <!-- Start: Content-Wrapper -->
    <section id="content_wrapper">

      <!-- Start: Topbar-Dropdown -->
      <div id="topbar-dropmenu">
        <div class="topbar-menu row">
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-phone"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'TUNNIT'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/index" class="metro-tile">
              <span class="metro-icon fa fa-male"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'TYÖNTEKIJÄT'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">

            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-user"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'ASIAKKAAT'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kohteet/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-home"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'KOHTEET'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/viestinta/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-envelope"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'VIESTIT'); ?></p>
            </a>
          </div>
          <div class="col-xs-4 col-sm-2">
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/update?id=1" class="metro-tile">
              <span class="metro-icon fa fa-gears"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'ASETUKSET'); ?></p>
            </a>
          </div>
        </div>
      </div>
      <!-- End: Topbar-Dropdown -->

      <!-- Start: Topbar -->
      <header id="topbar" class="hidden">
        <div class="topbar-left">
          <ol class="breadcrumb">
            <li class="crumb-active">
              <a href="dashboard.html">Dashboard</a>
            </li>
            <li class="crumb-icon">
              <a href="dashboard.html">
                <span class="glyphicon glyphicon-home"></span>
              </a>
            </li>
            <li class="crumb-link">
              <a href="dashboard.html">Home</a>
            </li>
            <li class="crumb-trail">Dashboard</li>
          </ol>
        </div>
        <div class="topbar-right">
          <div class="ib topbar-dropdown">
            <label for="topbar-multiple" class="control-label pr10 fs11 text-muted">Reporting Period</label>
            <select id="topbar-multiple" class="hidden">
              <optgroup label="Filter By:">
                <option value="1-1">Last 30 Days</option>
                <option value="1-2" selected="selected">Last 60 Days</option>
                <option value="1-3">Last Year</option>
              </optgroup>
            </select>
          </div>
          <div class="ml15 ib va-m" id="toggle_sidemenu_r">
            <a href="#" class="pl5">
              <i class="fa fa-sign-in fs22 text-primary"></i>
              <span class="badge badge-hero badge-danger">3</span>
            </a>
          </div>
        </div>
      </header>
      <!-- End: Topbar -->



  <!-- BEGIN: PAGE SCRIPTS -->

  <!-- jQuery -->


  <!-- HighCharts Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/highcharts/highcharts.js"></script>

  <!-- Sparklines Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/sparkline/jquery.sparkline.min.js"></script>

  <!-- Simple Circles Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/circles/circles.js"></script>

  <!-- JvectorMap Plugin + US Map (more maps in plugin/assets folder) -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/jvectormap/jquery.jvectormap.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/jvectormap/assets/jquery-jvectormap-us-lcc-en.js"></script> 

  <!-- Theme Javascript -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/utility/utility.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/demo.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/main.js"></script>

  <!-- Widget Javascript -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/widgets.js"></script>
  <script type="text/javascript">
  jQuery(document).ready(function() {

    "use strict";

    // Init Theme Core      
    Core.init();

    // Init Demo JS
    Demo.init();

    // Init Widget Demo JS
    // demoHighCharts.init();

    // Because we are using Admin Panels we use the OnFinish 
    // callback to activate the demoWidgets. It's smoother if
    // we let the panels be moved and organized before 
    // filling them with content from various plugins

    // Init plugins used on this page
    // HighCharts, JvectorMap, Admin Panels

    // Init Admin Panels on widgets inside the ".admin-panels" container
    $('.admin-panels').adminpanel({
      grid: '.admin-grid',
      draggable: true,
      preserveGrid: true,
      mobile: false,
      onStart: function() {
        // Do something before AdminPanels runs
      },
      onFinish: function() {
        $('.admin-panels').addClass('animated fadeIn').removeClass('fade-onload');

        // Init the rest of the plugins now that the panels
        // have had a chance to be moved and organized.
        // It's less taxing to organize empty panels
        demoHighCharts.init();
        runVectorMaps(); // function below
      },
      onSave: function() {
        $(window).trigger('resize');
      }
    });

    // Widget VectorMap
    function runVectorMaps() {

      // Jvector Map Plugin
      var runJvectorMap = function() {
        // Data set

        var mapData = [900, 700, 350, 500];
        // Init Jvector Map

        $('#WidgetMap').vectorMap({
          map: 'us_lcc_en',
          //regionsSelectable: true,
          backgroundColor: 'transparent',
          series: {
            markers: [{
              attribute: 'r',
              scale: [3, 7],
              values: mapData
            }]
          },
          regionStyle: {
            initial: {
              fill: '#E5E5E5'
            },
            hover: {
              "fill-opacity": 0.3
            }
          },
          markers: [{
            latLng: [37.78, -122.41],
            name: 'San Francisco,CA'
          }, {
            latLng: [36.73, -103.98],
            name: 'Texas,TX'
          }, {
            latLng: [38.62, -90.19],
            name: 'St. Louis,MO'
          }, {
            latLng: [40.67, -73.94],
            name: 'New York City,NY'
          }],
          markerStyle: {
            initial: {
              fill: '#a288d5',
              stroke: '#b49ae0',
              "fill-opacity": 1,
              "stroke-width": 10,
              "stroke-opacity": 0.3,
              r: 3
            },
            hover: {
              stroke: 'black',
              "stroke-width": 2
            },
            selected: {
              fill: 'blue'
            },
            selectedHover: {}
          },
        });
        // Manual code to alter the Vector map plugin to 
        // allow for individual coloring of countries
        var states = ['US-CA', 'US-TX', 'US-MO',
          'US-NY'
        ];
        var colors = [bgWarningLr, bgPrimaryLr, bgInfoLr, bgAlertLr];
        var colors2 = [bgWarning, bgPrimary, bgInfo, bgAlert];
        $.each(states, function(i, e) {
          $("#WidgetMap path[data-code=" + e + "]").css({
            fill: colors[i]
          });
        });
        $('#WidgetMap').find('.jvectormap-marker')
          .each(function(i, e) {
            $(e).css({
              fill: colors2[i],
              stroke: colors2[i]
            });
          });
      }

      if ($('#WidgetMap').length) {
        runJvectorMap();
      }
    }



  });
  </script>

  <!-- END: PAGE SCRIPTS -->
