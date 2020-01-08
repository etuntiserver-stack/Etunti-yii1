<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<style>
  #toggle-chart {
    position: absolute;
    z-index: 999;
    background-color:whitesmoke;
    border: 1px solid #ddd;
    padding: 2px 8px;
  }
  #toggle-chart-save {
    margin: 12px 0px 6px;
  }
  .toggle.custom {
    margin: 4px 0px;
  }
  .checkbox-inline, .radio-inline {
    padding-left: 0px;
  }
  .chart-container {
    height: 350px;
  }
</style>

<!------------------------------------------------------------------------------
-- Yläpalkki
------------------------------------------------------------------------------->

<button class="btn-primary" data-toggle="collapse" data-target="#toggle-chart" aria-expanded="false" aria-controls="toggle-chart">
  <b>Valitse näytetyt kaaviot</b>
</button>

<div style="position:relative">
  <div id="toggle-chart" class="collapse">
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
          <button type="submit" id="toggle-chart-save" class="btn btn-primary btn-sm">Tallenna</button>
        </div>
      </div>
    </form>
  </div>
</div>

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
-- Kaaviot
------------------------------------------------------------------------------->
<div class="row">
  <div class="col-sm-6">
    <div class="chart-container" id="container_tyovuorojen_maara"></div>
  </div>
  <div class="col-sm-6">
    <div class="chart-container" id="container_lomat_ja_poissaolot"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="chart-container" id="container_uudet_lopettaneet_asiakkaat_kpl"></div>
  </div>
  <div class="col-sm-6">
    <div class="chart-container" id="container_uudet_lopettaneet_asiakkaat_tuovuorot"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="chart-container" id="container_tyontekijat_eniten_tunteja"></div>
  </div>
  <div class="col-sm-6">
    <div class="chart-container" id="container_asiakkaat_eniten_tunteja"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="chart-container" id="container_tunnit"></div>
  </div>
  <div class="col-sm-6">
    <div class="chart-container" id="container_eniten_suunniteltu_kestot"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="chart-container" id="container_eniten_suunniteltu_kpl"></div>
  </div>
  <div class="col-sm-6">
    <div class="chart-container" id="container_lahetetut_laskut_maara_summa"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="chart-container" id="container_liikevaihto_arvokkaimmat_asiakkaat"></div>
  </div>
  <div class="col-sm-6">
    <div class="chart-container" id="container_tyontekijat"></div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
    <div class="chart-container" id="container_onlinevaraukset"></div>
  </div>
  <div class="col-sm-6">
    <div class="chart-container" id="container_eniten_tuotteet_palvelut_euro"></div>
  </div>
</div>
<?php

$from = '01.12.2018';
$to = '30.11.2019';
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

$tv = Yii::app()->createController('Tyovuoroot')[0];
$categories = array();
$begin = new DateTime(date("Y-m-d", strtotime($from)));
$end = new DateTime(date("Y-m-d", strtotime($to)));
$end = $end->modify('+1 month');
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
$data_arr = array();
foreach ($period as $dt) {
  $categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];
}

$criteria = new CDbCriteria();
$criteria->order = " COUNT(status) DESC ";
$criteria->group = " status ";
$criteria->select = "
    COUNT(*) as count, t.*
  ";
$criteria->condition = " 
    DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
    AND status!=''
  ";
$asiakkaat = Tyovuoroot::model()->findAll($criteria);

$arr_new = array();
$arr = array();
$i = 0;
foreach ($asiakkaat as $v) {
  $i++;
  $arr_new[$i] = array('name' => $tv->tilanteet()[$v->status]);
  $arr_new[$i]['data'] = array();
  foreach ($categories as $k_cat => $item_cat) {

    $criteria = new CDbCriteria();
    $criteria->select = "
        COUNT(status) as count
      ";
    $criteria->condition = " 
        DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m')='" . $k_cat . "'
        AND status='" . $v->status . "'
      ";
    $asiakkaat_month = Tyovuoroot::model()->find($criteria);

    $arr_new[$i]['data'][] = (int) $asiakkaat_month->count;
  }
}
?>

<script>
  Highcharts.chart('container_tyovuorojen_maara', {
    chart: {
      type: '<?= (isset($_GET["chart_tyyppi"])) ? $_GET["chart_tyyppi"] : "line" ?>'
    },
    title: {
      text: 'Työvuorojen määrä <?= date("d.m.Y", strtotime($from)) . "-" . date("d.m.Y", strtotime($to)) ?>'
    },
    xAxis: {
      categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
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
    series: JSON.parse('<?= json_encode(array_values($arr_new)) ?>'),
    exporting: {
      enabled: true
    }
  });
</script>





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
Highcharts.chart('container_uudet_lopettaneet_asiakkaat_kpl', {
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


