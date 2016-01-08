<?php

$months=array(
	'01'=>'Tammikuu',
	'02'=>'Helmikuu',
	'03'=>'Maaliskuu',
	'04'=>'Huhtikuu',
	'05'=>'Toukokuu',
	'06'=>'Kesäkuu',
	'07'=>'Heinäkuu',
	'08'=>'Elokuu',
	'09'=>'Syyskuu',
	'10'=>'Lokakuu',
	'11'=>'Marraskuu',
	'12'=>'Joulukuu',
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
                <h5 class="text-muted"><?php echo $months[date("m")]; ?> toteuma</h5>
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
		if(isset($a->l_tunnit))
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
                  <i class="fa fa-bar-chart-o"></i>
                </div>
                <h2 class="mt15 lh15">
                  <b>267</b>
                </h2>
                <h5 class="text-muted">Reach</h5>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-3">
            <div class="panel bg-warning light of-h mb10">
              <div class="pn pl20 p5">
                <div class="icon-bg">
                  <i class="fa fa-envelope"></i>
                </div>
                <h2 class="mt15 lh15">
                  <b>714</b>
                </h2>
                <h5 class="text-muted">Comments</h5>
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



              <!-- Stats Top Graph Bot -->
              <div class="panel" id="p7">
                <div class="panel-heading">
                  <span class="panel-title">Area Graph</span>
                </div>
                <div class="panel-body pn">
                  <div class="br-b admin-form">
                    <div class="smart-widget sm-right smr-50">
                      <label class="field">
                        <input type="text" name="sub" id="sub" class="gui-input br-n" placeholder="Search State">
                      </label>
                      <button type="submit" class="button br-n br-l">
                        <i class="fa fa-caret-down"></i>
                      </button>
                    </div>
                    <table class="table mbn br-t">
                      <thead>
                        <tr class="hidden">
                          <th>#</th>
                          <th>First Name</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="va-m fw600 text-muted">
                            <span class="fa fa-female text-primary fs14 ml5 mr10"></span>Male</td>
                          <td class="fs14 fw600 text-right">54%</td>
                        </tr>
                        <tr>
                          <td class="va-m fw600 text-muted">
                            <span class="fa fa-male text-info fs14 ml5 mr10"></span>Female</td>
                          <td class="fs14 fw600 text-right">46%</td>
                        </tr>
                        <tr>
                          <td class="va-m fw600 text-muted">
                            <span class="fa fa-child text-warning fs15 ml5 mr10"></span>Unemployed</td>
                          <td class="fs14 fw600 text-right">14%</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div id="high-line3" style="width: 100%; height: 210px; margin: 0 auto"></div>
                </div>
              </div>

             <!-- Pie Chart -->
              <div class="panel" id="p10">
                <div class="panel-heading">
                  <span class="panel-title">Pie Chart</span>
                </div>
                <div class="panel-body pn">
                  <div id="high-pie" style="width: 100%; height: 200px; margin: 0 auto"></div>
                </div>
              </div>

              <!-- Column Graph -->
              <div class="panel" id="p8">
                <div class="panel-heading">
                  <span class="panel-title">State Icon</span>
                </div>
                <div class="panel-body pn">
                  <div class="row table-layout">
                    <div class="col-xs-4 text-center posr">
                      <span data-toggle="tooltip" data-placement="top" title="Missouri" class="stateface stateface-mo fs70 text-info light t-center"></span>
                    </div>
                    <div class="col-xs-8 br-l pn admin-form">
                      <div class="smart-widget sm-right smr-50">
                        <label class="field">
                          <input type="text" name="sub" id="sub" class="gui-input br-n br-b" placeholder="Search State">
                        </label>
                        <button type="submit" class="button br-n br-b br-l">
                          <i class="fa fa-caret-down"></i>
                        </button>
                      </div>
                      <table class="table mbn">
                        <thead>
                          <tr class="hidden">
                            <th>#</th>
                            <th>First Name</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="va-m fw600 text-muted">
                              <span class="fa fa-female text-primary fs14 mr10"></span>Male</td>
                            <td class="fs14 fw600 text-right">54%</td>
                          </tr>
                          <tr>
                            <td class="va-m fw600 text-muted">
                              <span class="fa fa-male text-info fs14 mr10"></span>Female</td>
                            <td class="fs14 fw600 text-right">46%</td>
                          </tr>
                          <tr>
                            <td class="va-m fw600 text-muted">
                              <span class="fa fa-child text-warning fs15 mr10"></span>Unemployed</td>
                            <td class="fs14 fw600 text-right">14%</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Geo Map + Table Stats -->
              <div class="panel" id="p9">
                <div class="panel-heading">
                  <span class="panel-title">Visitor Geography</span>
                </div>
                <div class="panel-body">
                  <div id="WidgetMap" class="jvector-colors hide-jzoom" style="width: 100%; height: 220px;"></div>
                </div>
                <div class="panel-menu admin-form pn">
                  <!-- Panel Break Smart Widget -->
                  <div class="smart-widget sm-right smr-50">
                    <label class="field">
                      <input type="text" name="sub" id="sub" class="gui-input br-n" placeholder="United States of America" disabled>
                    </label>
                    <button type="submit" class="button br-n br-l">
                      <i class="fa fa-caret-down"></i>
                    </button>
                  </div>
                </div>
                <div class="panel-body pn">
                  <table class="table mbn">
                    <thead>
                      <tr class="hidden">
                        <th>#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="va-m fw600 text-muted">
                          <span class="fa fa-circle text-alert fs14 mr10"></span>New York</td>
                        <td class="fs15 fw600 text-right">7%</td>
                      </tr>
                      <tr>
                        <td class="va-m fw600 text-muted">
                          <span class="fa fa-circle text-info fs14 mr10"></span>Missouri</td>
                        <td class="fs15 fw600 text-right">14%</td>
                      </tr>
                      <tr>
                        <td class="va-m fw600 text-muted">
                          <span class="fa fa-circle text-primary fs14 mr10"></span>Texas</td>
                        <td class="fs15 fw600 text-right">7%</td>
                      </tr>
                      <tr>
                        <td class="va-m fw600 text-muted">
                          <span class="fa fa-circle text-warning fs14 mr10"></span>California</td>
                        <td class="fs15 fw600 text-right">24%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
            <!-- end: .col-md-5-->

            <div class="col-md-6 col-lg-4 admin-grid">

              <!-- Column Graph -->
              <div class="panel" id="p11">
                <div class="panel-heading">
                  <span class="panel-title">Response Time</span>
                </div>
                <div class="panel-menu pn bg-white">
                  <ul class="nav nav-justified text-center fw600 chart-legend" data-chart-id="#high-column3">
                    <li>
                      <a href="#" class="legend-item" data-chart-id="0"> Tech </a>
                    </li>
                    <li class="br-l">
                      <a href="#" class="legend-item" data-chart-id="1"> Support </a>
                    </li>
                    <li class="br-l">
                      <a href="#" class="legend-item" data-chart-id="2"> Service </a>
                    </li>
                    <li class="br-l">
                      <a href="#" class="legend-item" data-chart-id="3"> Another </a>
                    </li>
                  </ul>
                </div>
                <div class="panel-body pbn">
                  <div id="high-column3" style="width: 100%; height: 400px; margin: 0 auto"></div>
                </div>
                <div class="panel-footer p15">
                  <p class="text-muted text-center mbn">A percent measure of tickets with
                    <b class="text-info">first</b> reply time</p>
                </div>
              </div>


              <!-- Circle Stats -->
              <div class="panel" id="p5">
                <div class="panel-heading">
                  <span class="panel-title">Circulars</span>
                </div>
                <div class="panel-body">
                  <div class="mb20 text-right">
                    <span class="fs11 text-muted ml10">
                      <i class="fa fa-circle text-primary fs12 pr5"></i> Facebook</span>
                    <span class="fs11 text-muted ml10">
                      <i class="fa fa-circle text-info fs12 pr5"></i> Twitter</span>
                    <span class="fs11 text-muted ml10">
                      <i class="fa fa-circle text-warning fs12 pr5"></i> Google+</span>
                  </div>
                  <div class="row">
                    <div class="col-xs-4 text-center">
                      <div class="info-circle" id="c1" value="80" data-circle-color="primary"></div>
                    </div>
                    <div class="col-xs-4">
                      <div class="info-circle" id="c2" value="30" data-circle-color="info"></div>
                    </div>
                    <div class="col-xs-4">
                      <div class="info-circle" id="c3" value="55" data-circle-color="warning"></div>
                    </div>
                  </div>
                </div>
              </div>


              <!-- Bar Graph -->
              <div class="panel" id="p12">
                <div class="panel-heading">
                  <span class="panel-title">Bar Graph</span>
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

              <!-- Sparklines -->
              <div class="panel" id="p13">
                <div class="panel-heading">
                  <span class="panel-title">Sparklines</span>
                </div>
                <div class="panel-body pn of-a">
                  <table class="table mbn">
                    <thead>
                      <tr class="hidden">
                        <th class="mw30">1</th>
                        <th>Data</th>
                        <th>Sparkline</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="fs18 text-center w30">
                          <span class="fa fa-desktop text-warning"></span>
                        </td>
                        <td class="fw600 text-muted">Desktop Viewers</td>
                        <td>
                          <span class="inlinesparkline pull-right" data-spark-color="warning" values="5,6,7,9,9,5,3,2,2,4,6"></span>
                        </td>
                      </tr>
                      <tr>
                        <td class="fs18 text-center">
                          <span class="fa fa-tablet text-primary"></span>
                        </td>
                        <td class="fw600 text-muted">Tablet Viewers</td>
                        <td>
                          <span class="inlinesparkline pull-right" data-spark-color="info" values="4,6,7,9,9,5,3,2,2,4,6,7"></span>
                        </td>
                      </tr>
                      <tr>
                        <td class="fs18 text-center">
                          <span class="fa fa-phone text-info"></span>
                        </td>
                        <td class="fw600 text-muted">Customer Support</td>
                        <td>
                          <span class="inlinesparkline pull-right" data-spark-color="primary" values="7,3,2,2,4,6,7,6,7,9"></span>
                        </td>
                      </tr>
                      <tr>
                        <td class="fs18 text-center">
                          <span class="fa fa-rocket text-success"></span>
                        </td>
                        <td class="fw600 text-muted">Rocket Explosions</td>
                        <td>
                          <span class="inlinesparkline pull-right" data-spark-color="alert" values="2,6,7,9,9,5,3,2,2,4,6,7"></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Area Graph -->
              <div class="panel" id="p14">
                <div class="panel-heading">
                  <span class="panel-title">Area Graph</span>
                </div>
                <div class="panel-menu">
                  <div class="chart-legend" data-chart-id="#high-area">
                    <a data-chart-id="0" class="legend-item btn btn-sm btn-warning ph20 mr10">Data 1</a>
                    <a data-chart-id="1" class="legend-item btn btn-sm btn-primary mr10">Data 2</a>
                    <a data-chart-id="2" class="legend-item btn btn-sm btn-info mr10">Data 3</a>
                  </div>
                </div>
                <div class="panel-body pn">
                  <div id="high-area" style="width: 100%; height: 230px; margin: 0 auto"></div>
                </div>
              </div>

            </div>
            <!-- end: .col-md-4-->

            <div class="col-md-6 col-lg-3 admin-grid">

              <!-- Dot List -->
              <div class="panel" id="p15">
                <div class="panel-heading">
                  <span class="panel-title">Dot List</span>
                </div>
                <div class="panel-menu admin-form pn">
                  <!-- Panel Break Smart Widget -->
                  <div class="smart-widget sm-right smr-50">
                    <label class="field">
                      <input type="text" name="sub" id="sub" class="gui-input br-n" placeholder="Add Social Network">
                    </label>
                    <button type="submit" class="button br-n br-l">
                      <i class="fa fa-plus"></i>
                    </button>
                  </div>
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
                      <tr>
                        <td>
                          <span class="fa fa-circle text-warning fs14 mr10"></span>Behance</td>
                        <td>24%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-circle text-info fs14 mr10"></span>Twitter</td>
                        <td>7%</td>
                      </tr>
                      <tr>
                        <td class="va-m fw600 text-muted">
                          <span class="fa fa-circle text-primary fs14 mr10"></span>Facebook</td>
                        <td>14%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Country List -->
              <div class="panel" id="p16">
                <div class="panel-heading">
                  <span class="panel-title">Country List</span>
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
                      <tr>
                        <td>
                          <span class="flag-xs flag-us mr5 va-b"></span>United States</td>
                        <td>24%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="flag-xs flag-de mr5 va-b"></span>Germany</td>
                        <td>7%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="flag-xs flag-fr mr5 va-b"></span>France</td>
                        <td>14%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="flag-xs flag-tr mr5 va-b"></span>Turkey</td>
                        <td>31%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="flag-xs flag-es mr5 va-b"></span>Spain</td>
                        <td>22%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Search List -->
              <div class="panel" id="p17">
                <div class="panel-heading">
                  <span class="panel-title">Crawler List</span>
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
                      <tr>
                        <td>
                          <span class="favicons google va-t mr10"></span>pages.com/article/7</td>
                        <td>24%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="favicons google va-t mr10"></span>pages.com/img/15</td>
                        <td>7%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="favicons yahoo va-t mr10"></span>pages.com/popular</td>
                        <td>14%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="favicons google va-t mr10"></span>pages.com/news/3</td>
                        <td>31%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="favicons bing va-t mr10"></span>pages.com/featured/16</td>
                        <td>22%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Browser List -->
              <div class="panel" id="p18">
                <div class="panel-heading">
                  <span class="panel-title">Browser List</span>
                </div>
                <div class="panel-body pn">
                  <table class="table mbn tc-med-1 tc-bold-2">
                    <thead>
                      <tr class="hidden">
                        <th>#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <span class="favicons chrome va-t mr10"></span>United States</td>
                        <td>39%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="favicons firefox va-t mr10"></span>Germany</td>
                        <td>43%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="favicons ie va-t mr10"></span>France</td>
                        <td>14%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="favicons safari va-t mr10"></span>Spain</td>
                        <td>33%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Icon List -->
              <div class="panel" id="p19">
                <div class="panel-heading">
                  <span class="panel-title">Icon List</span>
                </div>
                <div class="panel-body pn">
                  <table class="table mbn tc-icon-1 tc-med-2 tc-bold-last">
                    <thead>
                      <tr class="hidden">
                        <th class="mw30">#</th>
                        <th>First Name</th>
                        <th>Revenue</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <span class="fa fa-desktop text-warning"></span>
                        </td>
                        <td>T.V.</td>
                        <td>
                          <i class="fa fa-caret-up text-info pr10"></i>$855,913</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-microphone text-primary"></span>
                        </td>
                        <td>Radio</td>
                        <td>
                          <i class="fa fa-caret-down text-danger pr10"></i>$349,712</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-newspaper-o text-info"></span>
                        </td>
                        <td>Paper</td>
                        <td>
                          <i class="fa fa-caret-up text-info pr10"></i>$95,342</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-android text-alert"></span>
                        </td>
                        <td>Android</td>
                        <td>
                          <i class="fa fa-caret-up text-info pr10"></i>$452,672</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-power-off text-system"></span>
                        </td>
                        <td>Digital</td>
                        <td>
                          <i class="fa fa-caret-up text-info pr10"></i>$12,352</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Dot Stats -->
              <div class="panel" id="p20">
                <div class="panel-heading">
                  <span class="panel-title">Dot/Addon Stats</span>
                </div>
                <div class="panel-body pn">
                  <table class="table mbn tc-med-1 tc-bold-last ">
                    <thead>
                      <tr class="hidden">
                        <th>#</th>
                        <th>First Name</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <span class="fa fa-circle text-warning fs14 mr10"></span>Behance</td>
                        <td>24%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-circle text-info fs14 mr10"></span>Twitter</td>
                        <td>7%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-circle text-primary fs14 mr10"></span>Facebook</td>
                        <td>14%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-circle text-alert fs14 mr10"></span>Google Plus</td>
                        <td>24%</td>
                      </tr>
                      <tr>
                        <td>
                          <span class="fa fa-circle text-system fs14 mr10"></span>Dribble</td>
                        <td>7%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Text List -->
              <div class="panel" id="p21">
                <div class="panel-heading">
                  <span class="panel-title">Text List</span>
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
                        <td>1.</td>
                        <td>Lorem ipsum dolor sit</td>
                      </tr>
                      <tr>
                        <td>2.</td>
                        <td>Lorem ipsc beyond ray</td>
                      </tr>
                      <tr>
                        <td>3.</td>
                        <td>Amet, consectetur adipi</td>
                      </tr>
                      <tr>
                        <td>4.</td>
                        <td>Lorem consec iscing</td>
                      </tr>
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

      <!-- Begin: Page Footer -->
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
      <!-- End: Page Footer -->

    </section>
    <!-- End: Content-Wrapper -->

    <!-- Start: Right Sidebar -->
    <aside id="sidebar_right" class="nano affix">

      <!-- Start: Sidebar Right Content -->
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
          <!-- end: .tab-content -->
        </div>

      </div>
    </aside>
    <!-- End: Right Sidebar -->

  </div>
  <!-- End: Main -->


