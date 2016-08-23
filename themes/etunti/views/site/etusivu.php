<?php

$criteria=new CDbCriteria;
$criteria->condition = " 
	DATE(time) < (DATE_SUB(CURDATE(), INTERVAL 2 DAY)) 
	AND tila=0
";
$vinkki = VinkkiExtranet::model()->find($criteria);
if(isset($vinkki->id))
echo '<p><div class="alert alert-danger">'.Yii::t('main', 'Käsittelemättömiä vinkkejä').' ID: '.$vinkki->id.'</div></p>';

// <-- Backup
if (!file_exists(Yii::app()->basePath."/../backup/".Yii::app()->user->domain.'/'.date("Y-m-d").'_'.Yii::app()->user->domain.'.sql.gz'))
{

   if (!file_exists(Yii::app()->basePath."/../backup/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../backup/".Yii::app()->user->domain, 0777, true);
  }


  	if( $_SERVER['REMOTE_ADDR'] == '::1' or $_SERVER['REMOTE_ADDR'] == '127.0.0.1' )
  	{
	
  	} else {

  	    exec("/usr/bin/mysqldump -u mulgikapsas -pKristinA1 ".Yii::app()->user->domain." | gzip -c > backup/".Yii::app()->user->domain."/".date("Y-m-d")."_".Yii::app()->user->domain.".sql.gz");


      	    echo '<span id="uusiVarmuskopioText">uusi varmuskopio on tehty</span>';

   	    foreach(array_reverse(glob(Yii::app()->baseUrl.'backup/'.Yii::app()->user->domain.'/*')) as $file) 
	    {
		$explNimi = explode("/",$file);
		$explNimi2 = explode("_",end($explNimi));
		if($explNimi2[0] < date("Y-m-d", strtotime("-7 day")))
		{
			//echo $explNimi2[0].' '.date("Y-m-d", strtotime("-7 day")).'<br>';
			unlink(Yii::app()->basePath.'/../backup/'.Yii::app()->user->domain.'/'.end($explNimi));
		}
   	    }

  	}

}
// Backup -->


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
                  <b>	<?php 
			$kktunnint = $this->toteutuThisMonth(date("Ym"));
			echo $this->sprint($kktunnint); 
			?>
		  </b>
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
		<?php
		$criteria = new CDbCriteria;
		$criteria->select="
			SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit
		";	
		$criteria->condition=" 
			DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=3
		";
		$a = Mobile::model()->find($criteria);
		  $tehdyht = '00:00';
		if(isset($a->l_tunnit) and $a->l_tunnit > 0)
		  $tehdyht = $this->sprint($a->l_tunnit);

                  echo '<b>'.$tehdyht.'</b>';
		?>
                </h2>
                <h5 class="text-muted"><?php echo Yii::t('main','Tehdyt tunnit tänään'); ?></h5>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="panel bg-danger light of-h mb10">
              <div class="pn pl20 p5">
                <div class="icon-bg">
                  <i class="fa fa-table"></i>
                </div>
		<?php

		$site = Yii::app()->createController('Site');
		$eilasketa = $site[0]->eiLasketa();

		$criteria = new CDbCriteria();
        	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
        	$criteria->condition = " 
			loppu!='' and alku!='' 
			AND $eilasketa
			AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
		";
	  	$su = Tyovuoroot::model()->find($criteria);
		  $suunniteltu = '00:00';
		if(isset($su->l_tunnit) and $su->l_tunnit > 0)
		  $suunniteltu = $this->sprint($su->l_tunnit);
		?>

                <h2 class="mt15 lh15">
                  <b><?php echo $suunniteltu; ?></b>
                </h2>
                <h5 class="text-muted"><?php echo Yii::t('main', 'Suunniteltu tänään'); ?></h5>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="panel bg-warning light of-h mb10">
              <div class="pn pl20 p5">
                <div class="icon-bg">
                  <i class="fa fa-envelope"></i>
                </div>
		<?php
		$criteria = new CDbCriteria();
        	$criteria->select = " COUNT(*) as count ";
        	$criteria->condition = " 
			DATE(time) = CURDATE()
			AND tekija='toimisto'
		";
	  	$v = Viestinta::model()->find($criteria);
		  $viestit = '0';
		if(isset($v->count) and $v->count > 0)
		  $viestit = (int)$v->count;
		?>
                <h2 class="mt15 lh15">
                  <b><?php echo $viestit; ?></b>
                </h2>
                <h5 class="text-muted"><?php echo Yii::t('main', 'Viestit tänään'); ?></h5>
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


<?php 
	$crsun = new CDbCriteria();
	$crsun->select = "  COUNT(*) as count ";
	$crsun->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
		AND kohde!=''
		AND tyoajanmerkinta NOT LIKE '%Ei lasketa%'
	";
	$s = Tyovuoroot::model()->find($crsun);

	$crsun = new CDbCriteria();
	$crsun->select = "  COUNT(*) as count ";
	$crsun->condition = " status=1	";
	$a = Mobile::model()->find($crsun);	

	$crsun = new CDbCriteria();
	$crsun->select = "  COUNT(*) as count ";
	$crsun->condition = " 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE() and status=3
	";
	$t = Mobile::model()->find($crsun);


	$ss = 0;
	if(isset($s->count))
	$ss = $s->count;

	$aa = 0;
	if(isset($a->count))
	$aa = $a->count;

	$tt = 0;
	if(isset($t->count))
	$tt = $t->count;

echo '
		<input type="hidden" id="tanaan_sun" value="'.$ss.'">
		<input type="hidden" id="tanaan_al" value="'.$aa.'">
		<input type="hidden" id="tanaan_tehdyt" value="'.$tt.'">

                      <table class="table mbn tc-med-1 tc-bold-last">
                        <thead>
                          <tr class="hidden">
                            <th>#</th>
                            <th>First Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-warning fs14 mr10"></span>'.Yii::t('main','Suunnitellut').'</td>
                            <td>'.$ss.'</td>
                          </tr>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-info fs14 mr10"></span>'.Yii::t('main','Käynnissä').'</td>
                            <td>'.$aa.'</td>
                          </tr>
                          <tr>
                            <td>
                              <span class="fa fa-circle text-primary fs14 mr10"></span>'.Yii::t('main','Tehdyt').'</td>
                            <td>'.$tt.'</td>
                          </tr>
                        </tbody>
                      </table>
';
?>

                    </div>
                  </div>
                </div>
              </div>





             <!-- Pie Chart -->
              <div class="panel" id="p10">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Työntekijät tänään'); ?></span>
		  <?php 
		   $parasSiivoja = json_encode($this->parasSiivojaTanaan());
		  ?>
		  <div id="pieParasSiivoja" style="display:none"><?php echo $parasSiivoja; ?></div>
                </div>
                <div class="panel-body pn">
                  <div id="high-pie" style="width: 100%; height: 200px; margin: 0 auto"></div>
                </div>
              </div>



              <!-- Country List -->
              <div class="panel" id="p216">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Suunniteltu'); ?></span>
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

		    <?php
       		    $criteria = new CDbCriteria();
       		    $criteria->order = " DATE_FORMAT(STR_TO_DATE(CONCAT(pvm, alku), '%d.%m.%Y %H:%i'), '%Y-%m-%d  %H:%i') ASC";
       		    $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
			AND alku!='00:00'
		    ";
		    $m = Tyovuoroot::model()->findAll($criteria);
		    if(isset($m[0]))
		    {

			foreach($m as $data)
			{
			$t = Tyontekijat::model()->findbypk($data->tid);
			$k = Kohteet::model()->findbypk($data->kohde);
			   if(isset($k->id) and isset($t->id))
			   {
			     echo '<tr>
	                        <td>
	                          '.$data->alku.'-'.$data->loppu.'</td>
	                        <td>'.$t->tekijan_nimi.'<br>'.$k->osoite.'</td>
	                      </tr>
				  ';
			   }
			}
	
		    }
		    ?>

                    </tbody>
                  </table>
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
			$tyot = $this->tilatTanaan(3);
			$matkat = $this->tilatTanaan(2);
			$lounaat = $this->tilatTanaan(10);
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
                <div class="panel-body">
		  <?php 
// Tyovuoro tksekkaus
$criteria=new CDbCriteria;
$criteria->condition = " 
	DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
	AND ilmoitus_myohastyneista_kohteesta=1
";
$tvc = Tyovuoroot::model()->findAll($criteria);

if(count($tvc) > 0)
{
echo '
<div class="row">
  <div class="col-sm-12">';
  foreach($tvc as $dat)
  {
	$k = Kohteet::model()->findbypk($dat->kohde);
	$t = Tyontekijat::model()->findbypk($dat->tid);
	if(isset($t->id) and isset($k->id))
	{
		echo '<p>'.$t->tekijan_nimi.', '.$k->osoite.'<br>'.$dat->pvm.' - '.$dat->alku.'-'.$dat->loppu.'</p>';
	}
  }
echo '</div></div>';
}
// Tyovuoro tksekkaus -->
		  ?>
                </div>
              </div>



              <div class="panel" id="p56">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Määräajanylittäneet kohteet'); ?></span>
                </div>
                <div class="panel-body">
		  <?php 


$criteria=new CDbCriteria;
$criteria->condition = " 
	DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
	AND ilmoitus_avoimista_kohteesta=1
";
$tv = Tyovuoroot::model()->findAll($criteria);

if(count($tv) > 0)
{
echo '

<div class="row">
  <div class="col-sm-12">';
  foreach($tv as $dat)
  {
	$k = Kohteet::model()->findbypk($dat->kohde);
	$t = Tyontekijat::model()->findbypk($dat->tid);
	if(isset($t->id) and isset($k->id))
	{
		echo '<p>'.$t->tekijan_nimi.', '.$k->osoite.'<br>'.$dat->pvm.' - '.$dat->alku.'-'.$dat->loppu.'</p>';
	}
  }
echo '</div></div>';
}
// Tyovuoro tksekkaus -->
		  ?>
                </div>
              </div>



              <!-- Bar Graph -->
              <div class="panel" id="p12">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Toimipaikat'); ?></span>
		  <?php 
		    $month1 = $months[date("m")];
		    $month2 = $months[date("m",strtotime("-1 month"))];
		    $hesari1 = $this->toteutuThisMonthByCity(date("Ym"), "helsinki"); 
		    $hesari2 = $this->toteutuThisMonthByCity(date("Ym",strtotime("-1 month")), "helsinki"); 
		    $espoo1 = $this->toteutuThisMonthByCity(date("Ym"), "espoo"); 
		    $espoo2 = $this->toteutuThisMonthByCity(date("Ym",strtotime("-1 month")), "espoo");
		    $vantaa1 = $this->toteutuThisMonthByCity(date("Ym"), "vantaa"); 
		    $vantaa2 = $this->toteutuThisMonthByCity(date("Ym",strtotime("-1 month")), "vantaa");
		  ?>
                  <input type="hidden" id="month1" value="<?php echo $month1; ?>">
                  <input type="hidden" id="month2" value="<?php echo $month2; ?>">
                  <input type="hidden" id="hesari1" value="<?php echo $hesari1; ?>">
                  <input type="hidden" id="hesari2" value="<?php echo $hesari2; ?>">
                  <input type="hidden" id="espoo1" value="<?php echo $espoo1; ?>">
                  <input type="hidden" id="espoo2" value="<?php echo $espoo2; ?>">
                  <input type="hidden" id="vantaa1" value="<?php echo $vantaa1; ?>">
                  <input type="hidden" id="vantaa2" value="<?php echo $vantaa2; ?>">
                </div>
                <div class="panel-menu">

                  <div class="chart-legend" data-chart-id="#high-bars">
                    <a data-chart-id="0" class="legend-item btn btn-warning btn-sm mr5">Data 1</a>
                    <a data-chart-id="1" class="legend-item btn btn-primary btn-sm mr5">Data 2</a>
                    <a data-chart-id="2" class="legend-item btn btn-info btn-sm">Data 3</a>
                  </div>
                </div>
                <div class="panel-body pn">
                  <div id="high-bars" style="width: 100%; height: 140px; margin: 0 auto"></div>
                </div>
              </div>

            </div>
            <!-- end: .col-md-4-->



            <div class="col-md-6 col-lg-3 admin-grid">

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
		    <?php
       		    $criteria = new CDbCriteria();
       		    $criteria->order = " time DESC ";
       		    $criteria->group = "user";
		    $uo = UsersOnline::model()->findAll($criteria);
		    if(isset($uo[0]))
		    {
			foreach($uo as $data)
			{
			  echo '
			  <tr>
			   <td>'.date("H:i",$data->time).'</td>
			   <td>'.$data->user.'</td>
			  </tr>
			  ';
			}
	
		    }
		    ?>
                    </tbody>
                  </table>
                </div>
              </div>



              <!-- Country List -->
              <div class="panel" id="p16">
                <div class="panel-heading">
                  <span class="panel-title"><?php echo Yii::t('main', 'Avoimet kohteet'); ?></span>
                </div>
                <div class="panel-body pn">
                  <table class="table mbn tc-med-1 tc-bold-last">
                    <thead>
                      <tr class="hidden">
                        <th>#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody>

		    <?php
       		    $criteria = new CDbCriteria();
       		    $criteria->select = " aloitan,loppui,kohde_kannasta  ";
       		    $criteria->order = " id DESC  ";
       		    $criteria->group = "kohde_kannasta";
       		    $criteria->condition = "status=1";
		    $m = Mobile::model()->findAll($criteria);
		    if(isset($m[0]))
		    {
			foreach($m as $data)
			{

 			  $data->loppui = date("d.m.Y H:i",time());
			  $data->aloitan = date("d.m.Y H:i",strtotime($data->aloitan));
			  $kesto =  strtotime($data->loppui) - strtotime($data->aloitan);

			  echo '
                      <tr>
                        <td>
                          <span class=""></span> '.$data->kohde_kannasta.'</td>
                        <td>'.$this->sprint($kesto).'</td>
                      </tr>
			  ';
			}
	
		    }
		    ?>

                    </tbody>
                  </table>
                </div>
              </div>


            </div>
            <!-- end: .col-md-3-->


          </div>
          <!-- end: .row -->
	

	<div class="row">
	 <div class="form-inline">
		<?php echo Yii::t('main', 'Alue/Kaupunki'); ?> <input type="text" class="form-control form-group" id="alueKaupunki">
		 <button class="tallennaLatLng btn btn-primary myBgColors"><?php echo Yii::t('main', 'Tallenna'); ?></button>

		<select id="tilanneKartalla" class="form-control">
		<option><?php echo Yii::t('main', 'Tilanne'); ?></option>
		<option value="aktiiviset"><?php echo Yii::t('main', 'Aktiiviset tänään'); ?></option>
		<option value="toteutetut"><?php echo Yii::t('main', 'Toteutetut tänään'); ?></option>
		<option value="kaikki"><?php echo Yii::t('main', 'Kaikki tänään'); ?></option>
		</select>
		<input type="hidden" id="getTila" value="<?php if(isset($_GET['tila'])) echo $_GET['tila']; ?>">
	 </div>
	</div>

	<div class="row">
	  <div id="kartta">
		<iframe scrolling="no" style="width: 100%; height: 700px; border: none" id="iframekartta"></iframe>
	  </div>
	</div>


<script type="text/javascript">
$(document).ready(function(){

$(".tallennaLatLng").click(function(){
	localStorage.setItem('alueKaupunki', $("#alueKaupunki").val());
	window.location.reload();
});

	var keskusta = '';
	var tila = '';
	
	if($('#getTila').val() !== '')
	{
		tila = $('#getTila').val();
		$("#tilanneKartalla").val(tila);
	}

	if (localStorage.getItem('alueKaupunki') !== "") {
		$("#alueKaupunki").val(localStorage.getItem('alueKaupunki'));
		keskusta = localStorage.getItem('alueKaupunki');
	}


	$('#iframekartta').attr('src', location.protocol + '//' + location.host + '/index.php/kohteet/googlemap?tila='+tila+'&nomenu=true&center='+keskusta);


$("#tilanneKartalla").change(function(){
	var tila = $(this).val();
	window.location.href="etusivu?tila="+tila;
});


});
</script>





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


