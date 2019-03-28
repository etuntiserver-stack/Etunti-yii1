<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

ini_set("max_execution_time", "60");
?>




        <!-- begin: .tray-center -->
        <div class="tray-center">

            <h2 class="myBgColors p10"> <i class="fa fa-line-chart"></i> Kaaviot </h2>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_slh')): ?>
   	    <form id="yhtveto_asiakas" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="asiakkaat_slh">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Asiakas kuukausittain')?></h3></legend>
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
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="line"><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
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

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>

	    <?php if(!isset($_GET['haku']) or (isset($_GET['haku']) and $_GET['haku'] == 'tyontekijat')): ?>
   	    <form id="yhtveto_tyontekijat" action="#" class="form-inline" method="GET">
	    <input type="hidden" name="haku" value="tyontekijat">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

	    	    <legend><h3><?=Yii::t('main', 'Työntekijät')?></h3></legend>
                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'tyontekija', // name
						'gui-input', //class
						'tyontekija', // id
						(isset($_GET['tyontekija']))?$_GET['tyontekija']:'', //selected
						1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
		      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="chart_tyyppi" class="gui-input">
			     <option value="line"><?php echo Yii::t('main', 'Line'); ?></option>
			     <option value="column" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'column')?'selected':''?>><?php echo Yii::t('main', 'Column'); ?></option>
			     <option value="bar" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'bar')?'selected':''?>><?php echo Yii::t('main', 'Bar'); ?></option>
			     <option value="area" <?=(isset($_GET['chart_tyyppi']) and $_GET['chart_tyyppi'] == 'area')?'selected':''?>><?php echo Yii::t('main', 'Area'); ?></option>
			    </select>
                            <i class="arrow double"></i>
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

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo kaavio'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>
	    </form>
	    <?php endif; ?>
        <!-- loppu: .tray-center -->
        </div>


<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<div id="container" style="height: 500px"></div>

<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_slh'): ?>
<?php
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

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Luetut, hyväksytyt ja suunnitellut tunnit'
    },
    subtitle: {
        text: '<?=(isset($_GET["yrityksen_nimi"]) and !empty($_GET["yrityksen_nimi"]))? $_GET["yrityksen_nimi"] : "Kaikki asiakkaat"?>'
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
    },],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>



<?php if(isset($_GET['haku']) and $_GET['haku'] == 'tyontekijat'): ?>
<?php
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
	if( isset($_GET['tyontekija']) ){
	$criteria->addCondition(" tid='".$_GET['tyontekija']."' "); 
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
	if( isset($_GET['tyontekija']) ){
	$criteria->addCondition(" tid='".$_GET['tyontekija']."' "); 
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
	if( isset($_GET['tyontekija']) ){
	$criteria->addCondition(" tid='".$_GET['tyontekija']."' "); 
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
	if( isset($_GET['tyontekija']) ){
	$criteria->addCondition(" tid='".$_GET['tyontekija']."' "); 
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

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: '<?=(isset($_GET["tyontekija"]) and !empty($_GET["tyontekija"]))? $this->etuSukunimi($_GET["tyontekija"]) : ""?>'
    },
    subtitle: {
        text: 'Luetut, hyväksytyt ja suunnitellut tunnit'
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
    },],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>



<script>
$(document).ready(function(){

$('.mult').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});

  $("#yhtveto_tyontekijat").on("submit", function(){
	if( $("#tyontekija option:selected").val() == 'kaikki' ){
		alert("Valitse työntekijä.");
		return false;
	}
  });
});
</script>
