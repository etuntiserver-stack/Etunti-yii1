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

	/* Charts submit button */
	#chart-submit-btn {
		position: fixed;
		margin: 0 8px 8px 0;
		bottom: 0;
		right: 0;
		display: none;
		z-index: 999999;
		font-size: 22px;
		line-height: normal;
	}
</style>

<?php

// var_dump($_POST);
// exit;

?>

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
		<!-- Main chart options form. The chart toggle form is used on submit, so
				 that modified values are added to it as hidden inputs. This way,
				 modified values and selected charts are preserved between reloads. -->
		<form id="chart-options-form" action="#" method="POST">
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_tyovuorojen_maara'])) echo 'checked="checked"'; ?> name="toggle_tyovuorojen_maara" id="toggle_tyovuorojen_maara"> Työvuorojen määrä ajanjaksolla
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_lomat_ja_poissaolot'])) echo 'checked="checked"'; ?> name="toggle_lomat_ja_poissaolot" id="toggle_lomat_ja_poissaolot"> Lomat ja poissaolot
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_kpl'])) echo 'checked="checked"'; ?> name="toggle_uudet_lopettaneet_asiakkaat_kpl" id="toggle_uudet_lopettaneet_asiakkaat_kpl"> Uudet ja lopettaneet asiakkaat: KPL määrä
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_tuovuorot'])) echo 'checked="checked"'; ?> name="toggle_uudet_lopettaneet_asiakkaat_tuovuorot" id="toggle_uudet_lopettaneet_asiakkaat_tuovuorot"> Uudet ja lopettaneet asiakkaat: Suunnitellut työvuorot
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_tyontekijat_eniten_tunteja'])) echo 'checked="checked"'; ?> name="toggle_tyontekijat_eniten_tunteja" id="toggle_tyontekijat_eniten_tunteja"> Työntekijät, joilla eniten hyväksyttyjä tunteja
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_asiakkaat_eniten_tunteja'])) echo 'checked="checked"'; ?> name="toggle_asiakkaat_eniten_tunteja" id="toggle_asiakkaat_eniten_tunteja"> Asiakkaat, joille on tehty eniten hyväksyttyjä tunteja
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_tunnit'])) echo 'checked="checked"'; ?> name="toggle_tunnit" id="toggle_tunnit"> Suunnitellut, luetut ja hyväksytyt tunnit
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_eniten_suunniteltu_kestot'])) echo 'checked="checked"'; ?> name="toggle_eniten_suunniteltu_kestot" id="toggle_eniten_suunniteltu_kestot"> Eniten suunniteltu työvuoro: Kestot
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_eniten_suunniteltu_kpl'])) echo 'checked="checked"'; ?> name="toggle_eniten_suunniteltu_kpl" id="toggle_eniten_suunniteltu_kpl"> Eniten suunniteltu työvuoro: KPL
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_lahetetut_laskut_maara_summa'])) echo 'checked="checked"'; ?> name="toggle_lahetetut_laskut_maara_summa" id="toggle_lahetetut_laskut_maara_summa"> Lähetettyjen laskujen määrä ja summa
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_liikevaihto_arvokkaimmat_asiakkaat'])) echo 'checked="checked"'; ?> name="toggle_liikevaihto_arvokkaimmat_asiakkaat" id="toggle_liikevaihto_arvokkaimmat_asiakkaat"> Liikevaihto arvokkaimmat asiakkaat
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_tyontekijat'])) echo 'checked="checked"'; ?> name="toggle_tyontekijat" id="toggle_tyontekijat"> Työntekijät
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_onlinevaraukset'])) echo 'checked="checked"'; ?> name="toggle_onlinevaraukset" id="toggle_onlinevaraukset"> Onlinevaraukset
					</label>
				</div>
			</div>
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" <?php if (isset($_POST['toggle_eniten_tuotteet_palvelut_euro'])) echo 'checked="checked"'; ?> name="toggle_eniten_tuotteet_palvelut_euro" id="toggle_eniten_tuotteet_palvelut_euro"> Eniten tuotteet ja palvelut euro
					</label>
				</div>
			</div>
			<div class="row mt15">
				<div class="col-sm-6">
					<select class="form-control" name="row_count">
						<option value="2" <?= (isset($_POST['row_count']) && $_POST['row_count'] == '2') ? 'selected' : '' ?>>2 kaaviota rivillä</option>
						<option value="3" <?= (isset($_POST['row_count']) && $_POST['row_count'] == '3') ? 'selected' : '' ?>>3 kaaviota rivillä</option>
						<option value="4" <?= (isset($_POST['row_count']) && $_POST['row_count'] == '4') ? 'selected' : '' ?>>4 kaaviota rivillä</option>
					</select>
				</div>
				<div class="col-sm-6">
					<select class="form-control" name="selections">
						<option value="all" <?= (isset($_POST['selections']) && $_POST['selections'] == 'all') ? 'selected' : '' ?>>Kaikki valinnat näytetään</option>
						<option value="notype" <?= (isset($_POST['selections']) && $_POST['selections'] == 'notype') ? 'selected' : '' ?>>Piilota kaavion tyypin valinta</option>
						<option value="none" <?= (isset($_POST['selections']) && $_POST['selections'] == 'none') ? 'selected' : '' ?>>Piilota kaikki valinnat</option>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<button type="submit" id="toggle-menu-save" class="btn btn-primary btn-sm btn-block"><b>Tallenna</b></button>
				</div>
			</div>
		</form>
	</div>
</div>

<!-- Save button -->
<button class="btn btn-success btn-lg" id="chart-submit-btn">Tallenna</button>

<script>
	$(function() {
		// Add modified fields from $_POST to this form, so that further submits preserve these values.
		<?php foreach ($_POST as $k => $v) : ?>
			if ($('#chart-options-form [name="<?= $k ?>"]').length <= 0)
				$('#chart-options-form').append(`<input type="hidden" name="<?= $k ?>" value="<?= $v ?>" />`);
		<?php endforeach; ?>

		// changed_elements holds values modified during this refresh session.
		var changed_elements = new Object();

		// Init bootstrap-toggle
		$('#toggle-menu-container #toggle-menu form label input').each(function(index) {
			$(this).bootstrapToggle({
				on: 'Kyllä',
				off: 'Ei',
				size: 'mini',
				width: 50
			});
		});

		// Show the save button when data changes.
		$('#charts-container').delegate("input, select", "blur", function() {
			$('#chart-submit-btn').show('slow');

			// Add this value to the list of changed values, so that submit will recognize the field.
			changed_elements[$(this).attr('name')] = $(this).val();
			// alert(`Changed ${$(this).attr('name')}: ${$(this).val()}`)
		});

		// Check each changed element, adding them to the main form, or modifying existing form value.
		var add_changed_elements = function() {
			if ($.isEmptyObject(changed_elements)) return;
			$.each(changed_elements, function(k, v) {
				if ($(`#chart-options-form [name='${k}']`).length) {
					// Input field by this name already exists, modify it's value.
					$(`#chart-options-form [name='${k}']`).val(v);
					// alert(`Modified ${k}: ${v}`);
				} else {
					// Field doesn't exist; append hidden field to the form with the modified value.
					$('#chart-options-form').append(`<input type="hidden" name="${k}" value="${v}" />`);
					// alert(`Added ${k}: ${v}`);
				}
			});
		};

		// Handle save button click event (submit). The chart toggle form is used on
		// submit, so that modified values are added to it as hidden inputs. This
		// way, modified values and selected charts are preserved between reloads.
		$(document).delegate("#chart-submit-btn", "click", function() {
			add_changed_elements();
			$('#chart-options-form').submit();
		});

		$("#chart-options-form").submit(function(e) {
			e.preventDefault();
			add_changed_elements();
			var form = this; // required to not trigger endless submit loop
			form.submit();
		});
	});
</script>

<!------------------------------------------------------------------------------
-- Common
------------------------------------------------------------------------------->
<div id="charts-container"></div>

<?php

// Common controllers, to avoid unnecessary processing.
$site_controller = Yii::app()->createController('Site')[0];
$tyontekijat_controller = Yii::app()->createController('Tyontekijat')[0];

//* TODO: remove, add "if (!isset($site_controller)) $site_controller = ..." to
//* charts code. This is to further reduce creating unnecessary controllers,
//* e.g. tyontekijat_controller is only used in a few charts.

// Temporary default variables. (Copied from old code)
$chart_type = 'line'; // line | bar | column | area
$from = date("Y-m-d", strtotime(" -1 year first day of this month"));
$to = date("Y-m-d", strtotime(" last day of last month"));
$asiakas = '';
$tyontekija = '';
$kpl_maara = 10;
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

?>

<script>
	// Amount of columns (charts) on one row. This number must fit as divisor for
	// 12, creating an common column width (bootstrap): 1, 2, 3, 4, 6, 12. This way,
	// if row_cols = 4, then 12/4=3, meaning col-sm-4 is used.
	var row_cols = <?= $_POST['row_count'] ?? 2 ?>;
	if (!$.inArray(row_cols, [1, 2, 3, 4, 6, 12]))
		row_cols = 2;

	// Assign column class based on row_cols (selected or default 2) by direct
	// division. This is guaranteed to work due to the earlier $.inArray() check.
	var col_class = "col-sm-" + (12 / row_cols);

	// Array of generated chart container ids, which may be used to access all
	// existing charts or their controls in later operations. This is also used to
	// track the count of charts, to calculate position for the next chart.
	var chart_ids = [];

	/**
	 * Add a chart to the primary charts container.
	 *
	 * Charts are held in a primary container, which is managed through jQuery.
	 * The chart is automatically sized to fit half of the container, or less if
	 * row length is configured. Possible control inputs are generated, and they
	 * are managed through jQuery to preserve selected values across refreshes.
	 *
	 * @param {object} options
	 * Options object provided to HighCharts.
	 *
	 * @param {object} [inputs]
	 * Possible input fields to configure this chart:
	 * { id: 'id', type: 'type', default: 'default' }
	 *
	 * id:
	 *   Name for the input field, which is passed across sessions. This is the
	 *   name to look for in $_POST, for chart options.
	 * type:
	 *   Field type; based on selection, may contain some automatic config:
	 *     chart_type:  Chart type selection (bar, line, column, area).
	 *     date:        Date picker.
	 *     worker_list: List of workers (single selection).
	 * default:
	 *   Default value for the field, e.g. from $_POST.
	 */
	var add_chart_container = function(options, inputs = []) {
		var chart_num = chart_ids.length + 1; // 1-based chart number
		var row_num = Math.ceil(chart_num / row_cols); // row number, example when num=3, per-row=2: 3/2=1.5 => ceil(1.5)=2 => goes to row 2.
		var charts_remainder = chart_num % row_cols; // calculate remainder; if 0, this is the last item on this row.
		var col_num = (charts_remainder == 0) ? row_cols : charts_remainder; // column number (example -- 3: 3%2=1 => 1, 4: 4%2=0 => 2)
		var row_id = `charts-row-${row_num}`; // id for the row, which will already exist if this is not the first item on this row.
		var col_id = `charts-row-${row_num}-col-${col_num}`; // id for the to-be-generated column containing the chart container itself.
		var chart_id = `${col_id}-chart`; // id for the highcharts chart item.

		// Add this chart container to the list of containers for later operations.
		chart_ids.push(chart_id);

		// Check whether this is the first item on the row (x%2==1), in which case,
		// generate row div, e.g. container 3%2=1 => generate new row.
		if (charts_remainder == 1)
			$('#charts-container').append(`<div class="row p10 chart-row" id="${row_id}"></div>`);

		// Add the new column inside this row, and add another div inside it, which
		// acts as the container for the generated chart. These are separated in
		// order to later add the controls under the chart, in another container.
		$(`#${row_id}`).append(`<div class="${col_class} chart-col" id="${col_id}"></div>`);
		$(`#${col_id}`).append(`<div class="chart-container" id="${chart_id}"></div>`)

		// Print the chart to the container div with the provided options.
		Highcharts.chart(chart_id, options);

		// Process inputs (form control fields), if specified. First, check that the
		// 'selections' setting is not set to 'none' (in which case, no controls).
		if (<?= $_POST['selections'] ?? '' ?> != 'none' && inputs.length > 0) {

			// Remove any and all chart_type inputs if 'notype' is selected in the
			// chart settings menu setting 'selections'.
			<?php if ($_POST['selections'] ?? '' == 'notype') : ?>
				inputs = inputs.filter(function(obj) {
					return obj.type !== 'chart_type';
				});
			<?php endif; ?>

			// Ensure input count is between 2 and 4. If greater, set to 4 and
			// overflow inputs are not printed. If 1, then the input is printed at 50%
			// width (col-sm-6), because single input at full width seems too big.
			// This is a only temporary variable for calculating the input class.
			var inputs_count = inputs.length;
			if (inputs_count < 2)
				inputs_count = 2;
			else if (inputs_count > 4)
				inputs_count = 4;

			// Assign class for the inputs, by direct division, which is ensured to
			// work due to previous block.
			var inputs_class = "col-sm-" + (12 / inputs_count);

			var date_fields = []; // List of generated date field ids, as they need to be initialized later.
			var count = 0; // Keep count of generated inputs, to stop if this exceeds 4.

			// Wrap the inputs in 'admin-form' container, which is required for at
			// least the datepicker fields. chart-controls is the primary container.
			var content = '<div class="admin-form"><div class="row chart-controls">';

			// Loop each provided input, processing the provided id, type and default value.
			$(inputs).each(function(k, v) {

				// Limit inputs to 4, for now.
				if (count++ > 4)
					return false;

				// Dynamically generate html based on the specified field type. Expected
				// end result is containers with ${inputs_class} class, containing an
				// input field (or select, etc.) with the specified id ( ${v.id} ), with
				// the specified default value filled in/selected.
				switch (v.type) {

					// chart_type: Highcharts display type selector (line, bar, column, area).
					case 'chart_type':
						content += `
							<div class="${inputs_class}">
								<label class="field select">
									<select name="${v.id}" class="gui-input">
										<option value="line" ` + (v.default == 'line' ? 'selected' : '') + `><?php echo Yii::t('main', 'Line'); ?></option>
										<option value="bar" ` + (v.default == 'bar' ? 'selected' : '') + `><?php echo Yii::t('main', 'Bar'); ?></option>
										<option value="column" ` + (v.default == 'column' ? 'selected' : '') + `><?php echo Yii::t('main', 'Column'); ?></option>
										<option value="area" ` + (v.default == 'area' ? 'selected' : '') + `><?php echo Yii::t('main', 'Area'); ?></option>
									</select>
									<i class="arrow double"></i>
								</label>
							</div>
						`;
						break;

						// date: Date picker, using jQuery datetimepicker().
					case 'date':
						content += `
							<div class="${inputs_class}">
								<label class="field prepend-icon">
									<input type="text" name="${v.id}" id="${v.id}" class="gui-input datepickerFI" value="${v.default}">
									<label for="firstname" class="field-icon">
										<i class="glyphicon glyphicon-calendar"></i>
									</label>
								</label>
							</div>
						`;
						date_fields.push(v.id); // add this input to the list of date fields to be initalized afterwards.
						break;

						// worker_list: Single select list of workers (työntekijät).
					case 'worker_list':
						<?php
						// Get the list of workers for this selection. Do this only once per
						// page load, to avoid some obvious slowdowns.
						if (!isset($worker_list)) {
							$worker_list = [];
							$worker_list_criteria = $this->etuSukunimiCriteria(new CDbCriteria());
							$worker_list_criteria->condition = " aktiivinen=1 ";
							$worker_list_worker_ids = $tyontekijat_controller->TyoryhmatTyontekijatHelper(null);
							if (count($worker_list_worker_ids) > 0)
								$worker_list_criteria->addCondition("id IN (" . implode(",", $worker_list_worker_ids) . ")");
							foreach (Tyontekijat::model()->findAll($worker_list_criteria) as $item)
								$worker_list[$item->id] = $this->etuSukunimi($item->id);
						}
						?>
						content += `
							<div class="${inputs_class}">
								<label class="field select">
									<select name="${v.id}" class="gui-input">
										<?php foreach ($worker_list as $id => $name) : ?>
											<option value="<?= $id ?>" ` + (v.default == <?= $id ?> ? 'selected' : '') + `><?= $name ?></option>
										<?php endforeach; ?>
									</select>
									<i class="arrow double"></i>
								</label>
							</div>
						`
						break;
				}
			});

			// Close the container and the admin-form parent div.
			content += '</div></div>';

			// content is ready; append to the column, after the chart container.
			// Therefore, this content will appear under the chart.
			$(`#${col_id}`).append(content);

			// Initialize any date fields now that they have been added to the document.
			$(date_fields).each(function(k, v) {
				$(`#${v}`).datetimepicker({
					format: 'DD.MM.YYYY',
					locale: 'fi',
				});
			});
		}
	};
</script>

<!------------------------------------------------------------------------------
-- Työvuorojen määrä ajanjaksolla
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyovuorojen_maara'])) : ?>

	<?php

	$tyovuorojen_maara_from = date("Y-m-d", strtotime($_POST['tyovuorojen_maara_from'] ?? '01.01.2019'));
	$tyovuorojen_maara_to = date("Y-m-d", strtotime($_POST['tyovuorojen_maara_to'] ?? '31.12.2019'));
	$tyovuorojen_maara_type = $_POST['tyovuorojen_maara_type'] ?? 'line';
	$tyovuorojen_maara_from_formated = date("d.m.Y", strtotime($tyovuorojen_maara_from));
	$tyovuorojen_maara_to_formated = date("d.m.Y", strtotime($tyovuorojen_maara_to));

	$tyovuoroot = Yii::app()->createController('Tyovuoroot');
	$categories = array();
	$begin = new DateTime(date("Y-m-d", strtotime($tyovuorojen_maara_from)));
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
	$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$tyovuorojen_maara_from' AND '$tyovuorojen_maara_to' AND status!=''";
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
					text: 'Työvuorojen määrä <?= "$tyovuorojen_maara_from_formated-$tyovuorojen_maara_to_formated" ?>'
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
			}, [{
					id: 'tyovuorojen_maara_type',
					type: 'chart_type',
					default: '<?= $tyovuorojen_maara_type ?>'
				},
				{
					id: 'tyovuorojen_maara_from',
					type: 'date',
					default: '<?= $tyovuorojen_maara_from_formated ?>'
				},
				{
					id: 'tyovuorojen_maara_to',
					type: 'date',
					default: '<?= $tyovuorojen_maara_to_formated ?>'
				}
			]);
		});
	</script>
<?php endif; ?>

<!------------------------------------------------------------------------------
-- Lomat ja poissaolot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_lomat_ja_poissaolot'])) : ?>

	<?php

	$lomat_ja_poissaolot_from = date("Y-m-d", strtotime($_POST['lomat_ja_poissaolot_from'] ?? '01.01.2019'));
	$lomat_ja_poissaolot_to = date("Y-m-d", strtotime($_POST['lomat_ja_poissaolot_to'] ?? '31.12.2019'));
	$lomat_ja_poissaolot_type = $_POST['lomat_ja_poissaolot_type'] ?? 'line';
	$lomat_ja_poissaolot_from_formated = date("d.m.Y", strtotime($lomat_ja_poissaolot_from));
	$lomat_ja_poissaolot_to_formated = date("d.m.Y", strtotime($lomat_ja_poissaolot_to));
	$lomat_ja_poissaolot_worker = $_POST['lomat_ja_poissaolot_worker'] ?? 0;

	$categories = array();
	$begin = new DateTime(date("Y-m-d", strtotime($lomat_ja_poissaolot_from)));
	$end = new DateTime(date("Y-m-d", strtotime($lomat_ja_poissaolot_to)));
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
	$criteria->condition = "DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '$lomat_ja_poissaolot_from' AND '$lomat_ja_poissaolot_to' AND tyoajanlaatu!=''";
	if ($lomat_ja_poissaolot_worker > 0)
		$criteria->addCondition("tid='$lomat_ja_poissaolot_worker'");
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
					text: 'Lomat ja poissaolot <?= date("d.m.Y", strtotime($lomat_ja_poissaolot_from)) . "-" . date("d.m.Y", strtotime($lomat_ja_poissaolot_to)) ?>'
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
			}, [{
					id: 'lomat_ja_poissaolot_worker',
					type: 'worker_list',
					default: <?= $lomat_ja_poissaolot_worker ?>
				},
				{
					id: 'lomat_ja_poissaolot_type',
					type: 'chart_type',
					default: '<?= $lomat_ja_poissaolot_type ?>'
				},
				{
					id: 'lomat_ja_poissaolot_from',
					type: 'date',
					default: '<?= $lomat_ja_poissaolot_from_formated ?>'
				},
				{
					id: 'lomat_ja_poissaolot_to',
					type: 'date',
					default: '<?= $lomat_ja_poissaolot_to_formated ?>'
				}
			]);
		});
	</script>
<?php endif; ?>

<!------------------------------------------------------------------------------
-- Uudet ja lopettaneet asiakkaat: KPL määrä
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_kpl'])) : ?>

	<?php
	// <-- Uudet
	$criteria = new CDbCriteria();
	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
	$criteria->select = "
		COUNT(*) as count, t.*
	";
	$criteria->condition = " 
		DATE(time) BETWEEN '" . $from . "' AND '" . $to . "'
	";
	$asiakkaat = Asiakkaat::model()->findAll($criteria);
	$arr_uudet = array();
	foreach ($asiakkaat as $item) {
		$arr_uudet[date("Ym", strtotime($item->time))] = (int) $item->count;
	}
	//     Uudet -->

	// <-- Lopettaneet
	$criteria = new CDbCriteria();
	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d')) ";
	$criteria->select = "
		COUNT(*) as count, t.*
	";
	$criteria->condition = " 
		lopetuksen_pvm!=''
		AND DATE_FORMAT(STR_TO_DATE(lopetuksen_pvm, '%d.%m.%Y'), '%Y-%m-%d') BETWEEN '" . $from . "' AND '" . $to . "'
	";
	$asiakkaat = Asiakkaat::model()->findAll($criteria);
	$arr_lop = array();
	foreach ($asiakkaat as $item) {
		$arr_lop[date("Ym", strtotime($item->lopetuksen_pvm))] = (int) $item->count;
	}
	//     Lopettaneet -->

	$categories = array();
	$begin = new DateTime(date("Y-m-d", strtotime($from)));
	$end = new DateTime(date("Y-m-d", strtotime($to)));
	$end = $end->modify('+1 month');
	$interval = DateInterval::createFromDateString('1 month');
	$period = new DatePeriod($begin, $interval, $end);
	$data_uudet = array();
	$data_lop = array();
	foreach ($period as $dt) {
		$categories[$dt->format("Ym")] = $dt->format("Y") . ', ' . $months[$dt->format("n")];
		if (isset($arr_uudet[$dt->format("Ym")])) {
			$data_uudet[$dt->format("Ym")] = $arr_uudet[$dt->format("Ym")];
		} else {
			$data_uudet[$dt->format("Ym")] = 0;
		}

		if (isset($arr_lop[$dt->format("Ym")])) {
			$data_lop[$dt->format("Ym")] = $arr_lop[$dt->format("Ym")];
		} else {
			$data_lop[$dt->format("Ym")] = 0;
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
					text: 'Uudet ja lopettaneet asiakkaat'
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
					data: JSON.parse('<?= json_encode(array_values($data_uudet)) ?>')
				}, {
					name: 'Lopettaneet',
					data: JSON.parse('<?= json_encode(array_values($data_lop)) ?>')
				}],
				exporting: {
					enabled: true
				}
			});
		});
	</script>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Uudet ja lopettaneet asiakkaat: Suunnitellut työvuorot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_uudet_lopettaneet_asiakkaat_tuovuorot'])) : ?>

	<?php

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

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Työntekijät, joilla eniten hyväksyttyjä tunteja
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyontekijat_eniten_tunteja'])) : ?>

	<?php
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
	if (isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 1) {
		$criteria->addCondition(" hyvaksytty='' OR hyvaksytty!='' ");
	}
	if (isset($_GET['hyvaksynta']) and $_GET['hyvaksynta'] == 2) {
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
					name: '<?= (isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 1) ? "Hyväksyntä" : "" ?><?= (isset($_GET["hyvaksynta"]) and $_GET["hyvaksynta"] == 2) ? "Hyväksytyt" : "" ?>',
					data: JSON.parse('<?= json_encode(array_values($data_hyvaksytyt)) ?>')
				}],
				exporting: {
					enabled: true
				}
			});
		});
	</script>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Asiakkaat, joille on tehty eniten hyväksyttyjä tunteja
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_asiakkaat_eniten_tunteja'])) : ?>

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

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Suunnitellut, luetut ja hyväksytyt tunnit
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tunnit'])) : ?>

	<?php
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
			});
		});
	</script>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten suunniteltu työvuoro: Kestot
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_suunniteltu_kestot'])) : ?>

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
			$criteria->order = " SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) DESC ";
			$criteria->select = " SUM(TIME_TO_SEC(TIMEDIFF(loppu, alku))) as l_tunnit, osoite, kohde";
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
				$per[] = array("name" => $this->clean($osoite), "y" => round((int) $item->l_tunnit / 3600), 2);
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
					text: 'Eniten Suunniteltu työvuoro - Kestot'
				},
				xAxis: {
					categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
				},
				yAxis: {
					stackLabels: {
						enabled: true,
						align: 'center',
						text: 'Kesto',
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

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten suunniteltu työvuoro: KPL
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_suunniteltu_kpl'])) : ?>

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

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Lähetettyjen laskujen määrä ja summa
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_lahetetut_laskut_maara_summa'])) : ?>

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

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Liikevaihto arvokkaimmat asiakkaat
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_liikevaihto_arvokkaimmat_asiakkaat'])) : ?>

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

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Työntekijät
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_tyontekijat'])) : ?>

	<?php
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
	if (isset($_GET['tyontekija'])) {
		$criteria->addCondition(" tid='" . $_GET['tyontekija'] . "' ");
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
	if (isset($_GET['tyontekija'])) {
		$criteria->addCondition(" tid='" . $_GET['tyontekija'] . "' ");
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
	if (isset($_GET['tyontekija'])) {
		$criteria->addCondition(" tid='" . $_GET['tyontekija'] . "' ");
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
	if (isset($_GET['tyontekija'])) {
		$criteria->addCondition(" tid='" . $_GET['tyontekija'] . "' ");
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
					text: '<?= $this->etuSukunimi($tyontekija) ?>'
				},
				subtitle: {
					text: 'Luetut, hyväksytyt ja suunnitellut tunnit'
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
			});
		});
	</script>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Onlinevaraukset
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_onlinevaraukset'])) : ?>

	<?php
	// <-- Onlinevaraus
	$criteria = new CDbCriteria();
	$criteria->group = " EXTRACT(YEAR_MONTH FROM DATE(time)) ";
	$criteria->select = "
		SUM(hinta) as hinta, t.*
	";
	$criteria->condition = " 
		DATE(time) BETWEEN '" . $from . "' AND '" . $to . "'
	";
	if (isset($asiakas->id)) {
		$criteria->addCondition(" asiakas_id ='" . $asiakas->id . "' ");
	}
	$onlinevaraus = Onlinevaraus::model()->findAll($criteria);
	$data_onlinevaraus = array();
	$categories = array();
	foreach ($onlinevaraus as $item) {
		$data_onlinevaraus[] = round((float) $item->hinta, 2);
		$categories[date("Ym", strtotime($item->time))] = date("Y", strtotime($item->time)) . ', ' . $months[date("n", strtotime($item->time))];
	}
	//     Onlinevaraus -->
	?>

	<script>
		$(function() {
			add_chart_container({
				chart: {
					type: '<?= $chart_type ?>'
				},
				title: {
					text: '<?= $from ?> - <?= $to ?>'
				},
				subtitle: {
					text: 'Onlinevaraukset'
				},
				xAxis: {
					categories: JSON.parse('<?= json_encode(array_values($categories)) ?>')
				},
				yAxis: {
					title: {
						text: 'Liikevaihto'
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
					name: 'Onlinevaraukset',
					data: JSON.parse('<?= json_encode(array_values($data_onlinevaraus)) ?>')
				}],
				exporting: {
					enabled: true
				}
			});
		});
	</script>

<?php endif; ?>

<!------------------------------------------------------------------------------
-- Eniten tuotteet ja palvelut euro
------------------------------------------------------------------------------->
<?php if (isset($_POST['toggle_eniten_tuotteet_palvelut_euro'])) : ?>

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
<?php endif; ?>