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


$curpage = Yii::app()->getController()->getAction()->controller->id;
$curpage .= '/'.Yii::app()->getController()->getAction()->controller->action->id;
echo '<input type="hidden" id="curpage" value="'.$curpage.'">';
?>
  
       
  <!-- Start: Header -->
    <header class="navbar navbar-fixed-top navbar-shadow">

      <div class="navbar-branding">
        <a class="navbar-brand" href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/index">
	<img src="<?php echo Yii::app()->request->baseUrl; ?>/img/logo.png" height="40">
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
            <li>
              <a href="#">
                <span class="mr10"></span> <?php echo strtoupper(Yii::app()->user->domain); ?> </a>
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
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu?lang=en">
                <span class="mr10"></span> English </a>
            </li>
          </ul>
        </li>



        <li class="dropdown menu-merge">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
             <span class="fa fa-eyedropper"></span> 

	  </a>
          <ul class="dropdown-menu pv5 animated animated-short flipInX" role="menu">
            <li>

  <div id="skin-toolbox">
    <div class="panel">
      <div class="panel-heading">

      </div>
      <div class="panel-body pn">

        <div class="text-dark">
          <div class="col-sm-6">
            <form id="toolbox-header-skin">
              <h4 class="mv20"><?php echo Yii::t('main','Header Skins'); ?></h4>
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
          <div class="col-sm-6">
            <form id="toolbox-sidebar-skin">
              <h4 class="mv20"><?php echo Yii::t('main','Sidebar Skins'); ?></h4>
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
        </div>

      </div>
    </div>
  </div>

        <div class="form-group mn br-t p15">
          <a href="#" id="clearLocalStorage" class="btn btn-primary btn-block pb10 pt10">Clear LocalStorage</a>
        </div>

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
              <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/asiakas_tila?id=<?php echo Yii::app()->user->asiakas; ?>" class="animated animated-short fadeInUp">
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
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/index">
              <span class="fa fa-home"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Etusivu'); ?></span>
            </a>
          </li>

              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/vinkkiExtranet/create">
                  <span class="fa fa-paper-plane-o"></span> 
 		  <span class="sidebar-title"><?php echo Yii::t('main', 'Lähetä vinkki'); ?></span>
		</a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/palautteet/create">
                  <span class="fa fa-paper-plane"></span> 
		  <span class="sidebar-title"><?php echo Yii::t('main', 'Lähetä palaute'); ?></span>
		</a>
              </li>

          <li>
            <a class="accordion-toggle asiakkaidenHallinta" href="#">
              <span class="fa fa-user"></span>
              <span class="sidebar-title"><?php echo Yii::t('main', 'Omat asetukset'); ?></span>
              <span class="caret"></span>
            </a>
            <ul class="nav sub-nav">
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/asiakas_tila">
                  <span class="glyphicon glyphicon-home"></span> <?php echo Yii::t('main', 'Minun tiedot'); ?></a>
              </li>
              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/kohteet/asiakas_kohteet">
                  <span class="glyphicon glyphicon-home"></span> <?php echo Yii::t('main', 'Minun kohteet'); ?></a>
              </li>
            </ul>
          </li>


              <li>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asiakkaat/osoitteen_muutos">
                  <span class="fa fa-gear"></span> 
		  <span class="sidebar-title"><?php echo Yii::t('main', 'Osoitteen muutos'); ?></span>
		</a>
              </li>


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
            <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/index" class="metro-tile">
              <span class="metro-icon glyphicon glyphicon-phone"></span>
              <p class="metro-title"><?php echo Yii::t('main', 'TEST'); ?></p>
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




  <script type="text/javascript">
  jQuery(document).ready(function() {

    var curpage = $('#curpage').val();

    if(
	curpage === 'asiakkaat/index'
	|| curpage === 'kohteet/index'
	|| curpage === 'kohteet/avaimet'
	|| curpage === 'kohteet/googlemap'
	|| curpage === 'mobile/laskutettu'
	|| curpage === 'palautteet/index'
    ){  $('.asiakkaidenHallinta').addClass('menu-open'); }
    else if(
	curpage === 'mobile/index' 
	|| curpage === 'toteutuneet/kk'
	|| curpage === 'mobile/palkkataulukko'
	|| curpage === 'mobile/raportit'
    ){  $('.tuntienHallinta').addClass('menu-open'); }
    else if(
	curpage === 'toteutuneet/index' 
	|| curpage === 'toteutuneet/kk'
	|| curpage === 'mobile/lahetys_asiakkaalle'
	|| curpage === 'asiakasHyvaksynta/index'
    ){  $('.tuntienHallinta').addClass('menu-open'); $('.tuntienHyvaksynta').addClass('menu-open'); }
    else if(
	curpage === 'mobile/yhteenveto' 
	|| curpage === 'mobile/kyhteenveto'
	|| curpage === 'mobile/kyhteenveto_tuntemattomat'
	|| curpage === 'mobile/yhteenveto_m'
    ){  $('.tuntienHallinta').addClass('menu-open'); $('.Yhteenvedot').addClass('menu-open'); }
    else if(
	curpage === 'tyontekijat/index' 
	|| curpage === 'tyontekijat/verotustiedot'
	|| curpage === 'tyosuhdet/index'
	|| curpage === 'mobile/raportit'
	|| curpage === 'tyontekijat/merkkipaivat'
	|| curpage === 'kirjallinenVaroitus/index'
	|| curpage === 'tyotodistus/index'
    ){  $('.tyontekijoidenHallinta').addClass('menu-open'); }
    else if(
	curpage === 'viestinta/index' 
    ){  $('.viestinnanHallinta').addClass('menu-open'); }
    else if(
	curpage === 'tyovuoroot/index'
	|| curpage === 'tyovuoroot/tv2'
	|| curpage === 'tyovuoroot/tv_kohteet'
	|| curpage === 'tyovuoroot/viikkottain'
	|| curpage === 'tyovuoroot/kk'
	|| curpage === 'vuosilomat/index'
    ){  $('.tyovuorojenHallinta').addClass('menu-open'); }
    else if(
	curpage === 'lasku/index'
	|| curpage === 'laskutusTuotteet/index'
	|| curpage === 'laskuHistoria/index'
	|| curpage === 'laskuHistoria/reskontraluettelo'
    ){  $('.laskutuksenHallinta').addClass('menu-open'); }
    else if(
	curpage === 'laskuHistoria/paivakirja'
	|| curpage === 'laskuHistoria/paakirja'
	|| curpage === 'laskuHistoria/maksu_paivakirja'
	|| curpage === 'laskuHistoria/maksu_paakirja'
	|| curpage === 'laskuHistoria/alv_raportti'
    ){  $('.laskutuksenHallinta').addClass('menu-open');  $('.raportit').addClass('menu-open'); }
    else if(
	curpage === 'onlinevaraus/kaikki'
	|| curpage === 'onlinevarausTuotteet/index'
    ){  $('.onlinevaraus').addClass('menu-open'); }
    else if(
	curpage === 'yhteystiedot/index'
	|| curpage === 'site/kohderyhma'
	|| curpage === 'crmTarjoukset/index'
	|| curpage === 'crmSopimukset/index'
	|| curpage === 'kirjeidenHallinta/index'
	|| curpage === 'vinkkiExtranet/index'
    ){  $('.crm').addClass('menu-open'); }




  });
  </script>
  <!-- END: PAGE SCRIPTS -->
