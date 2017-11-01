<?php
	// <-- Change password to bcrypt
	$adm = Administrators::model()->findByPk(Yii::app()->user->id);
	if( isset($adm->id) and strlen($adm->adm_salasana) < 60 )
	{
		$this->redirect(array('change_password'));
		exit;
	}
	// Change password to bcrypt -->


	$asetukset = Asetukset::model()->findByPk(1);
	$domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' ");

$months=array(
	'01'=>Yii::t('main', 'Tammikuu'),
	'02'=>Yii::t('main', 'Helmikuu'),
	'03'=>Yii::t('main', 'Maaliskuu'),
	'04'=>Yii::t('main', 'Huhtikuu'),
	'05'=>Yii::t('main', 'Toukokuu'),
	'06'=>Yii::t('main', 'Kesäkuu'),
	'07'=>Yii::t('main', 'Heinäkuu'),
	'08'=>Yii::t('main', 'Elokuu'),
	'09'=>Yii::t('main', 'Syyskuu'),
	'10'=>Yii::t('main', 'Lokakuu'),
	'11'=>Yii::t('main', 'Marraskuu'),
	'12'=>Yii::t('main', 'Joulukuu'),
	);

?>


      <!-- Begin: Content -->
      <section id="content" class="animated fadeIn">

        <!-- Dashboard Tiles -->
        <div class="row mb10">
          <div class="col-sm-6 col-md-3">
            <div class="panel bg-alert light of-h mb10">
              <div class="pn pl20 p5">
                <div class="icon-bg">
                  <i class="fa fa-calendar"></i>
                </div>
                <h2 class="mt15 lh15">
                  <b><div id="toteututhismonth"></div></b>
                </h2>
                <h5 class="text-muted"><?php echo $months[date("m")].' '.Yii::t('main', 'toteuma'); ?></h5>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="panel bg-info light of-h mb10">
              <div class="pn pl20 p5">
                <div class="icon-bg">
                  <i class="fa fa-clock-o"></i>
                </div>
                <h2 class="mt15 lh15">
                  <b><div id="tehdyttunnittanaan"></div></b>
                </h2>
                <h5 class="text-muted"><?php echo Yii::t('main','Tehdyt tunnit tänään'); ?></h5>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="panel bg-warning light of-h mb10">
              <div class="pn pl20 p5">
                <div class="icon-bg">
                  <i class="fa fa-table"></i>
                </div>
                <h2 class="mt15 lh15">
                  <b><div id="suunnitteltutunnittanaan"></div></b>
                </h2>
                <h5 class="text-muted"><?php echo Yii::t('main', 'Suunniteltu tänään'); ?></h5>
              </div>
            </div>
          </div>

	  <?php
		$vastaamattomat = Viestinta::model()->findAll(" status=0 AND tekija='toimisto' ");
	  ?>
	  <?php if(count($vastaamattomat) > 0) : ?>
	  <script>
	  $( document ).ready(function() {
		$(".pulsar").effect("pulsate", { times:3 }, 7000);
	  });
	  </script>
	  <?php endif; ?>

          <div class="col-sm-6 col-md-3 pulsar">
            <div class="panel bg-danger light of-h mb10">
              <div class="pn pl20 p5">
                <div class="icon-bg">
                  <i class="fa fa-envelope"></i>
                </div>
                <h2 class="mt15 lh15">
                  <b><div id="viestittanaan"></div></b>
                </h2>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/viestinta"><h5 class="text-muted"><?php echo Yii::t('main', 'Lukemattomat viestit'); ?></h5></a>
              </div>
            </div>
          </div>
        </div>

        <!-- Admin-panels -->
        <div class="admin-panels fade-onload">


          <div class="row">

            <div class="col-md-6 col-lg-5 admin-grid">

              <!-- Column Graph -->
              <div class="panel" id="p6">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Työt tänään'); ?></span>
                </div>
                <div class="panel-body pn">
                  <div class="row table-layout">
                    <div class="col-xs-5 va-m">
                      <div id="high-column" style="width: 100%; height: 197px; margin: 0 auto"></div>
                    </div>
                    <div class="col-xs-7 br-l pn">
                      <div class="admin-form">
                        <!-- Panel Break Smart Widget -->
                        <div class="smart-widget sm-right smr-50">
                        </div>
                      </div>

			<div id="tyot_tanaan"></div>

                    </div>
                  </div>
                </div>
              </div>





             <!-- Pie Chart -->
              <div class="panel" id="p10">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Työntekijät tänään'); ?></span>
                </div>
                <div class="panel-body pn">
                  <div id="high-pie" style="width: 100%; height: 200px; margin: 0 auto"></div>
                </div>
              </div>





              <!-- Bar Graph -->
              <div class="panel" id="p12">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Toimipaikat'); ?></span>
		  <?php 
		    $month1 = $months[date("m")];
		    $month2 = $months[date("m",strtotime("-1 month"))];

		    $m1 = date("Y-m-d");
		    $m2 = date("Y-m-d", strtotime($m1.'first day of this month -1 month'));
		  ?>
                  <input type="hidden" id="month1" value="<?php echo $month1; ?>" month="<?php echo $m1; ?>" m="<?php echo date('m'); ?>">
                  <input type="hidden" id="month2" value="<?php echo $month2; ?>" month="<?php echo $m2; ?>" m="<?php echo date('m',strtotime('-1 month')); ?>">
                </div>
                <div class="panel-menu">

                  <div class="chart-legend" data-chart-id="#high-bars">
		    <div id="toimipakat_bars"></div>
                  </div>
                </div>
                <div class="panel-body pn">
                  <div id="high-bars" style="width: 100%; height: 140px; margin: 0 auto"></div>
                </div>
              </div>



              <!-- Country List -->
              <div class="panel" id="p216">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Suunniteltu'); ?></span>
                </div>
                <div class="panel-body panel-scroller scroller-md scroller-overlay pn">
                  <div id="suunniteltulistatanaan"></div>
                </div>
              </div>

            </div>
            <!-- end: .col-md-5-->



            <div class="col-md-6 col-lg-4 admin-grid">


              <!-- Circle Stats -->

              <div class="panel" id="p5">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Tilat tänään'); ?></span>
                </div>
                <div class="panel-body">
                  <div class="mb20 text-right">
                    <span class="fs11 text-muted ml10">
                      <i class="fa fa-circle text-primary fs12 pr5"></i> <?php echo Yii::t('main', 'Työt'); ?></span>
                    <span class="fs11 text-muted ml10">
                      <i class="fa fa-circle text-info fs12 pr5"></i> <?php echo Yii::t('main', 'Matkat'); ?></span>
                    <span class="fs11 text-muted ml10">
                      <i class="fa fa-circle text-warning fs12 pr5"></i> <?php echo Yii::t('main', 'Lounaat'); ?></span>
                  </div>
		  <br><br>
                  <div class="row">
                    <div class="col-xs-4 text-center">
			<?php 
			$tilatTanaan = $this->tilatTanaan();
			$tyot = $tilatTanaan[3];
			$matkat = $tilatTanaan[2];
			$lounaat = $tilatTanaan[10];
			?>
                      <div class="info-circle" id="c1" value="<?php echo (int)$tyot; ?>" data-circle-color="primary"></div>
                    </div>
                    <div class="col-xs-4">
                      <div class="info-circle" id="c2" value="<?php echo (int)$matkat; ?>" data-circle-color="info"></div>
                    </div>
                    <div class="col-xs-4">
                      <div class="info-circle" id="c3" value="<?php echo (int)$lounaat; ?>" data-circle-color="warning"></div>
                    </div>
                  </div>
                </div>
              </div>



              <div class="panel" id="p55">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Myöhästyneet kohteet'); ?></span>
                </div>
                <div class="panel-body panel-scroller scroller-md scroller-overlay pn">

                  <table class="table mbn tc-med-1 tc-bold-last">
                    <thead>
                      <tr class="hidden">
                        <th>#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody id="myohastyneet">
                    </tbody>
                  </table>

                </div>
              </div>



              <div class="panel" id="p56">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Määräajan ylittäneet kohteet'); ?></span>
                </div>
                <div class="panel-body panel-scroller scroller-md scroller-overlay pn">

                  <table class="table mbn tc-med-1 tc-bold-last">
                    <thead>
                      <tr class="hidden">
                        <th>#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody id="ylittaneet">
                    </tbody>
                  </table>

                </div>
              </div>


            </div>
            <!-- end: .col-md-4-->





<script type="text/javascript">
$(document).ready(function(){

var count = 0;
var etusivuAjax = function(){
     if(count < 20) {
          count++;

	console.log('Count: '+count);
        $.ajax({
           url: 'etusivu_ajax',
           type: "POST",
           data: { "suoritus" : "cronin_asiat" , count : count },
           success: function(data){

		try {
			var d = JSON.parse(data);
		} catch (e) {
		        window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error'
		}

		$("#ylittaneet").html(d[0]);
		$("#myohastyneet").html(d[1]);


           },
           error: function(data){
		        window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error'
	   }
        });

     } else {

	  $("#ylittaneet").html('<div class="alert alert-danger">Tiedot ovat vanhetuneet</div>');
	  $("#myohastyneet").html('<div class="alert alert-danger">Tiedot ovat vanhetuneet</div>');
          clearInterval(etusivuAjax);

     }
};

     etusivuAjax();
     setInterval(etusivuAjax, 60000);



});
</script>


            <div class="col-md-6 col-lg-3 admin-grid">


              <!-- Text List -->
	      <?php if($domainit->maksullinen == 0) : ?>

	      <?php
		$sum_laskuri = $this->laskuri(); //tuntien laskuri mobiili + tyovuorot
		$prosentti = 0;
		if( $sum_laskuri > 0 )
		$prosentti = ($sum_laskuri*100)/500;

		$pr_class = 'success';
		if( $prosentti > 100 ){
			$pr_class = 'danger';
			Yii::app()->user->setState('ilmainen', false);
		} elseif( $prosentti < 100 and $prosentti > 90 ){
			$pr_class = 'warning';
			Yii::app()->user->setState('ilmainen', true);
		} else {
			Yii::app()->user->setState('ilmainen', true);
		}
	      ?>

              <div class="panel" id="p_ilmainen">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Ilmainen käyttö'); ?></span>
                </div>
                <div class="panel-body pn">
                  <table class="table mbn tc-list-1 tc-text-muted-2 tc-fw600-2">
                    <thead>
                      <tr class="hidden">
                        <th class="w30">#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody>
		      <tr>
			<td><h3 class="text-primary mn pl5"><?=$sum_laskuri?>h</h3></td>
			<td><h3 class="text-<?=$pr_class?>-dark mn"> <i class="fa fa-caret-up"></i> <?=$prosentti?>% </h3></td>
		      </tr>
                    </tbody>
                  </table>
		<center><?php echo CHtml::link(Yii::t('main', 'Haluan maksullinen'),"maksullinen", array("submit"=>array('maksullinen'), 'confirm' => 'Oletko varma?')); ?></center>
                </div>
              </div>
	      <?php endif; ?>


	      <?php if($this->tasot(5)) : ?>
              <div class="panel" id="p22">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'eDico'); ?></span>
                </div>
                <div class="panel-body pn">
                  <table class="table mbn tc-list-1 tc-text-muted-2 tc-fw600-2">
                    <thead>
                      <tr class="hidden">
                        <th class="w30">#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
	      <?php endif; ?>

              <!-- Text List -->
              <div class="panel" id="p21">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Käyttäjää online'); ?></span>
                </div>
                <div class="panel-body pn">
			<div id="kayttajaonline"></div>
                </div>
              </div>



              <!-- Country List -->
              <div class="panel" id="p16">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Avoimet kohteet'); ?></span>
                </div>
                <div class="panel-body pn">
			<div id="avoimet_kohteet"></div>
                </div>
              </div>


            </div>
            <!-- end: .col-md-3-->


          </div>
          <!-- end: .row -->
	



        </div>

      </section>
      <!-- End: Content -->

      <!-- Begin: Page Footer -->
<!--
      <footer id="content-footer">
        <div class="row">
          <div class="col-md-6">
            <span class="footer-legal">© 2015 AdminDesigns</span>
          </div>
          <div class="col-md-6 text-right">
            <span class="footer-meta">10GB of <b>250GB</b> Free</span>
            <a href="#content" class="footer-return-top">
              <span class="fa fa-arrow-up"></span>
            </a>
          </div>
        </div>
      </footer>


    </section>
    <!-- End: Content-Wrapper -->

    <!-- Start: Right Sidebar -->
<!--
    <aside id="sidebar_right" class="nano affix">


      <div class="sidebar-right-content nano-content">

        <div class="tab-block sidebar-block br-n">
          <ul class="nav nav-tabs tabs-border nav-justified hidden">
            <li class="active">
              <a href="#sidebar-right-tab1" data-toggle="tab">Tab 1</a>
            </li>
            <li>
              <a href="#sidebar-right-tab2" data-toggle="tab">Tab 2</a>
            </li>
            <li>
              <a href="#sidebar-right-tab3" data-toggle="tab">Tab 3</a>

            </li>
          </ul>
          <div class="tab-content br-n">
            <div id="sidebar-right-tab1" class="tab-pane active">

              <h5 class="title-divider text-muted mb20"> Server Statistics
                <span class="pull-right"> 2013
                  <i class="fa fa-caret-down ml5"></i>
                </span>
              </h5>
              <div class="progress mh5">
                <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 44%">
                  <span class="fs11">DB Request</span>
                </div>
              </div>


              <div class="progress mh5">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 84%">
                  <span class="fs11 text-left">Server Load</span>
                </div>
              </div>
              <div class="progress mh5">
                <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 61%">
                  <span class="fs11 text-left">Server Connections</span>
                </div>
              </div>

              <h5 class="title-divider text-muted mt30 mb10">Traffic Margins</h5>
              <div class="row">
                <div class="col-xs-5">
                  <h3 class="text-primary mn pl5">132</h3>
                </div>
                <div class="col-xs-7 text-right">
                  <h3 class="text-success-dark mn">
                    <i class="fa fa-caret-up"></i> 13.2% </h3>
                </div>
              </div>

              <h5 class="title-divider text-muted mt25 mb10">Database Request</h5>
              <div class="row">
                <div class="col-xs-5">
                  <h3 class="text-primary mn pl5">212</h3>
                </div>
                <div class="col-xs-7 text-right">
                  <h3 class="text-success-dark mn">
                    <i class="fa fa-caret-up"></i> 25.6% </h3>
                </div>
              </div>

              <h5 class="title-divider text-muted mt25 mb10">Server Response</h5>
              <div class="row">
                <div class="col-xs-5">
                  <h3 class="text-primary mn pl5">82.5</h3>
                </div>
                <div class="col-xs-7 text-right">
                  <h3 class="text-danger mn">
                    <i class="fa fa-caret-down"></i> 17.9% </h3>
                </div>
              </div>

              <h5 class="title-divider text-muted mt40 mb20"> User Activity
                <span class="pull-right text-primary fw600">1 Hour</span>
              </h5>

              <div class="media">
                <a class="media-left" href="#">
                  <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/img/avatars/6.jpg" class="mw40 br64" alt="holder-img">
                </a>
                <div class="media-body">
                  <h5 class="media-heading">Article
                    <small class="text-muted">- 08/16/22</small>
                  </h5>Updated 36 days ago by
                  <a class="text-system" href="#"> Max </a>
                </div>
              </div>
              <div class="media">
                <a class="media-left" href="#">
                  <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/img/avatars/4.jpg" class="mw40 br64" alt="holder-img">
                </a>
                <div class="media-body">
                  <h5 class="media-heading">Richard
                    <small class="text-muted">@cloudesigns</small>
                    <small class="pull-right text-muted">6h</small>
                  </h5>Updated 36 days ago by
                  <a class="text-system" href="#"> Max </a>
                </div>
              </div>
              <div class="media">
                <a class="media-left" href="#">
                  <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/img/avatars/3.jpg" class="mw40 br64" alt="holder-img">
                </a>
                <div class="media-body">
                  <h5 class="media-heading">1,610 kcal
                    <span class="fa fa-caret-down text-primary pl5"></span>
                  </h5>Updated 36 days ago by
                  <a class="text-system" href="#"> Max </a>
                </div>
              </div>
              <div class="media">
                <a class="media-left" href="#">
                  <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/img/avatars/2.jpg" class="mw40 br64" alt="holder-img">
                </a>
                <div class="media-body">
                  <h5 class="media-heading">1,610 kcal
                    <span class="label label-xs label-system ml5">Featured</span>
                  </h5>Updated 36 days ago by
                  <a class="text-system" href="#"> Max </a>
                </div>
              </div>
              <div class="media">
                <a class="media-left" href="#">
                  <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/img/avatars/5.jpg" class="mw40 br64" alt="holder-img">
                </a>
                <div class="media-body">
                  <h5 class="media-heading">1,610 kcal</h5>
                  Updated ago by
                  <a class="text-system" href="#"> Max </a>
                </div>
                <a class="media-right pl30" href="#">
                  <span class="fa fa-pencil text-muted mb5"></span>
                  <br>
                  <span class="fa fa-remove text-danger-light"></span>
                </a>
              </div>
            </div>
            <div id="sidebar-right-tab2" class="tab-pane"></div>
            <div id="sidebar-right-tab3" class="tab-pane"></div>
          </div>


        </div>

      </div>
    </aside>
     -->

  </div>
  <!-- End: Main -->




  <!-- HighCharts Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/highcharts/highcharts.js"></script>

  <!-- Sparklines Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/sparkline/jquery.sparkline.min.js"></script>

  <!-- Simple Circles Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/circles/circles.js"></script>

  <!-- JvectorMap Plugin + US Map (more maps in plugin/assets folder) -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/jvectormap/jquery.jvectormap.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/jvectormap/assets/jquery-jvectormap-us-lcc-en.js"></script> 

  <!-- Widget Javascript -->
  <?php /* <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/widgets.js"></script> */ ?>

<script>
 $.getScript("<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/etunti_ajax.js", function(){
	$.getScript("<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/widgets.js");
 });
</script>

