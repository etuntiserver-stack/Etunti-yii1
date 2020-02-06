<?php

/**
 * Kaavio: Uudet ja lopettaneet asiakkaat: Suunnitellut työvuorot.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   uudet_lopettaneet_asiakkaat_tyovuorot_from  Chart start date
 *   uudet_lopettaneet_asiakkaat_tyovuorot_to    Chart end date
 *   uudet_lopettaneet_asiakkaat_tyovuorot_type  Chart type
 */

$from = date('Y-m-d', isset($_POST['uudet_lopettaneet_asiakkaat_tyovuorot_from'])
  ? strtotime($_POST['uudet_lopettaneet_asiakkaat_tyovuorot_from'])
  : strtotime('-1year', time()));
$to = date('Y-m-d', isset($_POST['uudet_lopettaneet_asiakkaat_tyovuorot_to'])
  ? strtotime($_POST['uudet_lopettaneet_asiakkaat_tyovuorot_to'])
  : time());
$type = $_POST['uudet_lopettaneet_asiakkaat_tyovuorot_type'] ?? 'column';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));

$categories = array();
$begin = new DateTime(date("Y-m-d", strtotime($from)));
$end = new DateTime(date("Y-m-d", strtotime($to)));
$end = $end->modify('+1 month');
$interval = DateInterval::createFromDateString('1 month');
$period = new DatePeriod($begin, $interval, $end);
$data_uudet = array();
$data_lopettaneet = array();
foreach ($period as $dt) {
  $categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];

  $criteria = new CDbCriteria();
  $criteria->select = "
    SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
  ";
  $criteria->condition = " 
    kohde IN ( SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
        ( SELECT id FROM asiakkaat WHERE YEAR(time)='" . $dt->format("Y") . "' AND MONTH(time)='" . $dt->format("m") . "' )
    )
    AND status=3
    AND peruutettu=0
  ";
  $tv = Tyovuoroot::model()->find($criteria);
  if (isset($tv->l_tunnit)) {
    $arr_uudet[$dt->format("Ym")] = round($this->num($tv->l_tunnit), 2);
  } else {
    $arr_uudet[$dt->format("Ym")] = 0;
  }

  // $criteria = new CDbCriteria();
  // $criteria->select = "
  // 	SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
  // ";
  // $criteria->condition = " 
  // 	kohde IN ( SELECT id FROM sivex_kohdet WHERE asiakas_id IN 
  // 			( SELECT id FROM asiakkaat WHERE lopetuksen_pvm!=''
  // 				AND DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y%m')='".$dt->format( "Ym" )."' )
  // 	)
  // 	AND status=3
  // 	AND peruutettu=0
  // ";
  // $tv = Tyovuoroot::model()->find($criteria);
  // if( isset($tv->l_tunnit) ){
  // 	$data_lopettaneet[$dt->format( "Ym" )] = round($this->num($tv->l_tunnit), 2);
  // } else {
  // 	$data_lopettaneet[$dt->format( "Ym" )] = 0;
  // }
}
?>

<script>
  $(function() {
    add_chart_container({
      chart: {
        type: '<?= $chart_type ?>'
      },
      title: {
        text: 'Uudet ja lopettaneet asiakkaat - Suunnitellut työvuorot'
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
          data: JSON.parse('<?= json_encode(array_values($arr_uudet)) ?>')
        }
        /*,{
                    name: 'Lopettaneet',
                    data: JSON.parse('<?= json_encode(array_values($data_lopettaneet)) ?>')
                }*/
      ],
      exporting: {
        enabled: true
      }
    });
  });
</script>