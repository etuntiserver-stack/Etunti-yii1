<?php
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
if (isset($asiakas->id)) {
  $criteria->addCondition(" as_nro IN (SELECT asiakasnumero FROM asiakkaat WHERE id='" . $asiakas->id . "') ");
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
  if (isset($asiakas->id)) {
    $criteria->addCondition(" as_nro IN (SELECT asiakasnumero FROM asiakkaat WHERE id='" . $asiakas->id . "') ");
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
        text: '<?= (isset($_GET["yrityksen_nimi"]) and !empty($_GET["yrityksen_nimi"])) ? $_GET["yrityksen_nimi"] : "Kaikki asiakkaat" ?>'
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
    });
  });
</script>