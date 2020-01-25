<?php

// <-- Uudet
$criteria = new CDbCriteria();
$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
$criteria->select = "
		COUNT(*) as count, t.*
	";
$criteria->condition = " 
		DATE(time) BETWEEN '" . $from . "' AND '" . $to . "'
	";
$asiakkaat = Asiakkaat::model()->findAll($criteria);
$arr_uudet = array();
foreach ($asiakkaat as $item) {
  $arr_uudet[date("Ym", strtotime($item->time))] = (int) $item->count;
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
		AND DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
	";
$asiakkaat = Asiakkaat::model()->findAll($criteria);
$arr_lop = array();
foreach ($asiakkaat as $item) {
  $arr_lop[date("Ym", strtotime($item->lopetuksen_pvm))] = (int) $item->count;
}
//     Lopettaneet -->

$categories = array();
$begin = new DateTime(date("Y-m-d", strtotime($from)));
$end = new DateTime(date("Y-m-d", strtotime($to)));
$end = $end->modify('+1 month');
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
$data_uudet = array();
$data_lop = array();
foreach ($period as $dt) {
  $categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];
  if (isset($arr_uudet[$dt->format("Ym")])) {
    $data_uudet[$dt->format("Ym")] = $arr_uudet[$dt->format("Ym")];
  } else {
    $data_uudet[$dt->format("Ym")] = 0;
  }

  if (isset($arr_lop[$dt->format("Ym")])) {
    $data_lop[$dt->format("Ym")] = $arr_lop[$dt->format("Ym")];
  } else {
    $data_lop[$dt->format("Ym")] = 0;
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
    });
  });
</script>