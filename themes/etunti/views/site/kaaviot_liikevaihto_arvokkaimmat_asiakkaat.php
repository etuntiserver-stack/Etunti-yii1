<?php

/**
 * Kaavio: Liikevaihto arvokkaimmat asiakkaat.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   liikevaihto_arvokkaimmat_asiakkaat_from  Chart start date
 *   liikevaihto_arvokkaimmat_asiakkaat_to    Chart end date
 *   liikevaihto_arvokkaimmat_asiakkaat_type  Chart type
 */

$from = date('Y-m-d', isset($_POST['liikevaihto_arvokkaimmat_asiakkaat_from'])
  ? strtotime($_POST['liikevaihto_arvokkaimmat_asiakkaat_from'])
  : strtotime('-1year', time()));
$to = date('Y-m-d', isset($_POST['liikevaihto_arvokkaimmat_asiakkaat_to'])
  ? strtotime($_POST['liikevaihto_arvokkaimmat_asiakkaat_to'])
  : time());
$chart_type = $_POST['liikevaihto_arvokkaimmat_asiakkaat_type'] ?? 'column';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));

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
      if (isset($item->asiakkaat->tyyppi)) {
        $asiakas = $item->asiakkaat->Fullname;
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
        text: '<?= (!empty($customer_name)) ? $customer_name : "Kaikki asiakkaat" ?>'
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
    }, [{
        id: 'liikevaihto_arvokkaimmat_asiakkaat_from',
        type: 'date',
        default: '<?= $from_formated ?>'
      },
      {
        id: 'liikevaihto_arvokkaimmat_asiakkaat_to',
        type: 'date',
        default: '<?= $to_formated ?>'
      },
      {
        id: 'liikevaihto_arvokkaimmat_asiakkaat_type',
        type: 'chart_type',
        default: '<?= $chart_type ?>'
      }
    ]);
  });
</script>
