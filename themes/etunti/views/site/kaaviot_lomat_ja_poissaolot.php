<?php

$lomat_ja_poissaolot_from = date("Y-m-d", strtotime($_POST['lomat_ja_poissaolot_from'] ?? '01.01.2019'));
$lomat_ja_poissaolot_to = date("Y-m-d", strtotime($_POST['lomat_ja_poissaolot_to'] ?? '31.12.2019'));
$lomat_ja_poissaolot_type = $_POST['lomat_ja_poissaolot_type'] ?? 'line';
$lomat_ja_poissaolot_from_formated = date("d.m.Y", strtotime($lomat_ja_poissaolot_from));
$lomat_ja_poissaolot_to_formated = date("d.m.Y", strtotime($lomat_ja_poissaolot_to));
$lomat_ja_poissaolot_worker = $_POST['lomat_ja_poissaolot_worker'] ?? 0;

$categories = array();
$begin = new DateTime(date("Y-m-d", strtotime($lomat_ja_poissaolot_from)));
$end = new DateTime(date("Y-m-d", strtotime($lomat_ja_poissaolot_to)));
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
$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$lomat_ja_poissaolot_from' AND '$lomat_ja_poissaolot_to' AND tyoajanlaatu!=''";
if ($lomat_ja_poissaolot_worker > 0)
  $criteria->addCondition("tid='$lomat_ja_poissaolot_worker'");
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
        text: 'Lomat ja poissaolot <?= date("d.m.Y", strtotime($lomat_ja_poissaolot_from)) . "-" . date("d.m.Y", strtotime($lomat_ja_poissaolot_to)) ?>'
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
        id: 'lomat_ja_poissaolot_worker',
        type: 'worker_list',
        default: <?= $lomat_ja_poissaolot_worker ?>
      },
      {
        id: 'lomat_ja_poissaolot_type',
        type: 'chart_type',
        default: '<?= $lomat_ja_poissaolot_type ?>'
      },
      {
        id: 'lomat_ja_poissaolot_from',
        type: 'date',
        default: '<?= $lomat_ja_poissaolot_from_formated ?>'
      },
      {
        id: 'lomat_ja_poissaolot_to',
        type: 'date',
        default: '<?= $lomat_ja_poissaolot_to_formated ?>'
      }
    ]);
  });
</script>