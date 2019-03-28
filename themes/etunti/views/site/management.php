<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

ini_set("max_execution_time", "60");
?>




        <!-- begin: .tray-center -->
        <div class="tray-center">

            <h2 class="myBgColors p10"> <i class="fa fa-home"></i> <?php echo Yii::t('main', 'Kaavio'); ?> </h2>

   	    <form id="yhtveto" action="#" class="form-inline" method="GET">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				$postvalue = '';
				if(isset($_GET[$sarake])){ $postvalue = $_GET[$sarake]; }
		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($from))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?=date("d.m.Y", strtotime($to))?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-4">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


<br>


<?php
$months=array(
	1=>Yii::t('main', 'Tammikuu'),
	2=>Yii::t('main', 'Helmikuu'),
	3=>Yii::t('main', 'Maaliskuu'),
	4=>Yii::t('main', 'Huhtikuu'),
	5=>Yii::t('main', 'Toukokuu'),
	6=>Yii::t('main', 'Kesäkuu'),
	7=>Yii::t('main', 'Heinäkuu'),
	8=>Yii::t('main', 'Elokuu'),
	9=>Yii::t('main', 'Syyskuu'),
	10=>Yii::t('main', 'Lokakuu'),
	11=>Yii::t('main', 'Marraskuu'),
	12=>Yii::t('main', 'Joulukuu')
	);

	$result = array();

	// <-- Luetut
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas->id."') "); 
	}
	$luetut = Mobile::model()->findAll($criteria);
	$data_luetut = array();
	foreach($luetut as $item){
		$data_luetut[date("Ym", strtotime($item->aloitan))] = round($this->num($item->l_tunnit), 2);

	}
	//     Luetut -->

	// <-- Suunnitellut
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND peruutettu=0
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" kohde IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas->id."') "); 
	}
	$suunnitellut = Tyovuoroot::model()->findAll($criteria);
	$data_suunnitellut = array();
	foreach($suunnitellut as $item){
		$data_suunnitellut[date("Ym", strtotime($item->pvm))] = round($this->num($item->l_tunnit), 2);

	}
	//     Suunnitellut -->

	// <-- Hyvaksytyt
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status=3
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas->id."') "); 
	}
	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
       	$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$asiakas->id."') "); 
	}
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);
	$data_hyvaksytyt = array();
	$categories = array();
	foreach($result as $item){
		if( !isset($data_hyvaksytyt[date("Ym", strtotime($item->aloitan))]) ){ 
			$data_hyvaksytyt[date("Ym", strtotime($item->aloitan))] = 0;
		}
		$data_hyvaksytyt[date("Ym", strtotime($item->aloitan))] += round($this->num($item->l_tunnit), 2);
		$categories[date("Ym", strtotime($item->aloitan))] = date("Y", strtotime($item->aloitan)).', '.$months[date("n", strtotime($item->aloitan))];
	}
	//     Hyvaksytyt -->

?>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<div id="container" style="height: 500px"></div>
<script>
Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Vuoden luetut, hyväksytyt ja suunnitellut tunnit'
    },
    subtitle: {
        text: '<?=(isset($_GET["yrityksen_nimi"]))? $_GET["yrityksen_nimi"] : Yii::app()->user->domain?>'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Tunnit'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Luetut',
        data: JSON.parse('<?=json_encode(array_values($data_luetut))?>')
    }, 
    {
        name: 'Hyväksytyt',
        data: JSON.parse('<?=json_encode(array_values($data_hyvaksytyt))?>')
    },
    {
        name: 'Suunnitellut',
        data: JSON.parse('<?=json_encode(array_values($data_suunnitellut))?>')
    },]
});
</script>
