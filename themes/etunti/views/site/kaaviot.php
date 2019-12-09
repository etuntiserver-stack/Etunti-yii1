<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<input type="checkbox" id="toggle-tyovuorojen-maara">
<input type="checkbox" id="toggle-lomat-ja-poissaolot">
<input type="checkbox" id="toggle-uudet-lopettaneet-asiakkaat-kpl">
<input type="checkbox" id="toggle-uudet-lopettaneet-asiakkaat-tuovuorot">
<input type="checkbox" id="toggle-tyontekijat-eniten-tunteja">
<input type="checkbox" id="toggle-asiakkaat-eniten-tunteja">
<input type="checkbox" id="toggle-tunnit">
<input type="checkbox" id="toggle-eniten-suunniteltu-kestot">
<input type="checkbox" id="toggle-eniten-suunniteltu-kpl">
<input type="checkbox" id="toggle-lahetetut-laskut-maara-summa">
<input type="checkbox" id="toggle-liikevaihto-arvokkaimmat-asiakkaat">
<input type="checkbox" id="toggle-tyontekijat">
<input type="checkbox" id="toggle-onlinevaraukset">
<input type="checkbox" id="toggle-eniten-tuotteet-palvelut-euro">
<br>

<div class="row">
  <div class="col-sm-6">
    <div id="container-tyovuorojen-maara" style="height: 350px;"></div>
  </div>
  <div class="col-sm-6">
    <div id="container-uudet-lopettaneet-asiakkaat-kpl" style="height: 350px;"></div>
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
  Highcharts.chart('container-tyovuorojen-maara', {
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
Highcharts.chart('container-uudet-lopettaneet-asiakkaat-kpl', {
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




<script>
  $(function() {
    $('#toggle-tyovuorojen-maara').bootstrapToggle({
      on: 'Työvuorojen määrä',
      off: 'Työvuorojen määrä',
      size: 'mini',
      width: 130
    });
  })
</script>