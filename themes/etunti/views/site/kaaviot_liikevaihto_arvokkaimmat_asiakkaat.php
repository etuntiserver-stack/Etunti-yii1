<?php
$categories = array();
$begin = new DateTime(date("Y-m-d", strtotime($from)));
$end = new DateTime(date("Y-m-d", strtotime($to)));
$end = $end->modify('+1 month');
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
$data = array();
foreach ($period as $dt) {
  $categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];
}

$tuote = array();
for ($i = 1; $i <= $kpl_maara; $i++) {
  $per = array();
  foreach ($period as $dt) {
    $criteria = new CDbCriteria();
    $criteria->group = " as_nro ";
    $criteria->limit = 1;
    $criteria->order = " SUM(yhteensa_total_veroton) DESC ";
    $criteria->select = " SUM(yhteensa_total_veroton) as yhteensa_total_veroton, as_nro";
    $criteria->condition = "
				YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='" . $dt->format("Y") . "' 
				AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='" . $dt->format("m") . "'
			";
    if (isset($tuote[$dt->format("Ym")])) {
      foreach ($tuote[$dt->format("Ym")] as $k => $v) {
        foreach ($v as $k1 => $v1) {
          $criteria->addcondition(" as_nro!='" . $v1 . "' ");
        }
      }
    }
    $lasku = Lasku::model()->findAll($criteria);
    foreach ($lasku as $item) {
      if (isset($item->asiakkaat->tyyppi) and $item->asiakkaat->tyyppi == 'yritys') {
        $asiakas = $item->asiakkaat->yrityksen_nimi;
      }
      if (isset($item->asiakkaat->tyyppi) and $item->asiakkaat->tyyppi == 'henkilo') {
        $asiakas = $item->asiakkaat->yhteyshenkilo;
      }
      $per[] = array("name" => $this->clean($asiakas), "y" => (int) $item->yhteensa_total_veroton);
      $tuote[$dt->format("Ym")][$item->as_nro][] = $item->as_nro;
    }
  }
  $data[] = array("data" => array_values($per), "name" => "");
}
?>

<script>
  $(function() {
    add_chart_container({
      chart: {
        type: '<?= $chart_type ?>'
      },
      title: {
        text: 'Liikevaihto arvokkaimmat asiakkaat'
      },
      subtitle: {
        text: '<?= (isset($_GET["yrityksen_nimi"]) and !empty($_GET["yrityksen_nimi"])) ? $_GET["yrityksen_nimi"] : "Kaikki asiakkaat" ?>'
      },
      xAxis: {
        categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
      },
      yAxis: {
        stackLabels: {
          enabled: true,
          align: 'center',
          text: 'Euro',
        }
      },
      plotOptions: {
        column: {
          stacking: 'normal',
          pointPadding: 0,
          groupPadding: 0,
          dataLabels: {
            enabled: true,
            color: 'white'
          }
        }
      },
      tooltip: {
        split: false,
        shared: true,
        pointFormatter: function() {
          return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + Highcharts.numberFormat(this.y, 2, ",", ".") + "</b><br/>";
        }
      },
      series: JSON.parse('<?= json_encode(array_values($data)) ?>'),
      exporting: {
        enabled: true
      }
    });
  });
</script>