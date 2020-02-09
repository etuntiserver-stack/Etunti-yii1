<?php

/**
 * Kaavio: Työvuorojen määrä ajanjaksolla.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   tyovuorojen_maara_from  Chart start date
 *   tyovuorojen_maara_to    Chart end date
 *   tyovuorojen_maara_type  Chart type
 */

$from = date('Y-m-d', isset($_POST['tyovuorojen_maara_from'])
  ? strtotime($_POST['tyovuorojen_maara_from'])
  : strtotime('-1year', time()));
$to = date('Y-m-d', isset($_POST['tyovuorojen_maara_to'])
  ? strtotime($_POST['tyovuorojen_maara_to'])
  : time());
$chart_type = $_POST['tyovuorojen_maara_type'] ?? 'line';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));

$begin = new DateTime($from);
$end = new DateTime($to);
$end->modify('+1 month');
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
$data_arr = array();
$categories = [];
foreach ($period as $dt)
  $categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];

$criteria = new CDbCriteria();
$criteria->order = "COUNT(status) DESC";
$criteria->group = "status";
$criteria->select = "COUNT(*) as count, t.*";
$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to' AND status!=''";
$asiakkaat = Tyovuoroot::model()->findAll($criteria);
$arr_new = array();
$arr = array();
$i = 0;

foreach ($asiakkaat as $v) {
  $i++;
  $arr_new[$i] = array('name' => $tyovuoroot->tilanteet()[$v->status]);
  $arr_new[$i]['data'] = array();

  foreach ($categories as $k_cat => $item_cat) {
    $criteria = new CDbCriteria();
    $criteria->select = "COUNT(status) as count";
    $criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m')='$k_cat' AND status='{$v->status}'";
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
        text: 'Työvuorojen määrä <?= "$from_formated-$to_formated" ?>'
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
        default: '<?= $chart_type ?>'
      },
      {
        id: 'tyovuorojen_maara_from',
        type: 'date',
        default: '<?= $from_formated ?>'
      },
      {
        id: 'tyovuorojen_maara_to',
        type: 'date',
        default: '<?= $to_formated ?>'
      }
    ]);
  });
</script>