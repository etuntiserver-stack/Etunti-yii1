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
    $criteria->group = " kohde ";
    $criteria->limit = 1;
    $criteria->order = " COUNT(kohde) DESC ";
    $criteria->select = " COUNT(kohde) as l_tunnit, osoite, kohde";
    $criteria->condition = "
				kohde!=0
				AND YEAR(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='" . $dt->format("Y") . "' 
				AND MONTH(DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d'))='" . $dt->format("m") . "'
				AND status=3
				AND peruutettu=0
			";
    if (isset($tuote[$dt->format("Ym")])) {
      foreach ($tuote[$dt->format("Ym")] as $k => $v) {
        foreach ($v as $k1 => $v1) {
          $criteria->addcondition(" kohde!='" . $v1 . "' ");
        }
      }
    }
    $lasku = Tyovuoroot::model()->findAll($criteria);
    foreach ($lasku as $item) {
      $osoite = '';
      if (!empty($item->osoite)) {
        $osoite = $item->osoite;
      } elseif (isset($item->kohteet->osoite)) {
        $osoite = $item->kohteet->osoite;
      }
      $per[] = array("name" => $this->clean($osoite), "y" => (int) $item->l_tunnit);
      $tuote[$dt->format("Ym")][$item->kohde][] = $item->kohde;
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
        text: 'Eniten Suunniteltu työvuoro - KPL'
      },
      xAxis: {
        categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
      },
      yAxis: {
        title: {
          text: 'KPL'
        },
        stackLabels: {
          enabled: true,
          align: 'center',
          text: 'KPL',
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
          return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + this.y + " kpl</b><br/>";
        }
      },
      series: JSON.parse('<?= json_encode(array_values($data)) ?>'),
      exporting: {
        enabled: true
      }
    });
  });
</script>