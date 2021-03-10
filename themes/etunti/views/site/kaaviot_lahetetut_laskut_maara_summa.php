<?php

/**
 * Kaavio: Lähetettyjen laskujen määrä ja summa.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   lahetetut_laskut_maara_summa_from         Chart start date
 *   lahetetut_laskut_maara_summa_to           Chart end date
 *   lahetetut_laskut_maara_summa_customer_id  Customer ID
 *   lahetetut_laskut_maara_summa_type         Chart type
 */

$from = date('Y-m-d', isset($_POST['lahetetut_laskut_maara_summa_from'])
  ? strtotime($_POST['lahetetut_laskut_maara_summa_from'])
  : strtotime('-1year', time()));
$to = date('Y-m-d', isset($_POST['lahetetut_laskut_maara_summa_to'])
  ? strtotime($_POST['lahetetut_laskut_maara_summa_to'])
  : time());
$customer_name = $_POST['lahetetut_laskut_maara_summa_customer_name'] ?? 0;
$chart_type = $_POST['lahetetut_laskut_maara_summa_type'] ?? 'line';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));
$customer = !empty($customer_name)
  ? Asiakkaat::model()->find("yrityksen_nimi='$customer_name' OR etunimi='$customer_name' OR sukunimi='$customer_name'")
  : null;

// <-- Laskut
$criteria = new CDbCriteria();
$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d')) ";
$criteria->select = "
		paivays, SUM(yhteensa_total_veroton) as yhteensa_total_veroton
	";
$criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
		AND tilanne=1
	";
if (isset($customer->id)) {
  $criteria->addCondition(" as_nro IN (SELECT asiakasnumero FROM asiakkaat WHERE id='" . $customer->id . "') ");
}
$lasku = Lasku::model()->findAll($criteria);
$data_kpl = array();
$data_summa = array();
$categories = array();
foreach ($lasku as $item) {
  $lasku_count = 0;
  $criteria = new CDbCriteria();
  $criteria->select = "COUNT(*) as count";
  $criteria->condition = " 
			YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='" . date("Y", strtotime($item->paivays)) . "' 
			AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='" . date("m", strtotime($item->paivays)) . "' 
		";
  if (isset($customer->id)) {
    $criteria->addCondition(" as_nro IN (SELECT asiakasnumero FROM asiakkaat WHERE id='" . $customer->id . "') ");
  }
  $lasku_count = Lasku::model()->find($criteria);

  $data_kpl[date("Ym", strtotime($item->paivays))] = (isset($lasku_count->count)) ? (int) $lasku_count->count : 0;
  $data_summa[date("Ym", strtotime($item->paivays))] = (int) round($item->yhteensa_total_veroton, 2);
  $categories[date("Ym", strtotime($item->paivays))] = date("Y", strtotime($item->paivays)) . ', ' . $months[date("n", strtotime($item->paivays))];
}
//     Laskut -->
?>

<script>
  $(function() {
    add_chart_container({
      chart: {
        type: '<?= $chart_type ?>'
      },
      title: {
        text: 'Lähetettyjen laskujen määrä ja summa'
      },
      subtitle: {
        text: '<?= (!empty($customer_name)) ? $customer_name : "Kaikki asiakkaat" ?>'
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
          name: 'Määrä',
          data: JSON.parse('<?= json_encode(array_values($data_kpl)) ?>')
        },
        {
          name: 'Summa',
          data: JSON.parse('<?= json_encode(array_values($data_summa)) ?>')
        }
      ],
      exporting: {
        enabled: true
      }
    }, [{
        id: 'lahetetut_laskut_maara_summa_customer_name',
        type: 'customer_auto',
        default: '<?= $customer_name ?>'
      },
      {
        id: 'lahetetut_laskut_maara_summa_from',
        type: 'date',
        default: '<?= $from_formated ?>'
      },
      {
        id: 'lahetetut_laskut_maara_summa_to',
        type: 'date',
        default: '<?= $to_formated ?>'
      },
      {
        id: 'lahetetut_laskut_maara_summa_type',
        type: 'chart_type',
        default: '<?= $chart_type ?>'
      }
    ]);
  });
</script>
