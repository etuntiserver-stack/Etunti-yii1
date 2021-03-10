<?php

/**
 * Kaavio: Onlinevaraukset.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   onlinevaraukset_from         Chart start date
 *   onlinevaraukset_to           Chart end date
 *   onlinevaraukset_customer_id  Customer ID
 *   onlinevaraukset_type         Chart type
 */

$from = date('Y-m-d', isset($_POST['onlinevaraukset_from'])
  ? strtotime($_POST['onlinevaraukset_from'])
  : strtotime('-1year', time()));
$to = date('Y-m-d', isset($_POST['onlinevaraukset_to'])
  ? strtotime($_POST['onlinevaraukset_to'])
  : time());
$customer_name = $_POST['onlinevaraukset_customer_name'] ?? 0;
$chart_type = $_POST['onlinevaraukset_type'] ?? 'line';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));
$customer = !empty($customer_name)
  ? Asiakkaat::model()->find("yrityksen_nimi='$customer_name' OR etunimi='$customer_name' OR sukunimi='$customer_name'")
  : null;

// <-- Onlinevaraus
$criteria = new CDbCriteria();
$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
$criteria->select = "
		SUM(hinta) as hinta, t.*
	";
$criteria->condition = " 
		DATE(time) BETWEEN '" . $from . "' AND '" . $to . "'
	";
if (isset($customer->id)) {
  $criteria->addCondition(" asiakas_id ='" . $customer->id . "' ");
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
    }, [{
        id: 'onlinevaraukset_customer_name',
        type: 'customer_auto',
        default: '<?= $customer_name ?>'
      },
      {
        id: 'onlinevaraukset_from',
        type: 'date',
        default: '<?= $from_formated ?>'
      },
      {
        id: 'onlinevaraukset_to',
        type: 'date',
        default: '<?= $to_formated ?>'
      },
      {
        id: 'onlinevaraukset_type',
        type: 'chart_type',
        default: '<?= $chart_type ?>'
      }
    ]);
  });
</script>
