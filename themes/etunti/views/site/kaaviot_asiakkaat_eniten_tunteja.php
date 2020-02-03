<?php
$result = array();
// <-- Hyvaksytyt yritykset
$criteria = new CDbCriteria();
$criteria->limit = "20";
$criteria->group = "asiakas";
$criteria->order = "l_tunnit DESC";
$criteria->select = "
		(SELECT id FROM asiakkaat WHERE id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id=t.kohdenID)) as asiakas,
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
$criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
		AND status=3
		AND deleted=0
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired)
	";
if (isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1) {
  $criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
}
if (isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2) {
  $criteria->addCondition(" hyvaksytty!='' ");
}
$lu = Mobile::model()->findAll($criteria);

$criteria = new CDbCriteria();
$criteria->limit = "20";
$criteria->group = "asiakas";
$criteria->order = "l_tunnit DESC";
$criteria->select = "
		(SELECT id FROM asiakkaat WHERE id IN(SELECT asiakas_id FROM sivex_kohdet WHERE id=t.kohdenID)) as asiakas,
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
$criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
	";
if (isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1) {
  $criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
}
if (isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2) {
  $criteria->addCondition(" hyvaksytty!='' ");
}
$tot = Toteutuneet::model()->findAll($criteria);
$result = array_merge($lu, $tot);
$data_hyvaksytyt = array();
$data_suunnitellut = array();
$categories_yritykset = array();
$new_arr = array();
$arr = array();
foreach ($result as $k => $v) {
  if (isset($v->kohteet->asiakkaat->id) and !isset($arr[$v->kohteet->asiakkaat->id])) {
    $new_arr[$v->l_tunnit] = $v;
  }
  if (isset($v->kohteet->asiakkaat->id)) {
    $arr[$v->kohteet->asiakkaat->id] = true;
  }
}
ksort($new_arr);
$i = 0;
foreach (array_reverse($new_arr) as $item) {
  if (isset($item->kohteet->asiakkaat->id)) {

    $criteria = new CDbCriteria();
    $criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
    $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
			AND kohde IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='" . $item->kohteet->asiakkaat->id . "')
			AND status=3
			AND peruutettu=0
		";
    $suunnitellut = Tyovuoroot::model()->find($criteria);
    $st = 0;
    if (isset($suunnitellut->l_tunnit)) {
      $st = $suunnitellut->l_tunnit;
    }
    $i++;
    $data_hyvaksytyt[$item->l_tunnit] = round($this->num($item->l_tunnit), 2);
    $data_suunnitellut[$item->l_tunnit] = round($this->num($st), 2);
    $categories_yritykset[$item->l_tunnit] = $item->kohteet->asiakkaat->Fullname;
  }
  if ($i > 20) {
    break;
  }
}
//     Hyvaksytyt yritykset -->
?>

<script>
  $(function() {
    add_chart_container({
      chart: {
        type: '<?= $chart_type ?>'
      },
      title: {
        text: 'Suunnitellut ja Hyväksytyt tunnit asiakkaiden mukaan'
      },
      xAxis: {
        categories: JSON.parse('<?= json_encode(array_values($categories_yritykset)) ?>')
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
        name: 'Suunnitellut',
        data: JSON.parse('<?= json_encode(array_values($data_suunnitellut)) ?>')
      }, {
        name: '<?= (isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 1) ? "Hyväksyntä" : "" ?><?= (isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 2) ? "Hyväksytyt" : "" ?>',
        data: JSON.parse('<?= json_encode(array_values($data_hyvaksytyt)) ?>')
      }],
      exporting: {
        enabled: true
      }
    });
  });
</script>