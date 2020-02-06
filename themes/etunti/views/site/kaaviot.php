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

/**
 * Output chart toggles in place, using bootstrap layout (row => col-12).
 *
 * This is generally used inside the toggle popup menu, but can be used from
 * elsewhere, too.
 *
 * The function (as opposed to plain html code) serves two purposes:
 *   1. Toggles can be printed in multiple places without duplicate code, and
 *   2. Local variables, to not interfere with other code.
 *
 * Note that, if creating another form for the options, the form id should be
 * provided to the same jQuery selector as #chart-options-form, where items in
 * $_POST are appended to the form to preserve choices.
 */
function output_chart_menu()
{
	$toggles = [
		'toggle_tyovuorojen_maara' => 'Työvuorojen määrä ajanjaksolla',
		//'toggle_lomat_ja_poissaolot' => 'Lomat ja poissaolot', // Not working yet, to be fixed.
		'toggle_uudet_lopettaneet_asiakkaat_kpl' => 'Uudet ja lopettaneet asiakkaat: KPL määrä',
		'toggle_uudet_lopettaneet_asiakkaat_tyovuorot' => 'Uudet ja lopettaneet asiakkaat: Suunnitellut työvuorot',
		'toggle_tyontekijat_eniten_tunteja' => 'Työntekijät, joilla eniten hyväksyttyjä tunteja',
		'toggle_asiakkaat_eniten_tunteja' => 'Asiakkaat, joille on tehty eniten hyväksyttyjä tunteja',
		'toggle_tunnit' => 'Suunnitellut, luetut ja hyväksytyt tunnit',
		'toggle_eniten_suunniteltu_kestot' => 'Eniten suunniteltu työvuoro: Kestot',
		'toggle_eniten_suunniteltu_kpl' => 'Eniten suunniteltu työvuoro: KPL',
		'toggle_lahetetut_laskut_maara_summa' => 'Lähetettyjen laskujen määrä ja summa',
		'toggle_liikevaihto_arvokkaimmat_asiakkaat' => 'Liikevaihto arvokkaimmat asiakkaat',
		'toggle_tyontekijat' => 'Työntekijät',
		'toggle_onlinevaraukset' => 'Onlinevaraukset',
		'toggle_eniten_tuotteet_palvelut_euro' => 'Eniten tuotteet ja palvelut euro'
	];

	foreach ($toggles as $toggle_id => $label) {
		$is_checked = isset($_POST[$toggle_id]) ? 'checked="checked"' : '';
		echo <<<EOD
			<div class="row m10">
				<div class="col-sm-12">
					<label class="checkbox-inline">
						<input type="checkbox" $is_checked name="$toggle_id" id="$toggle_id"> $label
					</label>
				</div>
			</div>
		EOD;
	}
}

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

			<!-- Print chart toggles -->
			<?php output_chart_menu(); ?>

			<!-- Other selections -->
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
		});

		// Check each changed element, adding them to the main form, or modifying existing form value.
		var add_changed_elements = function() {
			if ($.isEmptyObject(changed_elements)) return;
			$.each(changed_elements, function(k, v) {
				if ($(`#chart-options-form [name='${k}']`).length) {
					// Input field by this name already exists, modify it's value.
					$(`#chart-options-form [name='${k}']`).val(v);
				} else {
					// Field doesn't exist; append hidden field to the form with the modified value.
					$('#chart-options-form').append(`<input type="hidden" name="${k}" value="${v}" />`);
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
		// alert(`num: ${chart_num}, row: ${row_num}, col: ${col_num}`);

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
		if ('<?= $_POST['selections'] ?? '' ?>' != 'none' && inputs.length > 0) {

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
						$tyontekijat_controller = Yii::app()->createController('Tyontekijat')[0];
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

<?php
/*******************************************************************************
 *? Render the views that handle the selected charts.
 *? View names are the same as toggle names, with "toggle_" => "kaaviot_".
 ******************************************************************************/
foreach ($_POST as $key => $value) {
	$exp_key = explode('_', $key, 2);
	if (!$exp_key || $exp_key[0] != 'toggle' || count($exp_key) < 2 || $value != 'on')
		continue;
	$view_name = "kaaviot_" . $exp_key[1];
	if (!$this->getViewFile($view_name)) {
		// TODO: Log, view not found; error in code.
		continue;
	}
	$this->renderPartial($view_name, [
		'chart_type' => $chart_type,
		'from' => $from,
		'to' => $to,
		'asiakas' => $asiakas,
		'tyontekija' => $tyontekija,
		'kpl_maara' => $kpl_maara,
		'months' => $months
	], false);
}
