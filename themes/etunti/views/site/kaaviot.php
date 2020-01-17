<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script src="https://code.highcharts.com/highcharts.src.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<style>
	/* Container div for the chart toggle menu. */
	#toggle-menu-container {
		position: relative;
	}

	/* Chart toggle menu (hidden, collapsible). */
	#toggle-menu {
		position: absolute;
		z-index: 999;
		background-color: whitesmoke;
		border: 1px solid #ddd;
		padding: 2px 8px;
	}

	/* Save button of the toggle chart form. */
	#toggle-menu-save {
		margin: 12px 0px 6px;
	}

	/* Add margin between the automatic bootstrap-toggle checkboxes. */
	.toggle.custom {
		margin: 4px 0px;
	}

	/* Remove automatic padding from the bootstrap-toggle input containers. */
	.checkbox-inline,
	.radio-inline {
		padding-left: 0px;
	}

	/* Container div for all displayed charts. */
	.chart-container {
		height: 350px;
	}
</style>

<!------------------------------------------------------------------------------
-- Yläpalkki
------------------------------------------------------------------------------->

<!-- Toggle button -->
<button class="btn-primary" data-toggle="collapse" data-target="#toggle-menu" aria-expanded="false" aria-controls="toggle-menu">
	<b>Valitse näytetyt kaaviot</b>
</button>

<!-- Main chart toggle menu -->
<div id="toggle-menu-container">
	<div id="toggle-menu" class="collapse">
		<form action="#" method="POST">
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_tyovuorojen_maara'])) echo 'checked="checked"'; ?> name="toggle_tyovuorojen_maara" id="toggle_tyovuorojen_maara"> Työvuorojen määrä ajanjaksolla
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_lomat_ja_poissaolot'])) echo 'checked="checked"'; ?> name="toggle_lomat_ja_poissaolot" id="toggle_lomat_ja_poissaolot"> Lomat ja poissaolot
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_kpl'])) echo 'checked="checked"'; ?> name="toggle_uudet_lopettaneet_asiakkaat_kpl" id="toggle_uudet_lopettaneet_asiakkaat_kpl"> Uudet ja lopettaneet asiakkaat: KPL määrä
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_tuovuorot'])) echo 'checked="checked"'; ?> name="toggle_uudet_lopettaneet_asiakkaat_tuovuorot" id="toggle_uudet_lopettaneet_asiakkaat_tuovuorot"> Uudet ja lopettaneet asiakkaat: Suunnitellut työvuorot
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_tyontekijat_eniten_tunteja'])) echo 'checked="checked"'; ?> name="toggle_tyontekijat_eniten_tunteja" id="toggle_tyontekijat_eniten_tunteja"> Työntekijät, joilla eniten hyväksyttyjä tunteja
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_asiakkaat_eniten_tunteja'])) echo 'checked="checked"'; ?> name="toggle_asiakkaat_eniten_tunteja" id="toggle_asiakkaat_eniten_tunteja"> Asiakkaat, joille on tehty eniten hyväksyttyjä tunteja
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_tunnit'])) echo 'checked="checked"'; ?> name="toggle_tunnit" id="toggle_tunnit"> Suunnitellut, luetut ja hyväksytyt tunnit
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_eniten_suunniteltu_kestot'])) echo 'checked="checked"'; ?> name="toggle_eniten_suunniteltu_kestot" id="toggle_eniten_suunniteltu_kestot"> Eniten suunniteltu työvuoro: Kestot
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_eniten_suunniteltu_kpl'])) echo 'checked="checked"'; ?> name="toggle_eniten_suunniteltu_kpl" id="toggle_eniten_suunniteltu_kpl"> Eniten suunniteltu työvuoro: KPL
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_lahetetut_laskut_maara_summa'])) echo 'checked="checked"'; ?> name="toggle_lahetetut_laskut_maara_summa" id="toggle_lahetetut_laskut_maara_summa"> Lähetettyjen laskujen määrä ja summa
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_liikevaihto_arvokkaimmat_asiakkaat'])) echo 'checked="checked"'; ?> name="toggle_liikevaihto_arvokkaimmat_asiakkaat" id="toggle_liikevaihto_arvokkaimmat_asiakkaat"> Liikevaihto arvokkaimmat asiakkaat
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_tyontekijat'])) echo 'checked="checked"'; ?> name="toggle_tyontekijat" id="toggle_tyontekijat"> Työntekijät
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_onlinevaraukset'])) echo 'checked="checked"'; ?> name="toggle_onlinevaraukset" id="toggle_onlinevaraukset"> Onlinevaraukset
			</label>
			<br>
			<label class="checkbox-inline">
				<input type="checkbox" data-style="custom" <?php if (isset($_POST['toggle_eniten_tuotteet_palvelut_euro'])) echo 'checked="checked"'; ?> name="toggle_eniten_tuotteet_palvelut_euro" id="toggle_eniten_tuotteet_palvelut_euro"> Eniten tuotteet ja palvelut euro
			</label>
			<br>
			<div class="row">
				<div class="col-sm-12">
					<button type="submit" id="toggle-menu-save" class="btn btn-primary btn-sm btn-block"><b>Tallenna</b></button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Init bootstrap-toggle -->
<script>
	$(function() {
		$('#toggle-menu-container #toggle-menu form label input').each(function(index) {
			$(this).bootstrapToggle({
				on: 'Kyllä',
				off: 'Ei',
				size: 'mini',
				width: 50
			});
		});
	})
</script>

<!------------------------------------------------------------------------------
-- Common
------------------------------------------------------------------------------->
<div id="charts-container"></div>

<script>
	var charts_count = 1;

	// Add chart to charts-container div.
	var add_chart_container = function(options) {
		var row_id = `charts-row-${Math.ceil(charts_count / 2)}`; // row number, e.g. row 2 for container 3 (ceil(3/2=1.5)=2).
		var col_id = `charts-col-${charts_count}`; // column mnumber.

		// Check whether to add new row div, e.g. container 3%2=1; new row.
		if (charts_count % 2 == 1)
			$('#charts-container').append(`<div class="row" id="${row_id}"></div>`);

		// Add column to current row.
		$(`#${row_id}`).append(`<div class="col-sm-6" id="${col_id}"></div>`);

		// Print chart to selected column.
		Highcharts.chart(col_id, options);
		charts_count++;
	};
</script>

<!------------------------------------------------------------------------------
-- Työvuorojen määrä ajanjaksolla
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyovuorojen_maara'])) : ?>

	<?php

	// Temporary variables, from previous code.
	$chart_type = 'line'; // line | bar | column | area
	$from = date("Y-m-d", strtotime(" -1 year first day of this month"));
	$to = date("Y-m-d", strtotime(" last day of last month"));
	$asiakas = '';

	$months = array(
		1 => Yii::t('main', 'Tammikuu'),
		2 => Yii::t('main', 'Helmikuu'),
		3 => Yii::t('main', 'Maaliskuu'),
		4 => Yii::t('main', 'Huhtikuu'),
		5 => Yii::t('main', 'Toukokuu'),
		6 => Yii::t('main', 'Kesäkuu'),
		7 => Yii::t('main', 'Heinäkuu'),
		8 => Yii::t('main', 'Elokuu'),
		9 => Yii::t('main', 'Syyskuu'),
		10 => Yii::t('main', 'Lokakuu'),
		11 => Yii::t('main', 'Marraskuu'),
		12 => Yii::t('main', 'Joulukuu')
	);

	$tyovuoroot = Yii::app()->createController('Tyovuoroot');
	$categories = array();
	$begin = new DateTime(date("Y-m-d", strtotime($from)));
	$end = new DateTime(date("Y-m-d", strtotime($to)));
	$end = $end->modify('+1 month');
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_arr = array();

	foreach ($period as $dt)
		$categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];

	$criteria = new CDbCriteria();
	$criteria->order = "COUNT(status) DESC";
	$criteria->group = "status";
	$criteria->select = "COUNT(*) as count, t.*";
	$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to' AND status!=''";
	$asiakkaat = Tyovuoroot::model()->findAll($criteria);
	$arr_new = array();
	$arr = array();
	$i = 0;

	foreach ($asiakkaat as $v) {
		$i++;
		$arr_new[$i] = array('name' => $tyovuoroot[0]->tilanteet()[$v->status]);
		$arr_new[$i]['data'] = array();

		foreach ($categories as $k_cat => $item_cat) {
			$criteria = new CDbCriteria();
			$criteria->select = "COUNT(status) as count";
			$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m')='$k_cat' AND status='$v->status'";
			$asiakkaat_month = Tyovuoroot::model()->find($criteria);
			$arr_new[$i]['data'][] = (int) $asiakkaat_month->count;
		}
	}
	?>

	<script>
		$(function() {
			add_chart_container({
				chart: {
					type: '<?= $chart_type ?>'
				},
				title: {
					text: 'Työvuorojen määrä <?= date("d.m.Y", strtotime($from)) . "-" . date("d.m.Y", strtotime($to)) ?>'
				},
				xAxis: {
					categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
				},
				yAxis: {
					title: {
						text: 'KPL'
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
				series: JSON.parse('<?= json_encode(array_values($arr_new)) ?>'),
				exporting: {
					enabled: true
				}
			});
		});
	</script>
<?php endif; ?>

<!------------------------------------------------------------------------------
-- Lomat ja poissaolot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_lomat_ja_poissaolot'])) : ?>

	<?php

	// Temporary variables, from previous code.
	$chart_type = 'line'; // line | bar | column | area
	$from = date("Y-m-d", strtotime(" -1 year first day of last month"));
	$to = date("Y-m-d", strtotime(" last day of last month"));
	$asiakas = '';

	$months = array(
		1 => Yii::t('main', 'Tammikuu'),
		2 => Yii::t('main', 'Helmikuu'),
		3 => Yii::t('main', 'Maaliskuu'),
		4 => Yii::t('main', 'Huhtikuu'),
		5 => Yii::t('main', 'Toukokuu'),
		6 => Yii::t('main', 'Kesäkuu'),
		7 => Yii::t('main', 'Heinäkuu'),
		8 => Yii::t('main', 'Elokuu'),
		9 => Yii::t('main', 'Syyskuu'),
		10 => Yii::t('main', 'Lokakuu'),
		11 => Yii::t('main', 'Marraskuu'),
		12 => Yii::t('main', 'Joulukuu')
	);

	$categories = array();
	$begin = new DateTime(date("Y-m-d", strtotime($from)));
	$end = new DateTime(date("Y-m-d", strtotime($to)));
	//$end = $end->modify('+1 month');
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_arr = array();

	foreach ($period as $dt)
		$categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];

	$criteria = new CDbCriteria();
	$criteria->order = "tyoajanlaatu";
	$criteria->group = "tyoajanlaatu";
	$criteria->select = "COUNT(*) as count, t.*";
	$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$from' AND '$to' AND tyoajanlaatu!=''";
	if (isset($_GET['tyontekija']) and $_GET['tyontekija'] !== 'kaikki' and $_GET['tyontekija'] > 0)
		$criteria->addCondition(" tid='" . $_GET['tyontekija'] . "' ");
	$tv = Tyovuoroot::model()->findAll($criteria);
	$arr_new = array();
	$arr = array();
	$i = 0;

	foreach ($tv as $v) {
		$i++;
		$name_expl = explode("/", $v->tyoajanlaatu);
		$arr_new[$i] = array('name' => ((isset($name_expl[0])) ? $name_expl[0] : ''));
		$arr_new[$i]['data'] = array();

		foreach ($categories as $k_cat => $item_cat) {
			$criteria = new CDbCriteria();
			$criteria->select = "COUNT(tyoajanlaatu) as count";
			$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y%m')='$k_cat' AND tyoajanlaatu='{$v->tyoajanlaatu}'";
			$asiakkaat_month = Tyovuoroot::model()->find($criteria);
			$arr_new[$i]['data'][] = (int) $asiakkaat_month->count;
		}
	}
	?>

	<script>
		$(function() {
			add_chart_container({
				chart: {
					type: '<?= $chart_type ?>'
				},
				title: {
					text: 'Lomat ja poissaolot <?= date("d.m.Y", strtotime($from)) . "-" . date("d.m.Y", strtotime($to)) ?>'
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
				series: JSON.parse('<?= json_encode(array_values($arr_new)) ?>'),
				exporting: {
					enabled: true
				}
			});
		});
	</script>
<?php endif; ?>

<!------------------------------------------------------------------------------
-- Uudet ja lopettaneet asiakkaat: KPL määrä
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_kpl'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Uudet ja lopettaneet asiakkaat: Suunnitellut työvuorot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_tuovuorot'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Työntekijät, joilla eniten hyväksyttyjä tunteja
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyontekijat_eniten_tunteja'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Asiakkaat, joille on tehty eniten hyväksyttyjä tunteja
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_asiakkaat_eniten_tunteja'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Suunnitellut, luetut ja hyväksytyt tunnit
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tunnit'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten suunniteltu työvuoro: Kestot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_suunniteltu_kestot'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten suunniteltu työvuoro: KPL
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_suunniteltu_kpl'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Lähetettyjen laskujen määrä ja summa
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_lahetetut_laskut_maara_summa'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Liikevaihto arvokkaimmat asiakkaat
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_liikevaihto_arvokkaimmat_asiakkaat'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Työntekijät
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyontekijat'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Onlinevaraukset
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_onlinevaraukset'])) : ?>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten tuotteet ja palvelut euro
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_tuotteet_palvelut_euro'])) : ?>

<?php endif; ?>