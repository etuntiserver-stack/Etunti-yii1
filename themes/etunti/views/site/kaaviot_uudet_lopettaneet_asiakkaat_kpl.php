<?php

/**
 * Kaavio: Uudet ja lopettaneet asiakkaat: KPL määrä.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   uudet_lopettaneet_asiakkaat_kpl_from  Chart start date
 *   uudet_lopettaneet_asiakkaat_kpl_to    Chart end date
 *   uudet_lopettaneet_asiakkaat_kpl_type  Chart type bar
 */

$from = date('Y-m-d', isset($_POST['uudet_lopettaneet_asiakkaat_kpl_from'])
  ? strtotime($_POST['uudet_lopettaneet_asiakkaat_kpl_from'])
  : strtotime('-1year', time()));
$to = date('Y-m-d', isset($_POST['uudet_lopettaneet_asiakkaat_kpl_to'])
  ? strtotime($_POST['uudet_lopettaneet_asiakkaat_kpl_to'])
  : time());
$chart_type = $_POST['uudet_lopettaneet_asiakkaat_kpl_type'] ?? 'bar';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));

// <-- Uudet
$criteria = new CDbCriteria();
$criteria->group = "EXTRACT(YEAR_MONTH FROM DATE(time))";
$criteria->select = "COUNT(*) as count, t.*";
$criteria->condition = "DATE(time) BETWEEN '$from' AND '$to'";
$asiakkaat = Asiakkaat::model()->findAll($criteria);
$arr_uudet = array();
foreach ($asiakkaat as $item)
  $arr_uudet[date("Ym", strtotime($item->time))] = (int) $item->count;
//     Uudet -->

// <-- Lopettaneet
$criteria = new CDbCriteria();
$criteria->group = "EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d'))";
$criteria->select = "COUNT(*) as count, t.*";
$criteria->condition = "lopetuksen_pvm!='' AND DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to'";
$asiakkaat = Asiakkaat::model()->findAll($criteria);
$arr_lop = array();
foreach ($asiakkaat as $item)
  $arr_lop[date("Ym", strtotime($item->lopetuksen_pvm))] = (int) $item->count;
//     Lopettaneet -->

$categories = array();
$begin = new DateTime($from);
$end = new DateTime($to);
$end->modify('+1 month');
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
$data_uudet = array();
$data_lop = array();
foreach ($period as $dt) {
  $dt_format = $dt->format("Ym");
  $categories[$dt_format] = $dt->format("Y") . ', ' . $months[$dt->format("n")];
  $data_uudet[$dt_format] = isset($arr_uudet[$dt_format]) ? $arr_uudet[$dt_format] : 0;
  $data_lop[$dt_format] = isset($arr_lop[$dt_format]) ? $arr_lop[$dt_format] : 0;
}
?>

<script>
  $(function() {
    add_chart_container({
      chart: {
        type: '<?= $chart_type ?>'
      },
      title: {
        text: 'Uudet ja lopettaneet asiakkaat'
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
      series: [{
        name: 'Uudet',
        data: JSON.parse('<?= json_encode(array_values($data_uudet)) ?>')
      }, {
        name: 'Lopettaneet',
        data: JSON.parse('<?= json_encode(array_values($data_lop)) ?>')
      }],
      exporting: {
        enabled: true
      }
    }, [{
        id: 'uudet_lopettaneet_asiakkaat_kpl_type',
        type: 'chart_type',
        default: '<?= $chart_type ?>'
      },
      {
        id: 'uudet_lopettaneet_asiakkaat_kpl_from',
        type: 'date',
        default: '<?= $from_formated ?>'
      },
      {
        id: 'uudet_lopettaneet_asiakkaat_kpl_to',
        type: 'date',
        default: '<?= $to_formated ?>'
      }
    ]);
  });
</script>