<div class="row">

	<?php
	ini_set('memory_limit', '512M');
	set_time_limit(300);
	/* @var $this MobileController */
	/* @var $dataProvider CActiveDataProvider */

	$this->breadcrumbs = array(
		Yii::t('main', 'Tunnit'),
	);

	?>

	<!-- begin: .tray-center -->
	<div class="tray-center">
		<h2 class="myBgColors p10"> <i class="fa fa-eur"></i> <?php echo Yii::t('main', 'Palkkataulukko'); ?>

			<?php if (!isset($_GET['all_workers']) || $_GET['all_workers'] == 0) : ?>
			<?php echo ', ' . Yii::t('main', 'aktiiviset työntekijät'); ?>
			<?php echo CHtml::link(Yii::t('main', 'Näytä kaikki'), 'palkkataulukko?all_workers=1', array('class' => 'btn btn-primary')); ?>
			<?php endif; ?>

			<!-- tulostus -->
			<div class="pull-right">
				<div class="form-inline">
					<form action="tulostus" class="form-group" target="_blank" method="POST">
						<input type="hidden" name="ext" value="doc">
						<input type="hidden" name="fileName" value="Raportti">
						<input type="hidden" name="from" value="<?= $from ?>">
						<input type="hidden" name="to" value="<?= $to ?>">
						<textarea name="html_content" class="form-control" style="display:none"></textarea>
						<button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o"
								aria-hidden="true"></i></button>
					</form>
					<form action="tulostus" class="form-group" target="_blank" method="POST">
						<input type="hidden" name="ext" value="xls">
						<input type="hidden" name="fileName" value="Raportti">
						<input type="hidden" name="from" value="<?= $from ?>">
						<input type="hidden" name="to" value="<?= $to ?>">
						<textarea name="html_content" class="form-control" style="display:none"></textarea>
						<button type="submit" class="btn btn-primary myBgColors submitForm"><i
								class="fa fa-file-excel-o" aria-hidden="true"></i></button>
					</form>
					<form action="tulostus" class="form-group" target="_blank" method="POST">
						<input type="hidden" name="header" value="<?= $from ?>-<?= $to ?>">
						<input type="hidden" name="ext" value="pdf">
						<input type="hidden" name="fileName" value="Raportti">
						<input type="hidden" name="from" value="<?= $from ?>">
						<input type="hidden" name="to" value="<?= $to ?>">
						<textarea name="html_content" class="form-control" style="display:none"></textarea>
						<button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-pdf-o"
								aria-hidden="true"></i></button>
					</form>
					<button class="btn btn-primary btn-sm btn-group myBgColors" data-toggle="collapse"
						data-target="#haku"><?php echo Yii::t('main', 'Ekstrat'); ?> <b class="caret"></b></button>
				</div>
			</div>
			<!-- tulostus -->
		</h2>

		<form id="yhtveto" action="#" class="form-inline" method="GET">
			<input type="hidden" name="yhtvetoform">

			<?php
			if (isset($_GET['all_workers']) && $_GET['all_workers'] == 1) {
				echo '<input id="all_workers" type="hidden" name="all_workers" value="1">';
			} else {
				echo '<input id="all_workers" type="hidden" name="all_workers" value="0">';
			}
			?>

			<div class="admin-form">
				<div class="panel heading-border">
					<div class="panel-body bg-light">

						<!-- Input Icons -->
						<div class="row">

							<div class="col-md-2">
								<div class="section">
									<label class="field prepend-icon">

										<input type="text" name="from" id="from" class="gui-input datepickerFI"
											value="<?php echo date('d.m.Y', strtotime($from)); ?>">

										<label for="firstname" class="field-icon">
											<i class="glyphicon glyphicon-calendar"></i>
										</label>
									</label>
								</div>
							</div>

							<div class="col-md-2">
								<div class="section">
									<label class="field prepend-icon">

										<input type="text" name="to" id="to" class="gui-input datepickerFI"
											value="<?php echo date('d.m.Y', strtotime($to)); ?>">

										<label for="firstname" class="field-icon">
											<i class="glyphicon glyphicon-calendar"></i>
										</label>
									</label>
								</div>
							</div>

							<div class="col-md-2">
								<div class="section">
									<label class="field select">
										<select class="gui-input" name="lu_tai_tot" id="lu_tai_tot">
											<option value="1"
												<?= (isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 1) ? 'selected' : '' ?>>
												<?php echo Yii::t('main', 'Luetut'); ?></option>
											<option value="2"
												<?= (isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 2) ? 'selected' : '' ?>>
												<?php echo Yii::t('main', 'Hyväksyntä'); ?></option>
											<option value="3"
												<?= (isset($_GET['lu_tai_tot']) and $_GET['lu_tai_tot'] == 3) ? 'selected' : '' ?>>
												<?php echo Yii::t('main', 'Hyväksytyt'); ?></option>
										</select>
										<i class="arrow double"></i>
									</label>
									</label>
								</div>
							</div>

							<!-- Työryhmät -->
							<div class="col-md-2">
								<div class="section">
									<select class="gui-input tyoryhmatMulti" name="tyoryhmat[]" multiple
										title="Työryhmät">
										<?php

										$tyoryhmat = $tyoryhmat ?? [];
										$tyoryhmat_kaikki = ['0' => 'Työryhmättömät'];
										foreach (Valikkoot::model()->findAll("select_type='tyoryhma'") as $tr)
											$tyoryhmat_kaikki[$tr->id] = $tr->value;

										foreach ($tyoryhmat_kaikki as $id => $name) {
											$sel = (isset($tyoryhmat[$id]) ? ' selected' : '');
											echo "<option value=\"{$id}\"{$sel}>{$name}</option>";
										}

										?>
									</select>
									<i class="arrow double"></i>
									<script>
									$(function() {
										$('.tyoryhmatMulti').multiselect({
											includeSelectAllOption: true,
											nonSelectedText: '<?php echo Yii::t("main", "Työryhmät"); ?>',
											selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
											allSelectedText: '<?php echo Yii::t("main", "Kaikki työryhmät"); ?>',
											nSelectedText: '<?php echo Yii::t("main", "työryhmää valittu"); ?>',
											numberDisplayed: 1,
											buttonWidth: '100%',
											maxHeight: 300,
										});
									});
									</script>
								</div>
							</div>

							<div class="col-md-2">
								<div class="section">
									<label class="field">
										<?php
										$site = Yii::app()->createController('Site');
										$activeOnly = 1;
										if (isset($_GET['all_workers']) && $_GET['all_workers'] == 1) {
											$activeOnly = null;
										}
										$tyontekiatLista = $site[0]->tyontekiatLista(
											'Tekija', // name
											null, // class
											'tyontekijat', // id
											// pre-selected elements
											(isset(Yii::app()->session["palkkataulukko_employeeIds"]) ?
												Yii::app()->session["palkkataulukko_employeeIds"] : []),
											$activeOnly // aktiivinen
										);
										echo $tyontekiatLista;
										?>
									</label>
								</div>
							</div>

							<div class="col-md-2">
								<input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors"
									value="<?php echo Yii::t('main', 'Hae'); ?>">
							</div>

						</div>
						<div class="row">
							<div class="col-sm-4">
								<h4>Määritellään työvuorosuunnittelussa:</h4>
								<b>SL</b> - Sairaus Palkallinen<br>
								<b>SPL</b> - Sairaus Palkaton<br>
								<b>LS</b> - Lapsen sairaus<br>
								<b>VL</b> - Vuosiloma<br>
								<b>VKL</b> - Viikkolomapäivä<br>
								<b>EL</b> - Erikoislauantai<br>
								<b>PY</b> - Pyhäpäivä<br>
								<b>PV</b> - Palkaton vapaa<br>
								<b>LSK</b> - LS (karenssi)<br>
								<b>PP</b> - Palkaton poissaolo<br>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>

		<!-- loppu: .tray-center -->
	</div>
	<br>
	<div class="row collapse" id="haku">
		<div class="col-md-12">
			<br><br><br>
			<?php
			$ko = new Korvaukset;
			echo $this->renderPartial('//korvaukset/_form', array('model' => $ko));
			?>
			<?php
			$lt = new Lisatyotunnit;
			echo $this->renderPartial('//lisatyotunnit/_form', array('model' => $lt));
			?>
			<?php
			$en = new Ennakko;
			echo $this->renderPartial('//ennakko/_form', array('model' => $en));
			?>
		</div>
	</div>

	<?php if (isset($_GET['Tekija']) and $from and $to) : ?>

	<div class="admin-form">
		<div class="panel heading-border">
			<div class="panel-body bg-light">
				<div class="row">
					<div class="table-responsive" id="tableContent">
						<table class="table table-bordered small" id="palkkatauluTaulu">
							<thead class="myBgColors">
								<tr>
									<th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
									<th><?php echo Yii::t('main', 'Tp'); ?></th>
									<th><?php echo Yii::t('main', 'M'); ?></th>
									<th><?php echo Yii::t('main', 'Työtunnit'); ?></th>
									<th><?php echo Yii::t('main', 'matka+<br>tunnit yht'); ?></th>
									<th><?php echo Yii::t('main', 'Ilta'); ?></th>
									<th><?php echo Yii::t('main', 'Iltamatka+<br>Iltatunnit yht'); ?></th>
									<th><?php echo Yii::t('main', 'Lounas'); ?></th>
									<th><?php echo Yii::t('main', 'Yö'); ?></th>
									<th><?php echo Yii::t('main', 'Su T'); ?></th>
									<th><?php echo Yii::t('main', 'Su M'); ?></th>
									<th><?php echo Yii::t('main', 'PY'); ?></th>
									<th><?php echo Yii::t('main', 'EL'); ?></th>
									<th><?php echo Yii::t('main', 'SL'); ?></th>
									<th><?php echo Yii::t('main', 'SPL'); ?></th>
									<th><?php echo Yii::t('main', 'LS'); ?></th>
									<th><?php echo Yii::t('main', 'VL'); ?></th>
									<th><?php echo Yii::t('main', 'VKL'); ?></th>
									<th><?= Yii::t("main", "PV"); ?></th>
									<th><?= Yii::t("main", "LSK"); ?></th>
									<th><?= Yii::t("main", "PP"); ?></th>
									<th><?php echo Yii::t('main', 'Korvaus'); ?></th>
									<th><?php echo Yii::t('main', 'Lisätyötunnit'); ?></th>
									<th><?php echo Yii::t('main', 'Ennakko'); ?></th>
								</tr>
							</thead>
							<?php
								$toteutuneet = Yii::app()->createController('Toteutuneet');
								$tids = array();
								$tyotunnit  = 0;
								$totalTp  = 0;
								$tot_sun  = 0;
								$tp    = 0;
								$sl     = 0;
								$ls     = 0;
								$spl     = 0;
								$vl     = 0;
								$vkl     = 0;
								$vlYht  = 0;
								$vklYht  = 0;
								$slYht  = 0;
								$splYht  = 0;
								$lsYht  = 0;
								$pvYht = 0;
								$lskYht = 0;
								$ppYht = 0;
								$pyhat  = 0;
								$el    = 0;
								$pyhatYht  = 0;
								$elYht  = 0;
								$matkaYht  = 0;
								$yht[0]   = 0;
								$yht[1]   = 0;
								$yht[2]   = 0;
								$yht[3]   = 0;
								$yht[4]   = 0;
								$mPlusTYht  = 0;
								$matkaIltaYht = 0;
								$iltaMatkaPlusIltatunnitYht = 0;
								$loun    = 0;
								$lounYht  = 0;

								$tids = [];
								foreach ($model as $data)
									$tids[] = $data->id;

								$tyopaivia   = $this->TPBetweenMobAll($from, $to, $tids, true);
								$tyotunnit   = $this->TidfromtoMobiiliAll($from, $to, $tids, array(3), $_GET['lu_tai_tot'], true, 0, false, null, null, false);
								$iltatunnit   = $this->TidfromtoMobiiliAll($from, $to, $tids, array(3), $_GET['lu_tai_tot'], true, 1, false, null, null, false);
								$yotunnit   = $this->TidfromtoMobiiliAll($from, $to, $tids, array(3), $_GET['lu_tai_tot'], true, 2, false, null, null, false);
								$matkaIlta   = $this->TidfromtoMobiiliAll($from, $to, $tids, array(2), $_GET['lu_tai_tot'], true, 1, false, null, null, false);
								$matkatunnit   = $this->TidfromtoMobiiliAll($from, $to, $tids, array(2), $_GET['lu_tai_tot'], true, 0, false, null, false);
								$loun   = $this->TidfromtoMobiiliAll($from, $to, $tids, array(10), $_GET['lu_tai_tot'], true, 0, false, null, null, false);
								$sutunnit  = $this->TidfromtoMobiiliAll($from, $to, $tids, array(3), $_GET['lu_tai_tot'], true, 3, false, null, null, false);
								$su_matkat  = $this->TidfromtoMobiiliAll($from, $to, $tids, array(2), $_GET['lu_tai_tot'], true, 3, false, null, null, false);
								$pyhapaivat  = $this->TidfromtoMobiiliAll($from, $to, $tids, array(2, 3), $_GET['lu_tai_tot'], true, 4, false, null, null, false);
								$erikoislauantai  = $this->TidfromtoMobiiliAll($from, $to, $tids, array(2, 3), $_GET['lu_tai_tot'], true, 5, false, null, null, false);

								// <-- SPL, SL, LS, VL, VKL, AP
								$vl_data = $this->TidfromtoVuosilomaBetweenFast($from, $to, $tids);
								$sl_all = $vl_data["SL"];
								$spl_all = $vl_data["SPL"];
								$ls_all = $vl_data["LS"];
								$vl_all = $vl_data["VL"];
								$vkl_all = $vl_data["VKL"];
								$ap_all = $vl_data["AP"];
								// PV, LSK, PP
								$pv_all = $vl_data["PV"];
								$lsk_all = $vl_data["LSK"];
								$pp_all = $vl_data["PP"];
								//     SPL, SL, LS, VL, VKL, AP -->

								foreach ($model as $data) {

									$tp = (isset($tyopaivia[$data->id])) ? $tyopaivia[$data->id] : 0; // $this->Tp($data->id,$from,$to); on kaytossa muuala

									// <-- SPL, SL, LS, VL, VKL, AP
									$sl     = (isset($sl_all[$data->id])) ? $sl_all[$data->id] : 0; // Palkallinen
									$spl     = (isset($spl_all[$data->id])) ? $spl_all[$data->id] : 0; // Palkaton
									$ls     = (isset($ls_all[$data->id])) ? $ls_all[$data->id] : 0; // Lapsen sairaus
									$vl     = (isset($vl_all[$data->id])) ? $vl_all[$data->id] : 0; // Vuosiloma
									$vkl     = (isset($vkl_all[$data->id])) ? $vkl_all[$data->id] : 0; // Viikkolomapaiva  ( Poistettu kaytosta )
									$pv = isset($pv_all[$data->id]) ? $pv_all[$data->id] : 0; // palkaton vapaa
									$lsk = isset($lsk_all[$data->id]) ? $lsk_all[$data->id] : 0; // LS karenssi
									$pp = isset($pp_all[$data->id]) ? $pp_all[$data->id] : 0; // palkaton poissaolo
									//     SPL, SL, LS, VL, VKL, AP -->

									$slYht += $sl;
									$lsYht += $ls;
									$splYht += $spl;
									$vlYht += $vl;
									$vklYht += $vkl;
									$totalTp += $tp;
									$pyhat = $pyhapaivat[$data->id];
									$pyhatYht += $pyhat;
									$el = $erikoislauantai[$data->id];
									$elYht += $el;
									$pvYht += $pv;
									$lskYht += $lsk;
									$ppYht += $pp;

									$iltatunnit_ja_iltamatka = $iltatunnit[$data->id] + $matkaIlta[$data->id];
									$yht[0]   += $tyotunnit[$data->id];
									$yht[1]   += $iltatunnit[$data->id];
									$yht[2]   += $yotunnit[$data->id];
									$yht[3]   += $sutunnit[$data->id];
									$yht[4]   += $su_matkat[$data->id];
									$matkaYht   += $matkatunnit[$data->id];
									$mPlusTYht   += $tyotunnit[$data->id] + $matkatunnit[$data->id];
									$iltaMatkaPlusIltatunnitYht += $iltatunnit_ja_iltamatka;
									$lounYht   += $loun[$data->id];
									$matkaIltaYht   += $matkaIlta[$data->id];

									$this->renderPartial('_palkkataulukko', array(
										'data' => $data,
										'tt_order_1' => $tt_order_1,
										'tt_order_2' => $tt_order_2,
										'tyotunnit' => $tyotunnit[$data->id],
										'matkatunnit' => $matkatunnit[$data->id],
										'tp' => $tp,
										'sl' => $sl,
										'spl' => $spl,
										'ls' => $ls,
										'vl' => $vl,
										'vkl' => $vkl,
										'from' => $from,
										'to' => $to,
										'pyhat' => $pyhat,
										'el' => $el,
										"pp" => $pp,
										"lsk" => $lsk,
										"pv" => $pv,
										'loun' => $loun[$data->id],
										'yotunnit' => $yotunnit[$data->id],
										'iltatunnit' => $iltatunnit[$data->id],
										'matkaIlta' => $matkaIlta[$data->id],
										'iltatunnit_ja_iltamatka' => $iltatunnit_ja_iltamatka,
										'sutunnit' => $sutunnit[$data->id],
										'su_matkat' => $su_matkat[$data->id]
									));
								}

								if ($matkaIltaYht != 0) {
									$matkaIltaYht = '<br><b>Matkat</b>:<br>' . $this->num($matkaIltaYht);
								} else {
									$matkaIltaYht = '';
								}
								?>
							<tfoot>
								<tr>
									<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
									<td><?php if ($totalTp != 0) echo $totalTp; ?></td>
									<td><?php echo number_format($this->num($matkaYht), 2, ',', ''); ?></td>
									<td><?php echo number_format($this->num($yht[0]), 2, ',', ''); ?></td>
									<td><?php echo number_format($this->num($mPlusTYht), 2, ',', ''); ?></td>
									<td><?php if ($matkaIltaYht != 0 or $this->num($yht[1]) != 0) echo '<b>Työt</b>:<br>' . number_format($this->num($yht[1]), 2, ',', '') . $matkaIltaYht; ?>
									</td>
									<td><?php echo number_format($this->num($iltaMatkaPlusIltatunnitYht), 2, ',', '') ?>
									</td>
									<td><?php echo number_format($this->num($lounYht), 2, ',', ''); ?></td>
									<td><?php echo number_format($this->num($yht[2]), 2, ',', ''); ?></td>
									<td><?php echo number_format($this->num($yht[3]), 2, ',', ''); ?></td>
									<td><?php echo number_format($this->num($yht[4]), 2, ',', ''); ?></td>
									<td><?php echo number_format($this->num($pyhatYht), 2, ',', ''); ?></td>
									<td><?php echo number_format($this->num($elYht), 2, ',', ''); ?></td>
									<td><?= ($slYht > 0) ? $slYht : '' ?></td>
									<td><?= ($splYht > 0) ? $splYht : '' ?></td>
									<td><?= ($lsYht > 0) ? $lsYht : '' ?></td>
									<td><?= ($vlYht > 0) ? $vlYht : '' ?></td>
									<td><?= ($vklYht > 0) ? $vklYht : '' ?></td>
									<td><?= ($pvYht > 0) ? $pvYht : '' ?></td>
									<td><?= ($lskYht > 0) ? $lskYht : '' ?></td>
									<td><?= ($ppYht > 0) ? $ppYht : '' ?></td>

									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

<script type="text/javascript">
$(document).ready(function() {

	$(".submitForm").on('click', function(e) {
		e.preventDefault();
		$('.mobileTable').addClass('table-bordered');
		$(this).prev('textarea').val($('#tableContent').html());
		$(this).closest('form').submit();
	});
	/* disabled this to prevent double submits
	$(".haemob").click(function() {
	  $("#yhtveto").submit();
	});
	*/

	$('#deselAll').click(function() {
		$('#tyontekijat').selectpicker('deselectAll');
	});

	$('#selAll').click(function() {
		$('#tyontekijat').selectpicker('selectAll');
	});

	$("#yhtveto").on('submit', function(e) {
		e.preventDefault();

		const all_workers = $("#all_workers").val();
		const from = $("#from").val();
		const to = $("#to").val();
		// default workGroups to empty array
		const workGroups = $(".tyoryhmatMulti").val() ?? [];
		const employees = $("#tyontekijat").val();

		const lu_tai_tot = $("#lu_tai_tot").val();

		const postData = {
			workGroups,
			employees,
			all_workers
		};
		$.ajax({
			url: "./palkkataulukkopost",
			type: "POST",
			data: postData,
			success: (data) => {
				let url = "./palkkataulukko?yhtveto=&Tekija=" +
					"&all_workers=" + all_workers +
					"&lu_tai_tot=" + lu_tai_tot +
					"&from=" + from +
					"&to=" + to;
				for (const workGroup of workGroups) {
					url += "&tyoryhmat[]=" + workGroup;
				}
				window.location = url;
			},
			error: (err) => {
				console.log("Err while submitting palkkataulukko", err);
			}
		});

		/*
		var from = $("#from").val();
		var to = $("#to").val();

		if (from === '') {
		  $('#from').css({
		    "border": "2px #f14010 solid"
		  }).focus();
		  return false;
		}
		if (to === '') {
		  $('#to').css({
		    "border": "2px #f14010 solid"
		  }).focus();
		  return false;
		}
		*/
	});

	$('#tyontekijat').multiselect({
		//inheritClass: true,
		//enableFiltering: true,
		includeSelectAllOption: true,
		nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
		selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
		allSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
		nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
		numberDisplayed: 0,
		buttonWidth: '100%',
		maxHeight: 300,
	});

	$(".tyoryhmatMulti").on("change", (event) => {
		// groups will be null if there's nothing selected,
		// default to empty array
		const groups = $(".tyoryhmatMulti").val() ?? [];
		const allWorkers = $("#all_workers").val();
		let url = "./selectedemployees?allWorkers=" + allWorkers + "&";
		for (const group of groups) {
			url += "workGroups[]=" + group + "&";
		}
		// remove last character (&)
		url = url.slice(0, -1);

		$.ajax({
			url: url,
			type: "GET",
			success: (data) => {
				const empIdArray = JSON.parse(data);
				const workerDropdown = $("#tyontekijat");
				//workerDropdown.multiselect("deselectAll");
				// deselectAll doesn't seem to work for whatever reason,
				// so we'll just get the list we currently have selected
				// and trigger deselect with that.
				const selectedNow = workerDropdown.val() ?? [];
				workerDropdown.multiselect("deselect", selectedNow);
				// select new elements
				workerDropdown.multiselect("select", empIdArray);
			},
			error: (err) => {
				console.log("Err while fetching employee list", err.status, err);
			},
		})
	});

	// <-- Tulostus
	$(document).delegate(".palkkatauluTaulu", "click", function() {

		$('#palkkatauluTaulu td,#palkkatauluTaulu th').css({
			"border": "1px #333 solid",
			"padding": "3px 5px"
		});

		var divToPrint = document.getElementById('palkkatauluTaulu');
		newWin = window.open("");
		newWin.document.write(divToPrint.outerHTML);
		newWin.print();
		newWin.close();
	});
	//    Tulostus -->
});
</script>