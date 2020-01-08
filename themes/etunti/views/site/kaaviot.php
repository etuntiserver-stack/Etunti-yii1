<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<style>
	/* Container div for the chart toggle menu. */
	#toggle-menu-container {
		position: relative;
	}

	/* Chart toggle menu (hidden, collapsible). */
  #toggle-menu {
    position: absolute;
    z-index: 999;
    background-color:whitesmoke;
    border: 1px solid #ddd;
    padding: 2px 8px;
  }

	/* Save button of the toggle chart form. */
  #toggle-menu-save {
    margin: 12px 0px 6px;
  }

	/* Add margin between the automatic bootstrap-toggle checkboxes. */
  .toggle.custom {
    margin: 4px 0px;
  }

	/* Remove automatic padding from the bootstrap-toggle input containers. */
  .checkbox-inline, .radio-inline {
    padding-left: 0px;
  }

	/* Container div for all displayed charts. */
  .chart-container {
    height: 350px;
  }
</style>

<!------------------------------------------------------------------------------
-- Yläpalkki
------------------------------------------------------------------------------->

<!-- Toggle button -->
<button class="btn-primary" data-toggle="collapse" data-target="#toggle-menu" aria-expanded="false" aria-controls="toggle-menu">
  <b>Valitse näytetyt kaaviot</b>
</button>

<!-- Main chart toggle menu -->
<div id="toggle-menu-container">
  <div id="toggle-menu" class="collapse">
    <form action="#" method="POST">
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_tyovuorojen_maara'])) echo 'checked="checked"'; ?>
          name="toggle_tyovuorojen_maara" id="toggle_tyovuorojen_maara"> Työvuorojen määrä ajanjaksolla
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_lomat_ja_poissaolot'])) echo 'checked="checked"'; ?>
          name="toggle_lomat_ja_poissaolot" id="toggle_lomat_ja_poissaolot"> Lomat ja poissaolot
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_kpl'])) echo 'checked="checked"'; ?>
          name="toggle_uudet_lopettaneet_asiakkaat_kpl" id="toggle_uudet_lopettaneet_asiakkaat_kpl"> Uudet ja lopettaneet asiakkaat: KPL määrä
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_tuovuorot'])) echo 'checked="checked"'; ?>
          name="toggle_uudet_lopettaneet_asiakkaat_tuovuorot" id="toggle_uudet_lopettaneet_asiakkaat_tuovuorot"> Uudet ja lopettaneet asiakkaat: Suunnitellut työvuorot
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_tyontekijat_eniten_tunteja'])) echo 'checked="checked"'; ?>
          name="toggle_tyontekijat_eniten_tunteja" id="toggle_tyontekijat_eniten_tunteja"> Työntekijät, joilla eniten hyväksyttyjä tunteja
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_asiakkaat_eniten_tunteja'])) echo 'checked="checked"'; ?>
          name="toggle_asiakkaat_eniten_tunteja" id="toggle_asiakkaat_eniten_tunteja"> Asiakkaat, joille on tehty eniten hyväksyttyjä tunteja
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_tunnit'])) echo 'checked="checked"'; ?>
          name="toggle_tunnit" id="toggle_tunnit"> Suunnitellut, luetut ja hyväksytyt tunnit
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_eniten_suunniteltu_kestot'])) echo 'checked="checked"'; ?>
          name="toggle_eniten_suunniteltu_kestot" id="toggle_eniten_suunniteltu_kestot"> Eniten suunniteltu työvuoro: Kestot
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_eniten_suunniteltu_kpl'])) echo 'checked="checked"'; ?>
          name="toggle_eniten_suunniteltu_kpl" id="toggle_eniten_suunniteltu_kpl"> Eniten suunniteltu työvuoro: KPL
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_lahetetut_laskut_maara_summa'])) echo 'checked="checked"'; ?>
          name="toggle_lahetetut_laskut_maara_summa" id="toggle_lahetetut_laskut_maara_summa"> Lähetettyjen laskujen määrä ja summa
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_liikevaihto_arvokkaimmat_asiakkaat'])) echo 'checked="checked"'; ?>
          name="toggle_liikevaihto_arvokkaimmat_asiakkaat" id="toggle_liikevaihto_arvokkaimmat_asiakkaat"> Liikevaihto arvokkaimmat asiakkaat
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_tyontekijat'])) echo 'checked="checked"'; ?>
          name="toggle_tyontekijat" id="toggle_tyontekijat"> Työntekijät
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_onlinevaraukset'])) echo 'checked="checked"'; ?>
          name="toggle_onlinevaraukset" id="toggle_onlinevaraukset"> Onlinevaraukset
      </label>
      <br>
      <label class="checkbox-inline">
        <input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_eniten_tuotteet_palvelut_euro'])) echo 'checked="checked"'; ?>
          name="toggle_eniten_tuotteet_palvelut_euro" id="toggle_eniten_tuotteet_palvelut_euro"> Eniten tuotteet ja palvelut euro
      </label>
      <br>
      <div class="row">
        <div class="col-sm-3 col-xs-offset-4">
          <button type="submit" id="toggle-menu-save" class="btn btn-primary btn-sm">Tallenna</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Init bootstrap-toggle -->
<script>
  $(function() {
    $("#toggle_tyovuorojen_maara, #toggle_lomat_ja_poissaolot, #toggle_uudet_lopettaneet_asiakkaat_kpl, " +
      "#toggle_uudet_lopettaneet_asiakkaat_tuovuorot, #toggle_tyontekijat_eniten_tunteja, " +
      "#toggle_asiakkaat_eniten_tunteja, #toggle_tunnit, #toggle_eniten_suunniteltu_kestot, " +
      "#toggle_eniten_suunniteltu_kpl, #toggle_lahetetut_laskut_maara_summa, #toggle_liikevaihto_arvokkaimmat_asiakkaat, " +
      "#toggle_tyontekijat, #toggle_onlinevaraukset, #toggle_eniten_tuotteet_palvelut_euro").bootstrapToggle({
      on: 'Kyllä',
      off: 'Ei',
      size: 'mini',
      width: 50
    });
  })
</script>

<!------------------------------------------------------------------------------
-- Common
------------------------------------------------------------------------------->
<?php


?>
<!------------------------------------------------------------------------------
-- Työvuorojen määrä ajanjaksolla
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyovuorojen_maara'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_tyovuorojen_maara"></div>
</div>

<?php
  $tyovuoroot_controller = Yii::app()->createController('Tyovuoroot')[0];
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_arr = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$criteria = new CDbCriteria();
       	$criteria->order = " COUNT(status) DESC ";
       	$criteria->group = " status ";
       	$criteria->select = "
		COUNT(*) as count, t.*
	";
        $criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND status!=''
	";
	$asiakkaat = Tyovuoroot::model()->findAll($criteria);

	$arr_new = array();
	$arr = array();
	$i = 0;
	foreach($asiakkaat as $v){
		$i++;
		$arr_new[$i] = array('name' => $tyovuoroot_controller->tilanteet()[$v->status] );
		$arr_new[$i]['data'] = array();
		foreach($categories as $k_cat => $item_cat){

			$criteria = new CDbCriteria();
		       	$criteria->select = "
				COUNT(status) as count
			";
		        $criteria->condition = " 
				DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m')='".$k_cat."'
				AND status='".$v->status."'
			";
			$asiakkaat_month = Tyovuoroot::model()->find($criteria);

			$arr_new[$i]['data'][] = (int)$asiakkaat_month->count;
		}
	}
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Työvuorojen määrä <?=date("d.m.Y", strtotime($from))."-".date("d.m.Y", strtotime($to))?>'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'KPL'
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
    series: JSON.parse('<?=json_encode(array_values($arr_new))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>

<!------------------------------------------------------------------------------
-- Lomat ja poissaolot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_lomat_ja_poissaolot'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_lomat_ja_poissaolot"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Uudet ja lopettaneet asiakkaat: KPL määrä
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_kpl'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_uudet_lopettaneet_asiakkaat_kpl"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Uudet ja lopettaneet asiakkaat: Suunnitellut työvuorot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_tuovuorot'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_uudet_lopettaneet_asiakkaat_tuovuorot"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Työntekijät, joilla eniten hyväksyttyjä tunteja
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyontekijat_eniten_tunteja'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_tyontekijat_eniten_tunteja"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Asiakkaat, joille on tehty eniten hyväksyttyjä tunteja
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_asiakkaat_eniten_tunteja'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_asiakkaat_eniten_tunteja"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Suunnitellut, luetut ja hyväksytyt tunnit
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tunnit'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_tunnit"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten suunniteltu työvuoro: Kestot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_suunniteltu_kestot'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_eniten_suunniteltu_kestot"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten suunniteltu työvuoro: KPL
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_suunniteltu_kpl'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_eniten_suunniteltu_kpl"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Lähetettyjen laskujen määrä ja summa
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_lahetetut_laskut_maara_summa'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_lahetetut_laskut_maara_summa"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Liikevaihto arvokkaimmat asiakkaat
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_liikevaihto_arvokkaimmat_asiakkaat'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_liikevaihto_arvokkaimmat_asiakkaat"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Työntekijät
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyontekijat'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_tyontekijat"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Onlinevaraukset
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_onlinevaraukset'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_onlinevaraukset"></div>
</div>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten tuotteet ja palvelut euro
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_tuotteet_palvelut_euro'])): ?>

<div class="col-sm-6">
  <div class="chart-container" id="container_eniten_tuotteet_palvelut_euro"></div>
</div>

<?php endif; ?>





<!-- Lomat ja poissaolot -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'lomat_poissaolot'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_arr = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$criteria = new CDbCriteria();
       	$criteria->order = " tyoajanlaatu ";
       	$criteria->group = " tyoajanlaatu ";
       	$criteria->select = "
		COUNT(*) as count, t.*
	";
        $criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND tyoajanlaatu!=''
	";
	if( isset($_GET['tyontekija']) and $_GET['tyontekija'] !== 'kaikki' and $_GET['tyontekija'] > 0 ){
	        $criteria->addCondition (" tid='".$_GET['tyontekija']."' ");
	}
	$tv = Tyovuoroot::model()->findAll($criteria);

	$arr_new = array();
	$arr = array();
	$i = 0;
	foreach($tv as $v){
		$i++;
		$name_expl = explode("/", $v->tyoajanlaatu);
		$arr_new[$i] = array('name' => ((isset($name_expl[0]))?$name_expl[0]:'') );
		$arr_new[$i]['data'] = array();
		foreach($categories as $k_cat => $item_cat){

			$criteria = new CDbCriteria();
		       	$criteria->select = "
				COUNT(tyoajanlaatu) as count
			";
		        $criteria->condition = " 
				DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m')='".$k_cat."'
				AND tyoajanlaatu='".$v->tyoajanlaatu."'
			";
			$asiakkaat_month = Tyovuoroot::model()->find($criteria);

			$arr_new[$i]['data'][] = (int)$asiakkaat_month->count;
		}
	}
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Lomat ja poissaolot <?=date("d.m.Y", strtotime($from))."-".date("d.m.Y", strtotime($to))?>'
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
    series: JSON.parse('<?=json_encode(array_values($arr_new))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Lomat ja poissaolot -->

<!-- Uudet ja lopettaneet asiakkaat kuukausittain KPL -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_uudet_lopettaneet'): ?>
<?php
	// <-- Uudet
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
       	$criteria->select = "
		COUNT(*) as count, t.*
	";
        $criteria->condition = " 
		DATE(time) BETWEEN '".$from."' AND '".$to."'
	";
	$asiakkaat = Asiakkaat::model()->findAll($criteria);
	$arr_uudet = array();
	foreach($asiakkaat as $item){
		$arr_uudet[date("Ym", strtotime($item->time))] = (int)$item->count;
	}
	//     Uudet -->

	// <-- Lopettaneet
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d')) ";
       	$criteria->select = "
		COUNT(*) as count, t.*
	";
        $criteria->condition = " 
		lopetuksen_pvm!=''
		AND DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
	";
	$asiakkaat = Asiakkaat::model()->findAll($criteria);
	$arr_lop = array();
	foreach($asiakkaat as $item){
		$arr_lop[date("Ym", strtotime($item->lopetuksen_pvm))] = (int)$item->count;
	}
	//     Lopettaneet -->

	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_uudet = array();
	$data_lop = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
		if(isset($arr_uudet[$dt->format( "Ym" )])){
			$data_uudet[$dt->format( "Ym" )] = $arr_uudet[$dt->format( "Ym" )];
		} else {
			$data_uudet[$dt->format( "Ym" )] = 0;
		}

		if(isset($arr_lop[$dt->format( "Ym" )])){
			$data_lop[$dt->format( "Ym" )] = $arr_lop[$dt->format( "Ym" )];
		} else {
			$data_lop[$dt->format( "Ym" )] = 0;
		}
	}
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Uudet ja lopettaneet asiakkaat'
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
        name: 'Uudet',
        data: JSON.parse('<?=json_encode(array_values($data_uudet))?>')
    },{
        name: 'Lopettaneet',
        data: JSON.parse('<?=json_encode(array_values($data_lop))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Uudet ja lopettaneet asiakkaat kuukausittain KPL -->

<!-- Uudet ja lopettaneet asiakkaat - Suunnitellut työvuorot -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_uudet_lopettaneet_tv'): ?>
<?php


	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_uudet = array();
	$data_lopettaneet = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];

		$criteria = new CDbCriteria();
		$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
		$criteria->condition = " 
			kohde IN ( SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
					( SELECT id FROM asiakkaat WHERE YEAR(time)='".$dt->format( "Y" )."' AND MONTH(time)='".$dt->format( "m" )."' )
			)
			AND status=3
			AND peruutettu=0
		";
		$tv = Tyovuoroot::model()->find($criteria);
		if( isset($tv->l_tunnit) ){
			$arr_uudet[$dt->format( "Ym" )] = round($this->num($tv->l_tunnit), 2);
		} else {
			$arr_uudet[$dt->format( "Ym" )] = 0;
		}
/*
		$criteria = new CDbCriteria();
		$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
		$criteria->condition = " 
			kohde IN ( SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
					( SELECT id FROM asiakkaat WHERE lopetuksen_pvm!=''
						AND DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y%m')='".$dt->format( "Ym" )."' )
			)
			AND status=3
			AND peruutettu=0
		";
		$tv = Tyovuoroot::model()->find($criteria);
		if( isset($tv->l_tunnit) ){
			$data_lopettaneet[$dt->format( "Ym" )] = round($this->num($tv->l_tunnit), 2);
		} else {
			$data_lopettaneet[$dt->format( "Ym" )] = 0;
		}
*/
	}
/*
echo '<pre>';
print_r($arr_uudet);
echo '</pre>';
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Uudet ja lopettaneet asiakkaat - Suunnitellut työvuorot'
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
        name: 'Uudet',
        data: JSON.parse('<?=json_encode(array_values($arr_uudet))?>')
    }/*,{
        name: 'Lopettaneet',
        data: JSON.parse('<?=json_encode(array_values($data_lopettaneet))?>')
    }*/],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Uudet ja lopettaneet asiakkaat - Suunnitellut työvuorot -->

<!-- Työntekijät TOP -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'tyontekijat_top'): ?>
<?php
	$result = array();
	// <-- Hyvaksytyt yritykset
	$criteria = new CDbCriteria();
       	$criteria->limit = "20";
       	$criteria->group = "tid";
       	$criteria->order = "l_tunnit DESC";
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
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1){
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2){
		$criteria->addCondition(" hyvaksytty!='' ");
	}
	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->limit = "20";
       	$criteria->group = "tid";
       	$criteria->order = "l_tunnit DESC";
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
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1){
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2){
		$criteria->addCondition(" hyvaksytty!='' ");
	}
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);
	$data_hyvaksytyt = array();
	$data_suunnitellut = array();
	$categories_yritykset = array();
	$new_arr = array();
	$arr = array();
	foreach($result as $k=>$v){
		if(isset($v->kohteet->asiakkaat->id) and !isset($arr[$v->kohteet->asiakkaat->id])){
			$new_arr[$v->l_tunnit] = $v;
		}
		if(isset($v->kohteet->asiakkaat->id)){
			$arr[$v->kohteet->asiakkaat->id] = true;
		}
	}
	ksort($new_arr);
	$i = 0;
	foreach(array_reverse($new_arr) as $item){
		$criteria = new CDbCriteria();
	       	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
	        $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND tid='".$item->tid."'
			AND status=3
			AND peruutettu=0
		";
		$suunnitellut = Tyovuoroot::model()->find($criteria);
		$st = 0;
		if(isset($suunnitellut->l_tunnit)){
			$st = $suunnitellut->l_tunnit;
		}
	  	$i++;
		$data_hyvaksytyt[$item->l_tunnit] = round($this->num($item->l_tunnit), 2);
		$data_suunnitellut[$item->l_tunnit] = round($this->num($st), 2);
		$categories_yritykset[$item->l_tunnit] = $this->etuSukunimi($item->tid);
	  if($i > 20){ break; }
	}
	//     Hyvaksytyt yritykset -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Suunnitellut ja Hyväksytyt tunnit työntekijän mukaan'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories_yritykset))?>')
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
        name: 'Suunnitellut',
        data: JSON.parse('<?=json_encode(array_values($data_suunnitellut))?>')
    },{
        name: '<?=(isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 1)?"Hyväksyntä":""?><?=(isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 2)?"Hyväksytyt":""?>',
        data: JSON.parse('<?=json_encode(array_values($data_hyvaksytyt))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Työntekijät TOP -->

<!-- Asiakkaat TOP -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_top'): ?>
<?php
	$result = array();
	// <-- Hyvaksytyt yritykset
	$criteria = new CDbCriteria();
       	$criteria->limit = "20";
       	$criteria->group = "asiakas";
       	$criteria->order = "l_tunnit DESC";
       	$criteria->select = "
		(SELECT id FROM asiakkaat WHERE id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id=t.kohdenID)) as asiakas,
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
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1){
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2){
		$criteria->addCondition(" hyvaksytty!='' ");
	}
	$lu = Mobile::model()->findAll($criteria);

	$criteria = new CDbCriteria();
       	$criteria->limit = "20";
       	$criteria->group = "asiakas";
       	$criteria->order = "l_tunnit DESC";
       	$criteria->select = "
		(SELECT id FROM asiakkaat WHERE id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id=t.kohdenID)) as asiakas,
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
        $criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
	";
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1){
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if(isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2){
		$criteria->addCondition(" hyvaksytty!='' ");
	}
	$tot = Toteutuneet::model()->findAll($criteria);
	$result = array_merge($lu, $tot);
	$data_hyvaksytyt = array();
	$data_suunnitellut = array();
	$categories_yritykset = array();
	$new_arr = array();
	$arr = array();
	foreach($result as $k=>$v){
		if(isset($v->kohteet->asiakkaat->id) and !isset($arr[$v->kohteet->asiakkaat->id])){
			$new_arr[$v->l_tunnit] = $v;
		}
		if(isset($v->kohteet->asiakkaat->id)){
			$arr[$v->kohteet->asiakkaat->id] = true;
		}
	}
	ksort($new_arr);
	$i = 0;
	foreach(array_reverse($new_arr) as $item){
	  if(isset($item->kohteet->asiakkaat->id)){

		$criteria = new CDbCriteria();
	       	$criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
	        $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
			AND kohde IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='".$item->kohteet->asiakkaat->id."')
			AND status=3
			AND peruutettu=0
		";
		$suunnitellut = Tyovuoroot::model()->find($criteria);
		$st = 0;
		if(isset($suunnitellut->l_tunnit)){
			$st = $suunnitellut->l_tunnit;
		}
	  	$i++;
		$data_hyvaksytyt[$item->l_tunnit] = round($this->num($item->l_tunnit), 2);
		$data_suunnitellut[$item->l_tunnit] = round($this->num($st), 2);
		$categories_yritykset[$item->l_tunnit] = $item->kohteet->asiakkaat->Fullname;
	  }
	  if($i > 20){ break; }
	}
	//     Hyvaksytyt yritykset -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Suunnitellut ja Hyväksytyt tunnit asiakkaiden mukaan'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories_yritykset))?>')
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
        name: 'Suunnitellut',
        data: JSON.parse('<?=json_encode(array_values($data_suunnitellut))?>')
    },{
        name: '<?=(isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 1)?"Hyväksyntä":""?><?=(isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 2)?"Hyväksytyt":""?>',
        data: JSON.parse('<?=json_encode(array_values($data_hyvaksytyt))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Asiakkaat TOP -->

<!-- Asiakkaat kuukausittain -->
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
        text: 'Suunniteltut, luetut ja hyväksytyt tunnit'
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
<!-- Asiakkaat kuukausittain -->

<!-- Lähetettyjen laskujen määrä ja summa -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'lahetettyjen_laskut'): ?>
<?php
	// <-- Laskut
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d')) ";
       	$criteria->select = "
		paivays, SUM(yhteensa_total_veroton) as yhteensa_total_veroton
	";
        $criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d') BETWEEN '".$from."' AND '".$to."'
		AND tilanne=1
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" as_nro IN (SELECT asiakasnumero FROM asiakkaat WHERE id='".$asiakas->id."') "); 
	}
	$lasku = Lasku::model()->findAll($criteria);
	$data_kpl = array();
	$data_summa = array();
	$categories = array();
	foreach($lasku as $item){
		$lasku_count = 0;
		$criteria = new CDbCriteria();
		$criteria->select = "COUNT(*) as count";
	        $criteria->condition = " 
			YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".date("Y", strtotime($item->paivays))."' 
			AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".date("m", strtotime($item->paivays))."' 
		";
		if( isset($asiakas->id) ){
			$criteria->addCondition(" as_nro IN (SELECT asiakasnumero FROM asiakkaat WHERE id='".$asiakas->id."') "); 
		}
		$lasku_count = Lasku::model()->find($criteria);

		$data_kpl[date("Ym", strtotime($item->paivays))] = (isset($lasku_count->count))?(int)$lasku_count->count:0;
		$data_summa[date("Ym", strtotime($item->paivays))] = (int)round($item->yhteensa_total_veroton, 2);
		$categories[date("Ym", strtotime($item->paivays))] = date("Y", strtotime($item->paivays)).', '.$months[date("n", strtotime($item->paivays))];
	}
	//     Laskut -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Lähetettyjen laskujen määrä ja summa'
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
        name: 'Määrä',
        data: JSON.parse('<?=json_encode(array_values($data_kpl))?>')
    }, 
    {
        name: 'Summa',
        data: JSON.parse('<?=json_encode(array_values($data_summa))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Lähetettyjen laskujen määrä ja summa -->

<!-- Liikevaihto arvokkaimmat asiakkaat -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'asiakkaat_arvoikkaimat'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tuote = array();
	for ($i = 1; $i <= $_GET['kpl_maara']; $i++) {
		$per = array();
		foreach($period as $dt) {
			$criteria = new CDbCriteria();
		       	$criteria->group = " as_nro ";
		       	$criteria->limit = 1;
		       	$criteria->order = " SUM(yhteensa_total_veroton) DESC ";
			$criteria->select = " SUM(yhteensa_total_veroton) as yhteensa_total_veroton, as_nro";
		       	$criteria->condition = "
				YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".$dt->format( "Y" )."' 
				AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".$dt->format( "m" )."'
			";
			if( isset($tuote[$dt->format( "Ym" )]) ){
				foreach($tuote[$dt->format( "Ym" )] as $k=>$v){
					foreach($v as $k1=>$v1){
		       				$criteria->addcondition (" as_nro!='".$v1."' ");
					}
				}
			}
			$lasku = Lasku::model()->findAll($criteria);
			foreach($lasku as $item){
					if( isset($item->asiakkaat->tyyppi) and $item->asiakkaat->tyyppi == 'yritys' ){ 
						$asiakas = $item->asiakkaat->yrityksen_nimi; 
					}
					if( isset($item->asiakkaat->tyyppi) and $item->asiakkaat->tyyppi == 'henkilo' ){ 
						$asiakas = $item->asiakkaat->yhteyshenkilo; 
					}
					$per[] = array("name" => $this->clean($asiakas), "y" => (int)$item->yhteensa_total_veroton);
					$tuote[$dt->format( "Ym" )][$item->as_nro][] = $item->as_nro;
			}

		}
			$data[] = array("data" => array_values($per), "name" => "");

	}

/*
echo '<pre>';
print_r(json_encode($data));
echo '</pre>';
//exit;
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Liikevaihto arvokkaimmat asiakkaat'
    },
    subtitle: {
        text: '<?=(isset($_GET["yrityksen_nimi"]) and !empty($_GET["yrityksen_nimi"]))? $_GET["yrityksen_nimi"] : "Kaikki asiakkaat"?>'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
            stackLabels: {
                enabled: true,
                align: 'center',
		text: 'Euro',
            }
    },
    plotOptions: {
            column: {
                stacking: 'normal',
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
    },
    tooltip: {
       	split: false,
        shared: true,
        pointFormatter: function() {
		return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + Highcharts.numberFormat(this.y, 2, ",", ".") + "</b><br/>";
        }
    }, 
    series: JSON.parse('<?=json_encode(array_values($data))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Liikevaihto arvokkaimmat asiakkaat -->

<!-- Eniten Suunniteltu työvuoro - Kestot -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'eniten_tyovuoro_kestot'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tuote = array();
	for ($i = 1; $i <= $_GET['kpl_maara']; $i++) {
		$per = array();
		foreach($period as $dt) {
			$criteria = new CDbCriteria();
		       	$criteria->group = " kohde ";
		       	$criteria->limit = 1;
		       	$criteria->order = " SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) DESC ";
			$criteria->select = " SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit, osoite, kohde";
		       	$criteria->condition = "
				kohde!=0
				AND YEAR(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$dt->format( "Y" )."' 
				AND MONTH(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$dt->format( "m" )."'
				AND status=3
				AND peruutettu=0
			";
			if( isset($tuote[$dt->format( "Ym" )]) ){
				foreach($tuote[$dt->format( "Ym" )] as $k=>$v){
					foreach($v as $k1=>$v1){
		       				$criteria->addcondition (" kohde!='".$v1."' ");
					}
				}
			}
			$lasku = Tyovuoroot::model()->findAll($criteria);
			foreach($lasku as $item){
					$osoite = '';
					if( !empty($item->osoite)){ 
						$osoite = $item->osoite; 
					} elseif( isset($item->kohteet->osoite) ){
						$osoite = $item->kohteet->osoite; 
					}
					$per[] = array("name" => $this->clean($osoite), "y" => round((int)$item->l_tunnit/3600), 2);
					$tuote[$dt->format( "Ym" )][$item->kohde][] = $item->kohde;
			}

		}
			$data[] = array("data" => array_values($per), "name" => "");

	}

/*
echo '<pre>';
print_r($data);
echo '</pre>';
//exit;
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Eniten Suunniteltu työvuoro - Kestot'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
            stackLabels: {
                enabled: true,
                align: 'center',
		text: 'Kesto',
            }
    },
    plotOptions: {
            column: {
                stacking: 'normal',
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
    },
    tooltip: {
       	split: false,
        shared: true,
        pointFormatter: function() {
		return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + Highcharts.numberFormat(this.y, 2, ",", ".") + "</b><br/>";
        }
    }, 
    series: JSON.parse('<?=json_encode(array_values($data))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Eniten Suunniteltu työvuoro - Kestot-->

<!-- Eniten Suunniteltu työvuoro - KPL -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'eniten_tyovuoro_kpl'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tuote = array();
	for ($i = 1; $i <= $_GET['kpl_maara']; $i++) {
		$per = array();
		foreach($period as $dt) {
			$criteria = new CDbCriteria();
		       	$criteria->group = " kohde ";
		       	$criteria->limit = 1;
		       	$criteria->order = " COUNT(kohde) DESC ";
			$criteria->select = " COUNT(kohde) as l_tunnit, osoite, kohde";
		       	$criteria->condition = "
				kohde!=0
				AND YEAR(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$dt->format( "Y" )."' 
				AND MONTH(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='".$dt->format( "m" )."'
				AND status=3
				AND peruutettu=0
			";
			if( isset($tuote[$dt->format( "Ym" )]) ){
				foreach($tuote[$dt->format( "Ym" )] as $k=>$v){
					foreach($v as $k1=>$v1){
		       				$criteria->addcondition (" kohde!='".$v1."' ");
					}
				}
			}
			$lasku = Tyovuoroot::model()->findAll($criteria);
			foreach($lasku as $item){
					$osoite = '';
					if( !empty($item->osoite)){ 
						$osoite = $item->osoite; 
					} elseif( isset($item->kohteet->osoite) ){
						$osoite = $item->kohteet->osoite; 
					}
					$per[] = array("name" => $this->clean($osoite), "y" => (int)$item->l_tunnit);
					$tuote[$dt->format( "Ym" )][$item->kohde][] = $item->kohde;
			}

		}
			$data[] = array("data" => array_values($per), "name" => "");

	}

/*
echo '<pre>';
print_r($data);
echo '</pre>';
//exit;
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Eniten Suunniteltu työvuoro - KPL'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
            title: {
            	text: 'KPL'
	    },
            stackLabels: {
                enabled: true,
                align: 'center',
		text: 'KPL',
            }
    },
    plotOptions: {
            column: {
                stacking: 'normal',
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
    },
    tooltip: {
       	split: false,
        shared: true,
        pointFormatter: function() {
		return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + this.y + " kpl</b><br/>";
        }
    }, 
    series: JSON.parse('<?=json_encode(array_values($data))?>'),
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>
<!-- Eniten Suunniteltu työvuoro - KPL-->

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

<?php if(isset($_GET['haku']) and $_GET['haku'] == 'onlinevaraukset'): ?>
<?php
	// <-- Onlinevaraus
	$criteria = new CDbCriteria();
       	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
       	$criteria->select = "
		SUM(hinta) as hinta, t.*
	";
        $criteria->condition = " 
		DATE(time) BETWEEN '".$from."' AND '".$to."'
	";
	if( isset($asiakas->id) ){
	$criteria->addCondition(" asiakas_id ='".$asiakas->id."' "); 
	}
	$onlinevaraus = Onlinevaraus::model()->findAll($criteria);
	$data_onlinevaraus = array();
	$categories = array();
	foreach($onlinevaraus as $item){
		$data_onlinevaraus[] = round((float)$item->hinta, 2);
		$categories[date("Ym", strtotime($item->time))] = date("Y", strtotime($item->time)).', '.$months[date("n", strtotime($item->time))];
	}
	//     Onlinevaraus -->
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: '<?=$from?> - <?=$to?>'
    },
    subtitle: {
        text: 'Onlinevaraukset'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
        title: {
            text: 'Liikevaihto'
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
        name: 'Onlinevaraukset',
        data: JSON.parse('<?=json_encode(array_values($data_onlinevaraus))?>')
    }],
    exporting: {
        enabled: true
    }
});
</script>
<?php endif; ?>

<!-- Enitent Tuotteet ja palvelut Euro -->
<?php if(isset($_GET['haku']) and $_GET['haku'] == 'eniten_tuotteet_per_laskurivi_euro'): ?>
<?php
	$categories = array();
	$begin = new DateTime( date("Y-m-d", strtotime($from)) );
	$end = new DateTime( date("Y-m-d", strtotime($to)) );
	$end = $end->modify( '+1 month' );
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data = array();
	foreach($period as $dt) {
		$categories[$dt->format( "Ym" )] = $dt->format( "Y" ).', '.$months[$dt->format( "n" )];
	}

	$tuote = array();
	for ($i = 1; $i <= 10; $i++) {
		$per = array();
		foreach($period as $dt) {
			$criteria = new CDbCriteria();
		       	$criteria->with = array('tuotteet_palvelut');
		       	$criteria->group = " tuoteID ";
		       	$criteria->limit = " 1 ";
		       	$criteria->order = " SUM(veroton) DESC ";
			$criteria->select = " SUM(veroton) as veroton, tuoteID";
		       	$criteria->condition = "
				tuoteID!=0
				AND lid IN( SELECT id FROM laskut WHERE tilanne!=999 
						AND YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".$dt->format( "Y" )."' 
						AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='".$dt->format( "m" )."' 
				)
			";
			if( isset($tuote[$dt->format( "Ym" )]) ){
				foreach($tuote[$dt->format( "Ym" )] as $k=>$v){
					foreach($v as $k1=>$v1){
		       				$criteria->addcondition (" tuoteID!='".$v1."' ");
					}
				}
			}
			$laskur = LaskunRivit::model()->findAll($criteria);
			foreach($laskur as $item){
					$per[] = array(
						"name" => ( isset($item->tuotteet_palvelut->nimike) )?$item->tuotteet_palvelut->nimike:'', 
						"y" => (int)$item->veroton
					);
					$tuote[$dt->format( "Ym" )][$item->tuoteID][] = $item->tuoteID;
			}

		}
			$data[] = array("data" => array_values($per), "name" => "");

	}




/*
echo '<pre>';
print_r($data);
echo '</pre>';
//exit;
*/
?>

<script>
Highcharts.chart('container', {
    chart: {
        type: '<?=(isset($_GET["chart_tyyppi"]))?$_GET["chart_tyyppi"]:"line"?>'
    },
    title: {
        text: 'Eniten Tuotteet ja palvelut Euro'
    },
    xAxis: {
        categories: JSON.parse('<?=json_encode(array_values($categories))?>')
    },
    yAxis: {
	    title: {
		text: 'Euro'
	    },
            stackLabels: {
                enabled: true,
                align: 'center',
		text: 'Euro',
            }
    },
    plotOptions: {
            column: {
                stacking: 'normal',
                pointPadding: 0,
                groupPadding: 0,
                dataLabels: {
                    enabled: true,
                    color: 'white'
                }
            }
    },
    tooltip: {
       	split: false,
        shared: true,
        pointFormatter: function() {
		return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + this.y + " euro</b><br/>";
        }
    }, 
    series: JSON.parse('<?=json_encode(array_values($data))?>'),
    exporting: {
        enabled: true
    }
});
//    series: JSON.parse('<?=json_encode($data)?>'),
</script>
<?php endif; ?>
<!-- Enitent Tuotteet ja palvelut euro -->
