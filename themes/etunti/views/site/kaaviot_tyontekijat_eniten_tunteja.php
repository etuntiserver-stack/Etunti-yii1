<?php

/**
 * Kaavio: Työntekijät, joilla eniten hyväksyttyjä tunteja.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   tyontekijat_eniten_tunteja_from      Chart start date
 *   tyontekijat_eniten_tunteja_to        Chart end date
 *   tyontekijat_eniten_tunteja_approved  Hyväksytyt (true) || Kaikki (false)
 *   tyontekijat_eniten_tunteja_type      Chart type
 */

$from = date('Y-m-d', isset($_POST['tyontekijat_eniten_tunteja_from'])
  ? strtotime($_POST['tyontekijat_eniten_tunteja_from'])
  : strtotime('-1month', time()));
$to = date('Y-m-d', isset($_POST['tyontekijat_eniten_tunteja_to'])
  ? strtotime($_POST['tyontekijat_eniten_tunteja_to'])
  : time());
$approved_only = ($_POST['tyontekijat_eniten_tunteja_approved'] ?? true) == true;
$chart_type = $_POST['tyontekijat_eniten_tunteja_type'] ?? 'bar';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));

$result = array();
// <-- Hyvaksytyt yritykset
$criteria = new CDbCriteria();
$criteria->limit = "20";
$criteria->group = "tid";
$criteria->order = "l_tunnit DESC";
$criteria->select = "
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
if (!$approved_only) {
  $criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
}
if ($approved_only) {
  $criteria->addCondition(" hyvaksytty!='' ");
}
$lu = Mobile::model()->findAll($criteria);

$criteria = new CDbCriteria();
$criteria->limit = "20";
$criteria->group = "tid";
$criteria->order = "l_tunnit DESC";
$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
$criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND status=3
		AND deleted=0
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
	";
if (!$approved_only) {
  $criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
}
if ($approved_only) {
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
  $criteria = new CDbCriteria();
  $criteria->select = "
			SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit
		";
  $criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
			AND tid='" . $item->tid . "'
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
  $categories_yritykset[$item->l_tunnit] = $this->etuSukunimi($item->tid);
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
        text: 'Suunnitellut ja Hyväksytyt tunnit työntekijän mukaan'
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
        name: '<?= (!$approved_only) ? "Hyväksyntä" : "" ?><?= ($approved_only) ? "Hyväksytyt" : "" ?>',
        data: JSON.parse('<?= json_encode(array_values($data_hyvaksytyt)) ?>')
      }],
      exporting: {
        enabled: true
      }
    }, [{
        id: 'tyontekijat_eniten_tunteja_from',
        type: 'date',
        default: '<?= $from_formated ?>'
      },
      {
        id: 'tyontekijat_eniten_tunteja_to',
        type: 'date',
        default: '<?= $to_formated ?>'
      },
      {
        id: 'tyontekijat_eniten_tunteja_approved_only',
        type: 'approved_only',
        default: '<?= $approved_only ?>'
      },
      {
        id: 'tyontekijat_eniten_tunteja_type',
        type: 'chart_type',
        default: '<?= $chart_type ?>'
      }
    ]);
  });
</script>