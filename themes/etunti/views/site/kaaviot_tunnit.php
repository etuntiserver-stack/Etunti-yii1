<?php
/**
 * Kaavio: Suunnitellut, luetut ja hyväksytyt tunnit.
 * For use in kaaviot.php (@see actionKaaviot()).
 *
 * @var $this AsiakkaatController
 *
 * Expected variables inside $_POST:
 *   tunnit_from         Chart start date
 *   tunnit_to           Chart end date
 *   tunnit_customer_id  Customer ID
 *   tunnit_type         Chart type
 */

$from = date('Y-m-d', isset($_POST['tunnit_from'])
  ? strtotime($_POST['tunnit_from'])
  : strtotime('-1year', time()));
$to = date('Y-m-d', isset($_POST['tunnit_to'])
  ? strtotime($_POST['tunnit_to'])
  : time());
$customer_id = $_POST['tunnit_customer_id'] ?? 0;
$chart_type = $_POST['tunnit_type'] ?? 'line';

$from_formated = date("d.m.Y", strtotime($from));
$to_formated = date("d.m.Y", strtotime($to));

$result = array();
// <-- Luetut
$criteria = new CDbCriteria();
$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(DATE_FORMAT(STR_TO_DATE(loppui, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i'), 
		DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y %H:%i'), '%Y-%m-%d %H:%i')))) as l_tunnit, t.*
	";
$criteria->condition = " 
		aloitan!='' AND loppui!=''
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
		AND status=3
		AND deleted=0
	";
if (isset($asiakas->id)) {
  $criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='" . $asiakas->id . "') ");
}
$luetut = Mobile::model()->findAll($criteria);
$data_luetut = array();
foreach ($luetut as $item) {
  $data_luetut[date("Ym", strtotime($item->aloitan))] = round($this->num($item->l_tunnit), 2);
}
//     Luetut -->

// <-- Suunnitellut
$criteria = new CDbCriteria();
$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y.%m.%d')) ";
$criteria->select = "
		SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit, t.*
	";
$criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
		AND status=3
		AND peruutettu=0
	";
if (isset($asiakas->id)) {
  $criteria->addCondition(" kohde IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='" . $asiakas->id . "') ");
}
$suunnitellut = Tyovuoroot::model()->findAll($criteria);
$data_suunnitellut = array();
foreach ($suunnitellut as $item) {
  $data_suunnitellut[date("Ym", strtotime($item->pvm))] = round($this->num($item->l_tunnit), 2);
}
//     Suunnitellut -->

// <-- Hyvaksytyt
$criteria = new CDbCriteria();
$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
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
if (isset($asiakas->id)) {
  $criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='" . $asiakas->id . "') ");
}
$lu = Mobile::model()->findAll($criteria);

$criteria = new CDbCriteria();
$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y.%m.%d')) ";
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
if (isset($asiakas->id)) {
  $criteria->addCondition(" kohdenID IN (SELECT id FROM sivex_kohdet WHERE asiakas_id='" . $asiakas->id . "') ");
}
$tot = Toteutuneet::model()->findAll($criteria);
$result = array_merge($lu, $tot);
$data_hyvaksytyt = array();
$categories = array();
foreach ($result as $item) {
  if (!isset($data_hyvaksytyt[date("Ym", strtotime($item->aloitan))])) {
    $data_hyvaksytyt[date("Ym", strtotime($item->aloitan))] = 0;
  }
  $data_hyvaksytyt[date("Ym", strtotime($item->aloitan))] += round($this->num($item->l_tunnit), 2);
  $categories[date("Ym", strtotime($item->aloitan))] = date("Y", strtotime($item->aloitan)) . ', ' . $months[date("n", strtotime($item->aloitan))];
}
//     Hyvaksytyt -->
?>

<script>
  $(function() {
    add_chart_container({
      chart: {
        type: '<?= $chart_type ?>'
      },
      title: {
        text: 'Suunniteltut, luetut ja hyväksytyt tunnit'
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
          name: 'Luetut',
          data: JSON.parse('<?= json_encode(array_values($data_luetut)) ?>')
        },
        {
          name: 'Hyväksytyt',
          data: JSON.parse('<?= json_encode(array_values($data_hyvaksytyt)) ?>')
        },
        {
          name: 'Suunnitellut',
          data: JSON.parse('<?= json_encode(array_values($data_suunnitellut)) ?>')
        },
      ],
      exporting: {
        enabled: true
      }
    }, [{
        id: 'tunnit_customer_id',
        type: 'customer_auto',
        default: '<?= $customer_id ?>'
      },
      {
        id: 'tunnit_from',
        type: 'date',
        default: '<?= $from_formated ?>'
      },
      {
        id: 'tunnit_to',
        type: 'date',
        default: '<?= $to_formated ?>'
      },
      {
        id: 'tunnit_type',
        type: 'chart_type',
        default: '<?= $chart_type ?>'
      }
    ]);
  });
</script>