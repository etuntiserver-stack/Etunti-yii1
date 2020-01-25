<?php

$tyovuorojen_maara_from = date("Y-m-d", strtotime($_POST['tyovuorojen_maara_from'] ?? '01.01.2019'));
$tyovuorojen_maara_to = date("Y-m-d", strtotime($_POST['tyovuorojen_maara_to'] ?? '31.12.2019'));
$tyovuorojen_maara_type = $_POST['tyovuorojen_maara_type'] ?? 'line';
$tyovuorojen_maara_from_formated = date("d.m.Y", strtotime($tyovuorojen_maara_from));
$tyovuorojen_maara_to_formated = date("d.m.Y", strtotime($tyovuorojen_maara_to));
$months = array(
	1 => Yii::t('main', 'Tammikuu'),
	2 => Yii::t('main', 'Helmikuu'),
	3 => Yii::t('main', 'Maaliskuu'),
	4 => Yii::t('main', 'Huhtikuu'),
	5 => Yii::t('main', 'Toukokuu'),
	6 => Yii::t('main', 'Kesäkuu'),
	7 => Yii::t('main', 'Heinäkuu'),
	8 => Yii::t('main', 'Elokuu'),
	9 => Yii::t('main', 'Syyskuu'),
	10 => Yii::t('main', 'Lokakuu'),
	11 => Yii::t('main', 'Marraskuu'),
	12 => Yii::t('main', 'Joulukuu')
);
$chart_type = 'line'; // line | bar | column | area

$tyovuoroot = Yii::app()->createController('Tyovuoroot');
$categories = array();
$begin = new DateTime(date("Y-m-d", strtotime($tyovuorojen_maara_from)));
$end = new DateTime(date("Y-m-d", strtotime($tyovuorojen_maara_to)));
$end = $end->modify('+1 month');
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
$data_arr = array();

foreach ($period as $dt)
  $categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];

$criteria = new CDbCriteria();
$criteria->order = "COUNT(status) DESC";
$criteria->group = "status";
$criteria->select = "COUNT(*) as count, t.*";
$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$tyovuorojen_maara_from' AND '$tyovuorojen_maara_to' AND status!=''";
$asiakkaat = Tyovuoroot::model()->findAll($criteria);
$arr_new = array();
$arr = array();
$i = 0;

foreach ($asiakkaat as $v) {
  $i++;
  $arr_new[$i] = array('name' => $tyovuoroot[0]->tilanteet()[$v->status]);
  $arr_new[$i]['data'] = array();

  foreach ($categories as $k_cat => $item_cat) {
    $criteria = new CDbCriteria();
    $criteria->select = "COUNT(status) as count";
    $criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m')='$k_cat' AND status='$v->status'";
    $asiakkaat_month = Tyovuoroot::model()->find($criteria);
    $arr_new[$i]['data'][] = (int) $asiakkaat_month->count;
  }
}
?>

<script>
  $(function() {
    add_chart_container({
      chart: {
        type: '<?= $chart_type ?>'
      },
      title: {
        text: 'Työvuorojen määrä <?= "$tyovuorojen_maara_from_formated-$tyovuorojen_maara_to_formated" ?>'
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
    }, [{
        id: 'tyovuorojen_maara_type',
        type: 'chart_type',
        default: '<?= $tyovuorojen_maara_type ?>'
      },
      {
        id: 'tyovuorojen_maara_from',
        type: 'date',
        default: '<?= $tyovuorojen_maara_from_formated ?>'
      },
      {
        id: 'tyovuorojen_maara_to',
        type: 'date',
        default: '<?= $tyovuorojen_maara_to_formated ?>'
      }
    ]);
  });
</script>