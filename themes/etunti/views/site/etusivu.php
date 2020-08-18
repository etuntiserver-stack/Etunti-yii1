<?php
	$ylittaneetMyohastyneet = $this->ylittaneetMyohastyneet();

	// <-- Change password to bcrypt
	$adm = Administrators::model()->findByPk(Yii::app()->user->id);
	if( isset($adm->id) and strlen($adm->adm_salasana) < 60 )
	{
		$this->redirect(array('change_password'));
		exit;
	}
	// Change password to bcrypt -->

	$asetukset = Asetukset::model()->findByPk(1);

	// <-- Eilen Autohyvaksyminen
	$eilen_hyvaksynta = '';
	if( $asetukset->app_hyvaksynnan_peruste == 2 and time() < strtotime($asetukset->auto_hyvaksynta_klo) ){
		$criteria = new CDbCriteria();
	        $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d') = '".date('Y-m-d', strtotime('-1 day'))."'
			AND hyvaksytty=''
			AND deleted=0
		";
		$hyvaksymattomat_eilen = Mobile::model()->findAll($criteria);
		if( count($hyvaksymattomat_eilen) > 0 ){
			$eilen_hyvaksynta = "<div class='alert alert-default'><marquee>Seuraava automaattinen tuntien hyväksyntä tapahtuu tänään kello ".$asetukset->auto_hyvaksynta_klo ." tunneista, jotka tehty eilen. Kirjattuja tunteja hyväksyntään ".count($hyvaksymattomat_eilen)." kappaletta.</marquee></div>";
		}
	}
	//     Eilen Autohyvaksyminen -->

	// <-- Eilen isot tunnit
	$eilen_ylitetyt_tyot = '';
		$criteria = new CDbCriteria();
		$criteria->order = " 
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) DESC
		";
	       	$criteria->select = "
			TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) as l_tunnit, t.*
		";
	        $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d') = '".date('Y-m-d', strtotime('-1 day'))."'
			AND hyvaksytty=''
			AND deleted=0
			AND TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'))) >= 28800
		";
		$isot = Mobile::model()->findAll($criteria);
		foreach($isot as $item){
			$eilen_ylitetyt_tyot .= '<tr><td class="bg-danger"><b>'.$this->sprint($item->l_tunnit).'</b></td><td>'.$this->etuSukunimi($item->tid).'</td></tr>';
		}
		if(!empty($fl)){
			Yii::app()->user->setFlash('danger', "<p>".$fl."</p>");
		}
	// <-- Eilen isot tunnit

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

          <div class="col-sm-6 col-md-3">
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

	<?=$eilen_hyvaksynta?>

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

	     <?php /* widjets.js on myos suljettu
             <!-- Pie Chart -->
              <div class="panel" id="p10">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Työntekijät tänään'); ?></span>
                </div>
                <div class="panel-body pn">
                  <div id="high-pie" style="width: 100%; height: 200px; margin: 0 auto"></div>
                </div>
              </div>
	     */ ?>

              <!-- Bar Graph -->
              <div class="panel" id="p12">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Toimipaikat'); ?></span>
		  <?php 
		    $month1 = $months[date("m")];
		    $month2 = $months[date("m",strtotime("-1 month"))];
		  ?>
                  <input type="hidden" id="month1" value="<?php echo $month1; ?>" m="<?php echo date('m'); ?>">
                  <input type="hidden" id="month2" value="<?php echo $month2; ?>" m="<?php echo date('m',strtotime('-1 month')); ?>">
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


	<?php /*
              <!-- Country List -->
              <div class="panel" id="p216">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Suunniteltu'); ?></span>
                </div>
                <div class="panel-body panel-scroller scroller-md scroller-overlay pn">
                  <div id="suunniteltulistatanaan"></div>
                </div>
              </div>
	*/ ?>
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
                    <?php echo (isset($ylittaneetMyohastyneet[1]))? $ylittaneetMyohastyneet[1] : ''; ?>
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
                    <?php echo (isset($ylittaneetMyohastyneet[0]))? $ylittaneetMyohastyneet[0] : ''; ?>
                    </tbody>
                  </table>

                </div>
              </div>


            </div>
            <!-- end: .col-md-4-->


            <div class="col-md-6 col-lg-3 admin-grid">


              <!-- Text List -->
	      <?php if($this->laskuri() !== false and isset(Yii::app()->user->ilmainen_kayttotunnit)) : ?>

	      <?php
		$asetuksetForAll = AsetuksetForAll::model()->findbypk(1);
		$sum_laskuri = Yii::app()->user->ilmainen_kayttotunnit; //tuntien laskuri mobiili + tyovuorot
		$prosentti = 0;
		if( $sum_laskuri > 0 )
		$prosentti = ($sum_laskuri*100)/$asetuksetForAll->max_ilmaiset_tunnit;

		$pr_class = 'success';
		if( $prosentti > 100 ){
			$pr_class = 'danger';
		} elseif( $prosentti < 100 and $prosentti > 90 ){
			$pr_class = 'warning';
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
			<td><h3 class="text-primary mn pl5"><?=round($sum_laskuri, 2)?>h</h3></td>
			<td><h3 class="text-<?=$pr_class?>-dark mn"> <i class="fa fa-caret-up"></i> <?=round($prosentti, 2)?>% </h3></td>
		      </tr>
                    </tbody>
                  </table>
		<center><a href="#" data-toggle="modal" data-target="#myModalAloitus">
		<?=Yii::t('main', 'Aloita Laajennettu käyttö')?>
		</a></center>
                </div>
              </div>
	      <?php endif; ?>



<!-- Modal -->
<style>
.modal-dialog-center {
    margin-top: 7%;
    margin-bottom: 5%;
}
.modal_checkbox {
    -webkit-appearance:none;
    width:20px;
    height:20px;
    background:white;
    border-radius:5px;
    border:2px solid #555;
}
.modal_checkbox:checked {
    background: #abd;
}
.modaltxt{
  margin-left: 10px;
  font-size:120%;
}
</style>
<div id="myModalAloitus" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-center">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=Yii::t('main', 'Olet aloittamassa Etunnin laajennetun käytön.')?></h4>
      </div>
      <div class="modal-body" id="body-aloita">
        <p class="small">
<p>Laajennettu Etunti-ohjelma mahdollistaa yli 500 työtunnin suunnittelun ja toteuman. Lisäksi saat kattavamman käyttäjätuen käyttöösi. Sinulla on myös mahdollisuus muokata palvelupakettiasi haluamaasi kokoonpanoon. Tutustu lisäosiin <?php echo CHtml::link('tästä',"https://etunti.fi", array('target' => '_blank')); ?>.</p>
 
<p>Laajennetun version hinta perustuu suunniteltuihin tai toteutuneisiin työtunteihin, riippuen siitä, kumpien yhteenlaskettu summa on suurempi. Työtunti tarkoittaa joko suunniteltua tai leimattua työtuntia. Työtunnit eivät sisällä matkoja eivätkä lounaita. Maksat siis vain työtuntien mukaan. Katso tarkempi hinnasto <?php echo CHtml::link('täältä',array('asetukset/yrityksentiedot', 'id' => 1), array('target' => '_blank')); ?>.</p>
 
<p>Huom! Kun olet ottanut käyttöön maksullisen version, ei sitä voi enää palauttaa ilmaisversioksi.</p>
 
 
<p>Valitse Laajennetun palvelun kokonaisuus tästä:</p>

<div class="row">
 <div class="col-sm-offset-1 col-sm-6">
	<input type="checkbox" name="eTyo" value="eTyo" class="modal_checkbox" checked disabled> 
		<span class="modaltxt">eTyö (Sisältyy)</span>
	<br>
	<input type="checkbox" name="eLasku" class="modal_checkbox val" value="3"> 
		<span class="modaltxt">eLasku</span>
	<br>
	<input type="checkbox" name="eOnline" class="modal_checkbox val" value="4"> 
		<span class="modaltxt">eOnline</span>
	<br>
	<input type="checkbox" name="eDico" class="modal_checkbox val" value="5"> 
		<span class="modaltxt">eDico</span>

 </div>
</div>


	</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="button" class="btn btn-primary aloitan_maksullinen"><?=Yii::t('main', 'Aloita Laajennettu käyttö')?></button>
	<?php echo CHtml::link('Kirjaudu ulos',"/index.php/user/logout",array(
		"class"=>"btn btn-primary hidden",
		"id" => "ulospainike"
	)); ?>
      </div>
    </div>

  </div>
</div>

<script>
$( document ).ready(function() {
  $(".aloitan_maksullinen").click(function(){

    $(".aloitan_maksullinen").text('Odota..');
    var paketti = [];
    $( ".modal_checkbox.val" ).each(function(index) {
	paketti[$( this ).val()] = $( this ).prop('checked');
    });
    //console.log(paketti);
	if(confirm('Olet ottamassa käyttöön Etunnin laajennetun palvelun. Painamalla OK vahvistat tutustuneesi palvelun hinnastoon.'))
	{
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/maksullinen",
	   type:'POST',
	   data: {dat : paketti},
           success: function(data){
		console.log(data);

		$(".aloitan_maksullinen").remove();
		$("#ulospainike").removeClass('hidden');
	
		$("#body-aloita").html('<p>Onneksi olkoon!</p>' +
			'Sinulla on nyt laajennettu Etunti-ohjelma liiketoimintasi tukena.' +
			'<p><b>Kirjaudu ulos ja palaa takaisin.</b></p>'
		);

    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
	}
	return false;
  });
});
</script>
<!-- Modal -->



	      <?php if(!empty($eilen_ylitetyt_tyot)) : ?>
              <div class="panel" id="p23">
                <div class="panel-heading">
                  <span class="panel-title" data-toggle="tooltip" title="Eilen tehdyt työt, joiden kesto on yli 8 tuntia."><?php echo Yii::t('main', 'Ylityöt eilen'); ?></span>
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
			<?=$eilen_ylitetyt_tyot?>
                    </tbody>
                  </table>
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



              <!-- Country List -->
              <div class="panel" id="p16">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Avoimet kohteet'); ?></span>
                </div>
                <div class="panel-body panel-scroller scroller-md scroller-overlay pn">
		 <table class="table mbn tc-med-1 tc-bold-last">
                    <thead>
                      <tr class="hidden">
                        <th>#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody>
		    </tbody>
	        </table>
                </div>
              </div>


            </div>
            <!-- end: .col-md-3-->


          </div>
          <!-- end: .row -->
	
        </div>

      </section>
      <!-- End: Content -->

  </div>
  <!-- End: Main -->

  <!-- HighCharts Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/highcharts/highcharts.js"></script>

  <!-- Sparklines Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/sparkline/jquery.sparkline.min.js"></script>

  <!-- Simple Circles Plugin -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/circles/circles.js"></script>

<?php /*
  <!-- JvectorMap Plugin + US Map (more maps in plugin/assets folder) -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/jvectormap/jquery.jvectormap.min.js"></script>
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/vendor/plugins/jvectormap/assets/jquery-jvectormap-us-lcc-en.js"></script> 

  <!-- Widget Javascript -->
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/widgets.js"></script> 
*/ ?>

<?php /*
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/etunti_ajax.js"></script> 
  <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/widgets.js"></script> 
*/
?>

<script type="text/javascript">
var jsLoaded = false;
setTimeout("callback()", 1000);
function callback() {
    if (!jsLoaded) {
        console.log("Javascript not loaded after 2 seconds!");
    } else {
        $.getScript("<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/widgets.js");
	//etusivuAjax();
    }
}

var etusivuAjax = function(){
        $.ajax({
           url: 'etusivu_ajax',
           type: "POST",
           data: { "suoritus" : "cronin_asiat" },
           success: function(data){
		//console.log('DATA: '+ data);
		try {
			var d = JSON.parse(data);
			$("#ylittaneet").html(d[0]);
			$("#myohastyneet").html(d[1]);
		} catch (e) {
		        window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error'
		}
           },
           error: function(data){
		        window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error'
	   }
        });
};
</script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/etunti_ajax.js" onload="jsLoaded=true"></script>



<?php /*
<script>
 $.getScript("<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/etunti_ajax.js", function(){
	$.getScript("<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/js/demo/widgets.js");
 });
</script>
*/ ?>

