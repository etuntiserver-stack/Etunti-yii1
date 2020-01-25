<?php
// <-- Onlinevaraus
$criteria = new CDbCriteria();
$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
$criteria->select = "
		SUM(hinta) as hinta, t.*
	";
$criteria->condition = " 
		DATE(time) BETWEEN '" . $from . "' AND '" . $to . "'
	";
if (isset($asiakas->id)) {
  $criteria->addCondition(" asiakas_id ='" . $asiakas->id . "' ");
}
$onlinevaraus = Onlinevaraus::model()->findAll($criteria);
$data_onlinevaraus = array();
$categories = array();
foreach ($onlinevaraus as $item) {
  $data_onlinevaraus[] = round((float) $item->hinta, 2);
  $categories[date("Ym", strtotime($item->time))] = date("Y", strtotime($item->time)) . ', ' . $months[date("n", strtotime($item->time))];
}
//     Onlinevaraus -->
?>

<script>
  $(function() {
    add_chart_container({
      chart: {
        type: '<?= $chart_type ?>'
      },
      title: {
        text: '<?= $from ?> - <?= $to ?>'
      },
      subtitle: {
        text: 'Onlinevaraukset'
      },
      xAxis: {
        categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
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
        data: JSON.parse('<?= json_encode(array_values($data_onlinevaraus)) ?>')
      }],
      exporting: {
        enabled: true
      }
    });
  });
</script>