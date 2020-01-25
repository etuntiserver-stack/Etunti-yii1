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
for ($i = 1; $i <= 10; $i++) {
  $per = array();
  foreach ($period as $dt) {
    $criteria = new CDbCriteria();
    $criteria->with = array('tuotteet_palvelut');
    $criteria->group = " tuoteID ";
    $criteria->limit = " 1 ";
    $criteria->order = " SUM(veroton) DESC ";
    $criteria->select = " SUM(veroton) as veroton, tuoteID";
    $criteria->condition = "
				tuoteID!=0
				AND lid IN( SELECT id FROM laskut WHERE tilanne!=999 
						AND YEAR(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='" . $dt->format("Y") . "' 
						AND MONTH(DATE_FORMAT(STR_TO_DATE(paivays, '%Y-%m-%d'), '%Y-%m-%d'))='" . $dt->format("m") . "' 
				)
			";
    if (isset($tuote[$dt->format("Ym")])) {
      foreach ($tuote[$dt->format("Ym")] as $k => $v) {
        foreach ($v as $k1 => $v1) {
          $criteria->addcondition(" tuoteID!='" . $v1 . "' ");
        }
      }
    }
    $laskur = LaskunRivit::model()->findAll($criteria);
    foreach ($laskur as $item) {
      $per[] = array(
        "name" => (isset($item->tuotteet_palvelut->nimike)) ? $item->tuotteet_palvelut->nimike : '',
        "y" => (int) $item->veroton
      );
      $tuote[$dt->format("Ym")][$item->tuoteID][] = $item->tuoteID;
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
        text: 'Eniten Tuotteet ja palvelut Euro'
      },
      xAxis: {
        categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
      },
      yAxis: {
        title: {
          text: 'Euro'
        },
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
          return "<span style='color:{point.color}'></span> " + this.name + ": <b>" + this.y + " euro</b><br/>";
        }
      },
      series: JSON.parse('<?= json_encode(array_values($data)) ?>'),
      exporting: {
        enabled: true
      }
    });
  });
</script>