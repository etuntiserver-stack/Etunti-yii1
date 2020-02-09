<?php

/**
 * Kaavio: Lomat ja poissaolot - Not working yet, to be fixed.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   lomat_ja_poissaolot_from       Chart start date
 *   lomat_ja_poissaolot_to         Chart end date
 *   lomat_ja_poissaolot_worker_id  Worker ID
 *   lomat_ja_poissaolot_type       Chart type
 */

$from = date('Y-m-d', isset($_POST['lomat_ja_poissaolot_from'])
  ? strtotime($_POST['lomat_ja_poissaolot_from'])
  : strtotime('-1year', time()));
$to = date('Y-m-d', isset($_POST['lomat_ja_poissaolot_to'])
  ? strtotime($_POST['lomat_ja_poissaolot_to'])
  : time());
$worker_id = $_POST['lomat_ja_poissaolot_worker_id'] ?? 0;
$chart_type = $_POST['lomat_ja_poissaolot_type'] ?? 'bar';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));

$categories = array();
$begin = new DateTime(date("Y-m-d", strtotime($from)));
$end = new DateTime(date("Y-m-d", strtotime($to)));
//$end = $end->modify('+1 month');
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
$data_arr = array();

foreach ($period as $dt)
  $categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];

$criteria = new CDbCriteria();
$criteria->order = "tyoajanlaatu";
$criteria->group = "tyoajanlaatu";
$criteria->select = "COUNT(*) as count, t.*";
$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to' AND tyoajanlaatu!=''";
if ($worker_id > 0)
  $criteria->addCondition("tid='$worker_id'");
$tv = Tyovuoroot::model()->findAll($criteria);
$arr_new = array();
$arr = array();
$i = 0;

foreach ($tv as $v) {
  $i++;
  $name_expl = explode("/", $v->tyoajanlaatu);
  $arr_new[$i] = array('name' => ((isset($name_expl[0])) ? $name_expl[0] : ''));
  $arr_new[$i]['data'] = array();

  foreach ($categories as $k_cat => $item_cat) {
    $criteria = new CDbCriteria();
    $criteria->select = "COUNT(tyoajanlaatu) as count";
    $criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m')='$k_cat' AND tyoajanlaatu='{$v->tyoajanlaatu}'";
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
        text: 'Lomat ja poissaolot <?= date("d.m.Y", strtotime($from)) . "-" . date("d.m.Y", strtotime($to)) ?>'
      },
      xAxis: {
        categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
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
      series: JSON.parse('<?= json_encode(array_values($arr_new)) ?>'),
      exporting: {
        enabled: true
      }
    }, [{
        id: 'lomat_ja_poissaolot_worker_id',
        type: 'worker_list',
        default: <?= $worker_id ?>
      },
      {
        id: 'lomat_ja_poissaolot_from',
        type: 'date',
        default: '<?= $from_formated ?>'
      },
      {
        id: 'lomat_ja_poissaolot_to',
        type: 'date',
        default: '<?= $to_formated ?>'
      },
      {
        id: 'lomat_ja_poissaolot_type',
        type: 'chart_type',
        default: '<?= $chart_type ?>'
      }
    ]);
  });
</script>