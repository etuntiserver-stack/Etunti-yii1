<?php ini_set("max_execution_time", "120"); ?>
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
	Yii::t('main', 'Toteuma'),
);
$asetukset = Asetukset::model()->findbypk(1);
$netvisor_kaytto 			= $asetukset->netvisor_kaytto;
$netvisor_mita_onkayttossa 	= $asetukset->netvisor_mita_onkayttossa;
$netvisor_lahetyksen_muoto	= $asetukset->netvisor_lahetyksen_muoto;
?>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>

<?php
$timestamp = time();
echo '<link rel="stylesheet" type="text/css" href="'.Yii::app()->request->baseUrl.'/css/tyovuorot_v4.css?v=' . $timestamp . '">';
echo '<script type="text/javascript" src="'.Yii::app()->request->baseUrl.'/js/tvuoroot_v4.js?v=' . $timestamp . '"></script>';
?>

<style>
	.fullRivi {
		height: 100%;
		margin-bottom: 2px;
		border: 1px #ccc solid;
		padding: 5px 7px;
		background: white;
		border-radius: 5px;
		width: 100%;
	}

	.oikeallaPlusV,
	.tp {
		display: none;
	}

	.table tbody>tr>td {
		vertical-align: top;
	}

	table {
		width: 100%;
	}

	.tdw1,
	.tdw2,
	.tdw3,
	.tdw4 {
		width: 25%;
	}

	td .latikkoAsetukset {
		max-height: none;
	}
</style>

<!-- begin: .tray-center -->
<div class="tray-center">


	<!-- tulostus -->
	<div class="pull-right">
		<button class="btn btn-primary btn-sm myBgColors tuntienHyvaksyntaTaulu"><?php echo Yii::t('main', 'Tulosta'); ?></button>
		<button class="btn btn-primary btn-sm myBgColors tuntienHyvaksyntaTauluTivistelma"><?php echo Yii::t('main', 'Tulosta tiivistelmä'); ?></button>
	</div>
	<!-- tulostus -->

	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-time"></i> <?php echo Yii::t('main', 'TUNTIEN HYVÄKSYNTÄ'); ?>
	</h2>



	<form id="yhtveto" action="#" class="form-inline" method="GET">
		<input type="hidden" name="mob_hae">

		<div class="admin-form">
			<div class="panel heading-border">
				<div class="panel-body bg-light">

					<!-- Input Icons -->
					<div class="row">

						<div class="col-md-3">
							<div class="section">
								<label class="field select">
									<?php

									$site = Yii::app()->createController('Site');
									$tyontekiatLista = $site[0]->workerListSelect2(
										'tekija', // name
										'', //class
										'nimi', // id
										(isset($_GET['tekija'])) ? $_GET['tekija'] : '', //selected
										1, // active workers or not,
										false, // multiple select or not
									);
									echo $tyontekiatLista;

									?>

								</label>
							</div>
						</div>
						<div class="col-md-2">
							<div class="section">
								<label class="field select">

									<?php
									$lounas = '';
									$lounas = (isset($_GET['ilman']) and in_array('Lounastauko', $_GET['ilman']))  ? 'selected' : '';
									$matka = '';
									$matka = (isset($_GET['ilman']) and in_array('MATKA', $_GET['ilman']))   ? 'selected' : '';

									echo '<select name="ilman[]" class="selectpicker ilman"  multiple="multiple"  title="' . Yii::t('main', 'Ei lasketa') . '">';
									echo '<option value="Lounastauko" ' . $lounas . '>' . Yii::t('main', 'Lounastauko') . '</option>';
									echo '<option value="MATKA" ' . $matka . '>' . Yii::t('main', 'Matka') . '</option>';
									echo '</select>';
									?>


								</label>
							</div>
						</div>
						<div class="col-md-2 col-md-offset-1">
							<div class="section">
								<label class="field prepend-icon">

									<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?= date('d.m.Y', strtotime($from)) ?>">

									<label for="firstname" class="field-icon">
										<i class="glyphicon glyphicon-calendar"></i>
									</label>
								</label>
							</div>
						</div>

						<div class="col-md-2">
							<div class="section">
								<label class="field prepend-icon">

									<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?= date('d.m.Y', strtotime($to)) ?>">

									<label for="firstname" class="field-icon">
										<i class="glyphicon glyphicon-calendar"></i>
									</label>
								</label>
							</div>
						</div>

						<div class="col-md-2">
							<input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
						</div>
					</div>


					<?php /* if($netvisor_kaytto == 1 and $netvisor_lahetyksen_muoto == 0 and isset($_GET['tekija'])) : ?>
			<p><div class="row">
				<div class="col-md-12">
					<span class="btn btn-primary btn-lg lahetaKaikki btn-block myBgColors"><?php echo Yii::t('main', 'Lähetä kaikki netvisoriin'); ?></span>
				</div>
			</div></p>
		    <?php endif; */ ?>

					<?php if ($netvisor_kaytto == 1 and isset($_GET['tekija'])) : ?>
						<p>
						<div class="row">
							<div class="col-md-12">
								<span class="btn btn-primary btn-lg lahetaKaikki_erikseen btn-block myBgColors"><?php echo Yii::t('main', 'Lähetä kaikki netvisoriin'); ?></span>
							</div>
						</div>
						</p>
					<?php endif; ?>

					<?php if (isset($_GET['tekija'])) : ?>
						<div class="row">
							<div class="col-md-4">
								<?php echo CHtml::link(
									Yii::t('main', 'HYVÄKSYMÄTTÖMÄT TUNNIT'),
									array('//mobile/hyvaksymattomat', 'tid' => $_GET['tekija']),
									array('target' => '_blank', 'class' => 'btn btn-lg btn-block tn-primary myBgColors')
								);
								?>
							</div>
							<div class="col-md-4">
								<span class="btn btn-primary btn-lg hyvaksyminen btn-block myBgColors" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Hyväksyntänappi1'); ?> " for="alkaen" arvo="<?= date("Y-m-d", strtotime("first day of last month")) ?>" tid="<?= $_GET['tekija'] ?>"><?php echo Yii::t('main', 'Hyväksy ' . date("d.m.Y", strtotime("first day of last month")) . ' alkaen'); ?></span>
							</div>
							<div class="col-md-4">
								<span class="btn btn-primary btn-lg hyvaksyminen btn-block myBgColors" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Hyväksyntänappi2'); ?> " for="kaikki" arvo="all" tid="<?= $_GET['tekija'] ?>"><?php echo Yii::t('main', 'Hyväksy kaikki ' . date("d.m.Y", strtotime("-1 day")) . ' asti'); ?></span>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

	</form>


	<!-- loppu: .tray-center -->
</div>



<div id="ilmoitusLahetysta"></div>


<?php if (isset($_GET['tekija'])) : ?>

	<?php
	$netvisor_mita_lahetetaan = array();
	if ($netvisor_kaytto == 1) {
		$netvisor_mita_lahetetaan = json_decode($asetukset->netvisor_mita_lahetetaan);
	}
	$tid = $_GET['tekija'];

	function dateDiff($start, $end)
	{
		$start_ts = strtotime($start);
		$end_ts = strtotime($end);
		$diff = $end_ts - $start_ts;
		return round($diff / 86400);
	}

	if (isset($_GET['from']))
		$from = date("d.m.Y", strtotime($_GET['from']));
	if (isset($_GET['to']))
		$to = date("d.m.Y", strtotime($_GET['to']));

	$dateDiff = dateDiff($from, $to);
	?>

	<div id="forTulostus">
		<h3>
			<?php echo $this->etuSukunimi($_GET['tekija']); ?>,
			<?php echo date("d.m.Y", strtotime($_GET['from'])); ?> - <?php echo date("d.m.Y", strtotime($_GET['to'])); ?>
		</h3>
	</div>


	<div class="panel heading-border">
		<div class="panel-heading"><?php echo Yii::t('main', 'Yhteensä'); ?> <?php echo date("d.m.Y", strtotime($_GET['from'])); ?> - <?php echo date("d.m.Y", strtotime($_GET['to'])); ?></div>
		<div class="panel-body">
			<div id="yhteensaTfootContent"></div>
		</div>
	</div>



	<div class="panel heading-border">
		<div class="panel-body">

			<table class="table table-bordered" cellspacing="0" cellpadding="0" id="tuntienHyvaksyntaTaulu">
				<thead class="myBgColors">
					<tr>
						<th class="tdw1"><?php echo Yii::t('main', 'Suunnitellut'); ?></th>
						<th class="tdw2"><?php echo Yii::t('main', 'Luetut'); ?></th>
						<th class="tdw3"><?php echo Yii::t('main', 'Hyväksyntä'); ?> <a href="#" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Hyväksyntäohje'); ?>">?</a></th>
						<th class="tdw4"><?php echo Yii::t('main', 'Yhteensä'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$arrDate = array(1 => "Maanantai", 2 => "Tiistai", 3 => "Keskiviikko", 4 => "Torstai", 5 => "Perjantai", 6 => "Lauantai", 7 => "Sunnuntai");
					$yhtMatka 	= 0;
					$yhtIlta 	= 0;
					$yhtYo 	= 0;
					$yhtSu 	= 0;
					$yhtTyotunnitWeek = 0;
					$yhtMatkaWeek = 0;
					$yhtLounaatWeek = 0;
					$yhtIltaWeek 	= 0;
					$yhtYoWeek	= 0;
					$yhtSuWeek	= 0;
					$yhtTotpvmtid	= 0;
					$yhtLuetutpvmtid = 0;
					$yhtTyotunnit	= 0;
					$yhtLounaat	= 0;

					$yhtPy	= 0;
					$yhtEl	= 0;
					$yhtPyWeek	= 0;
					$yhtElWeek	= 0;

					$suunnittelut = 0;
					$yhtSuunnittelut = 0;
					$yhtSuunnittelutWeek = 0;

					$yhteensaLuetut 	= 0;
					$yhteensaToteutuneet 	= 0;

					$mobile = Yii::app()->createController('Mobile');
					$tyovuoroot = Yii::app()->createController('Tyovuoroot');
					$yhtSPLWeek	= 0;
					$yhtSLWeek	= 0;
					$yhtLSWeek	= 0;
					$yhtVLWeek	= 0;
					$yhtVKLWeek	= 0;
					$yhtAPWeek	= 0;
					$yhtPVWeek	= 0;

					$yhtSPL	= 0;
					$yhtSL	= 0;
					$yhtLS	= 0;
					$yhtVL	= 0;
					$yhtVKL	= 0;
					$yhtAP	= 0;
					$yhtPV	= 0;

					$ilman_lounastaukot 	= false;
					$ilman_matkat 	= false;
					if (isset($_GET['ilman'])) {
						foreach ($_GET['ilman'] as $val) {
							if ($val == 'Lounastauko')
								$ilman_lounastaukot = true;

							if ($val == 'MATKA')
								$ilman_matkat = true;
						}
					}


					$tyotunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, array(3), 2, false, 0, true, null, null, false);

					$hyv_arr = array(3, 2, 10);
					if ($ilman_matkat)
						unset($hyv_arr[1]);
					if ($ilman_lounastaukot)
						unset($hyv_arr[2]);
					$hyv_tyotunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, $hyv_arr, 3, false, 0, true, null, null, false);
					/*
					echo '<pre>';
					print_r($hyv_tyotunnit_all);
					echo '</pre>';
					exit;
					*/
					if (!$ilman_lounastaukot)
						$lounaat_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [10], 2, false, 0, true, null, null, false);

					if (!$ilman_matkat)
						$matkatunnit_all = $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [2], 2, false, 0, true, null, null, false);

					if ($ilman_matkat)
						$iltatunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [3], 2, false, 1, true, null, null, false);
					else
						$iltatunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [2, 3], 2, false, 1, true, null, null, false);

					$yotunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [2, 3], 2, false, 2, true, null, null, false);
					$sutunnit_all 	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [2, 3], 2, false, 3, true, null, null, false);

					if (!$ilman_matkat) {
						// this used to be $from - $from for some reason
						$pyhapaivat_all		= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [2, 3], 2, false, 4, true, null, null, false);
						$erikoislauantai_all	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [2, 3], 2, false, 5, true, null, null, false);
					} else {
						$pyhapaivat_all	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [3], 2, false, 4, true, null, null, false);
						$erikoislauantai_all	= $mobile[0]->TidfromtoMobiiliAll($from, $to, $tid, [3], 2, false, 5, true, null, null, false);
					}

					// Pyhapaivat
					$pyhapaivat = $tyovuoroot[0]->pyhapaivatAll($from, $to);

					// <-- SPL, SL, LS, VL, VKL, AP
					$sl_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from, $to, [$tid], 'SL', true); // Palkallinen
					$spl_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from, $to, [$tid], 'SPL', true); // Palkaton
					$ls_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from, $to, [$tid], 'LS', true); // Lapsen sairaus
					$vl_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from, $to, [$tid], 'VL', true); // Vuosiloma
					$vkl_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from, $to, [$tid], 'VKL', true); // Viikkolomapaiva  ( Poistettu kaytosta )
					$ap_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from, $to, [$tid], 'AP', true); // Arkipaiva
					$pv_all 		= $mobile[0]->TidfromtoVuosilomaBetween($from, $to, [$tid], 'PV', true); // Palkaton vapaa
					//     SPL, SL, LS, VL, VKL, AP -->

					$luetut_laatikot = json_decode($this->LuetutPvmTidBetween($from, $to, $tid), true);
					$toteutuneet_laatikot = $this->TotPvmTidBetween($asetukset, $from, $to, $tid);

					$vuosilomachecker	= $this->vuosilomaCheckerBetween($from, $to, $tid);
					$hyvaksymmattomat_t	= $this->hyvaksyttamatTunnitBetween($from, $to, $tid);


					$haku_from 	= date("Y-m-d", strtotime($from));
					$haku_to 	= date("Y-m-d", strtotime($to));
					$haku_criteria = ["status!=11"];
					$tv_arr 	= $tyovuoroot[0]->tv_arr($haku_from, $haku_to, [$tid], $haku_criteria, true, []);

					for ($i = 0; $i <= $dateDiff; $i++) {

						$plus = "+$i day";
						$date = '';
						$date = date("d.m.Y", strtotime($from . " " . $plus));

						$columnDate = date("N/d.m", strtotime($date));
						$explColDate = explode("/", $columnDate);
						$did = date("Ymd", strtotime($date));

						$pvm		= date("Y-m-d", strtotime($date));
						$pvmF		= date("d.m.Y", strtotime($date));
						$tyotunnit		= (isset($tyotunnit_all[$pvm][$tid])) ? $tyotunnit_all[$pvm][$tid] : 0;
						$hyv_tyotunnit 	= (isset($hyv_tyotunnit_all[$pvm][$tid])) ? $hyv_tyotunnit_all[$pvm][$tid] : 0;
						$lounaat 		= (isset($lounaat_all[$pvm][$tid])) ? $lounaat_all[$pvm][$tid] : 0;
						$matkatunnit 	= (isset($matkatunnit_all[$pvm][$tid])) ? $matkatunnit_all[$pvm][$tid] : 0;
						$iltatunnit 	= (isset($iltatunnit_all[$pvm][$tid])) ? $iltatunnit_all[$pvm][$tid] : 0;
						$yotunnit 		= (isset($yotunnit_all[$pvm][$tid])) ? $yotunnit_all[$pvm][$tid] : 0;
						$sutunnit 		= (isset($sutunnit_all[$pvm][$tid])) ? $sutunnit_all[$pvm][$tid] : 0;
						$pyhapaivat_tunnit	= (isset($pyhapaivat_all[$pvm][$tid])) ? $pyhapaivat_all[$pvm][$tid] : 0;
						$erikoislauantai_tunnit = (isset($erikoislauantai_all[$pvm][$tid])) ? $erikoislauantai_all[$pvm][$tid] : 0;

						// <-- SPL, SL, LS, VL, VKL, AP
						$sl 		= (isset($sl_all[$pvmF][$tid])) ? $sl_all[$pvmF][$tid] : 0; // Palkallinen
						$spl 		= (isset($spl_all[$pvmF][$tid])) ? $spl_all[$pvmF][$tid] : 0; // Palkaton
						$ls 		= (isset($ls_all[$pvmF][$tid])) ? $ls_all[$pvmF][$tid] : 0; // Lapsen sairaus
						$vl 		= (isset($vl_all[$pvmF][$tid])) ? $vl_all[$pvmF][$tid] : 0; // Vuosiloma
						$vkl 		= (isset($vkl_all[$pvmF][$tid])) ? $vkl_all[$pvmF][$tid] : 0; // Viikkolomapaiva  ( Poistettu kaytosta )
						$ap 		= (isset($ap_all[$pvmF][$tid])) ? $ap_all[$pvmF][$tid] : 0; // Arkipaiva
						$pv 		= (isset($pv_all[$pvmF][$tid])) ? $pv_all[$pvmF][$tid] : 0; // Palkaton vapaa
						//     SPL, SL, LS, VL, VKL, AP -->

						$yhtTyotunnit 	+= $tyotunnit;
						$yhtMatka 		+= $matkatunnit;
						$yhtLounaat 	+= $lounaat;
						$yhtIlta 		+= $iltatunnit;
						$yhtYo 		+= $yotunnit;
						$yhtSu 		+= $sutunnit;

						$yhtPy 		+= $pyhapaivat_tunnit;
						$yhtEl 		+= $erikoislauantai_tunnit;
						$yhtSPL 	+= $spl;
						$yhtSL 		+= $sl;
						$yhtLS 		+= $ls;
						$yhtVL 		+= $vl;
						$yhtVKL 	+= $vkl;
						$yhtAP 		+= $ap;
						$yhtPV 		+= $pv;

						$korv = $this->korvauksetPvmTid(date("Y-m-d", strtotime($date)), $tid);
						if (!empty($korv)) {
							$korv = '<div class="row">
										<div class="col-sm-4">
											<h3>' . Yii::t('main', 'Korvaukset ja ennakot') . '</h3>
											' . $korv .
									'</div>
								</div>';
						}

						$ispyha = '';
						if (isset($pyhapaivat[$date]['su']) or isset($pyhapaivat[$date]['vp']) or isset($pyhapaivat[$date]['el'])) {
							$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="' . Yii::t('main', 'Pyhäpäivä') . '"></i>';
						}
						if (isset($pyhapaivat[$date]['el'])) {
							$ispyha = ' <i class="text-warning fa fa-flag-o" aria-hidden="true" style="font-size:150%" data-toggle="tooltip" data-placement="bottom" title="' . Yii::t('main', 'Erikoislauantai') . '"></i>';
						}
						echo '<tr><td class="text-left" colspan="4">' . $arrDate[$explColDate[0]] . ' ' . date("d.m", strtotime($date)) . $ispyha . '</td></tr>';

						if ($netvisor_kaytto == 1) {
							echo '<tr class="korvaukset_ennakkot">';
							echo '<td colspan="4">' .
								CHtml::button(
									Yii::t('main', 'Korvaukset ja ennakot' . $netvisor_kaytto),
									array(
										'class' => 'btn btn-sm btn-primary btn-group myBgColors avaaModalFor',
										'taulu' => 'korvaukset',
										'pvm' => date("Y-m-d", strtotime($date)),
										'tid' => $tid,
									)
								) . '
									<br>
									' . $korv . ' 
							</td></tr>';
						}

						if (isset($vuosilomachecker[$date])) {
							echo '<tr>';
							echo '<td></td><td></td><td class="text-center">';
							foreach ($vuosilomachecker[$date] as $vuosiloma)
								echo $vuosiloma;
							echo '</td><td></td>';
							echo '</tr>';
						}
						echo '<tr class="su_lu_tot">';
						$suunnittelut = 0;
						$didoResult = '';
						if (isset($tv_arr[$tid][$date])) {
							$didoResult .= '<div id="suun_' . $did . '_' . $tid . '" class="latikkoAsetukset" pvm="' . $date . '" tid="' . $tid . '">';
							ksort($tv_arr[$tid][$date]);
							foreach ($tv_arr[$tid][$date] as $k => $v)
								foreach ($v as $v2) {
									$didoResult .= '<p><span class="pull-right">' . $this->sprint($v2['tv_kesto']) . '</span>' . $v2['tv_edit'] . '</p>';
									$suunnittelut += $v2['tv_kesto'];
								}
							$didoResult .= '</div>';
						}
						$yhtSuunnittelut += $suunnittelut;
						echo '<td>' . $didoResult . '</td>';
						echo '<td>';
						if (isset($luetut_laatikot['laatikkot'][$date])) {
							echo '<div class="small" style="opacity:0.6">';
							foreach ($luetut_laatikot['laatikkot'][$date] as $item)
								echo $item;
							echo '</div>';
						}
						echo '</td>';

						echo '<td id="' . $did . '_' . $tid . '">';
						if (isset($toteutuneet_laatikot[$date])) {
							echo '<div class="small">';
							foreach ($toteutuneet_laatikot[$date] as $item)
								echo $item;

							echo '</div>';
						}
						echo '<b class="link glyphicon glyphicon-plus uusirivi" for="' . $did . '_' . $tid . '"></b>';
						echo '</td>';


						if ($suunnittelut > $hyv_tyotunnit)
							$eroAika = $suunnittelut - $hyv_tyotunnit;
						else
							$eroAika = ($hyv_tyotunnit - $suunnittelut);

						echo '<td class="yhteensaPvm_' . date("W", strtotime($date)) . ' forFooter" id="yhteensaPvm_' . $did . '_' . $tid . '">
							<div class="row">
								<div class="col-sm-5">
								' . Yii::t('main', 'Suunnitellut: ') . '
								</div><div class="col-sm-6">
								<span class="pvmSuunn" total="' . (int)$suunnittelut . '">' . $this->sprint($suunnittelut) . '</span>
							</div>
							<div class="row">
								</div><div class="col-sm-5">
								' . Yii::t('main', 'Hyväksytyt: ') . '
								</div><div class="col-sm-6">
								<span class="pvmTot" total="' . (int)$hyv_tyotunnit . '">' . $this->sprint($hyv_tyotunnit) . '</span>
							</div>
							<div class="row">
								</div><div class="col-sm-5">
								' . Yii::t('main', 'Ero aika: ') . '
								</div><div class="col-sm-6">
								<span class="pvmEro">' . $this->sprint($eroAika) . '</span>
								</div>
							</div>
							</td>';

						echo '</tr>';


						//$hyvaksytty = HyvaksyttamatPvmTunnit::model()->find(" tid='".$tid."' AND pvm='".date("Y-m-d",strtotime($date))."' AND netvisor_ok_list!='' ");
						if (isset($hyvaksymmattomat_t[$date]))
							$nvtilanne = 1;
						else
							$nvtilanne = 0;

						echo '
	<tr><td colspan="4">
		<table class="yhteensaPvmAllaTaulu_' . date("W", strtotime($date)) . ' forFooterAlla table table-bordered" cellspacing="0" cellpadding="0" id="yhteensaPvmAllaTaulu_' . $did . '_' . $tid . '" style="width:100%">
		 <thead>
		  <tr>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('tyotunnit', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '">' . Yii::t('main', 'Työtunnit') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('matka', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '">' . Yii::t('main', 'Matkat') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('lounaat', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '">' . Yii::t('main', 'Lounaat') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('tyoilta', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '">' . Yii::t('main', 'Ilta') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('tyoyo', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '">' . Yii::t('main', 'Yö') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('tyosu', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Sunnuntaitunnit') . '">' . Yii::t('main', 'Su') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('py', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Pyhäpäivätunnit') . '">' . Yii::t('main', 'Py') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('el', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Erikoislauantaitunnit') . '">' . Yii::t('main', 'El') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('sl', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Palkkalinen sairasloma') . '">' . Yii::t('main', 'SL') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('spl', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Palkaton sairasloma') . '">' . Yii::t('main', 'SPL') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('ls', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Lapsen sairaus') . '">' . Yii::t('main', 'LS') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('vl', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Vuosiloma') . '">' . Yii::t('main', 'VL') . '</th>
		   <!--<th data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Viikkolomapäivä') . '">' . Yii::t('main', 'VKL') . '</th>-->
		   <th class="' . (($netvisor_kaytto == 1 and in_array('ap', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Arkipyhä') . '">' . Yii::t('main', 'AP') . '</th>
		   <th class="' . (($netvisor_kaytto == 1 and in_array('pv', $netvisor_mita_lahetetaan, true)) ? 'bg-success' : '') . '" data-toggle="tooltip" data-placement="top" title="' . Yii::t('main', 'Arkipyhä') . '">' . Yii::t('main', 'PV') . '</th>
		  </tr>
		 </thead>
		  <tr>
			<td><span class="allaTyotunnit" total="' . (int)$tyotunnit . '">' . $this->sprint($tyotunnit) . '</span></td>
			<td><span class="allaMatkat" total="' . (int)$matkatunnit . '">' . $this->sprint($matkatunnit) . '</span></td>
			<td><span class="allaLounaat" total="' . (int)$lounaat . '">' . $this->sprint($lounaat) . '</span></td>
			<td><span class="allaIlta" total="' . (int)$iltatunnit . '">' . $this->sprint($iltatunnit) . '</span></td>
			<td><span class="allaYo" total="' . (int)$yotunnit . '">' . $this->sprint($yotunnit) . '</span></td>
			<td><span class="allaSu" total="' . (int)$sutunnit . '">' . $this->sprint($sutunnit) . '</span></td>
			<td>' . $this->sprint($pyhapaivat_tunnit) . '</td>
			<td>' . $this->sprint($erikoislauantai_tunnit) . '</td>
			<td><span class="allaSL" total="' . (int)$sl . '">' . $sl . '</span></td>
			<td><span class="allaSPL" total="' . (int)$spl . '">' . $spl . '</span></td>
			<td><span class="allaLS" total="' . (int)$ls . '">' . $ls . '</span></td>
			<td><span class="allaVL" total="' . (int)$vl . '">' . $vl . '</span></td>
			<!--<td><span class="allaVKL" total="' . (int)$vkl . '">' . $this->sprint($vkl) . '</span></td>-->
			<td><span class="allaAP" total="' . (int)$ap . '">' . $ap . '</span></td>
			<td><span class="allaPV" total="' . (int)$pv . '">' . $pv . '</span></td>
		  </tr>';
		  if ($netvisor_kaytto == 1 and ($netvisor_mita_onkayttossa == 1 or $netvisor_mita_onkayttossa == 2))
		  {
				echo ' 
				<tr class="lahetys_netvisoriin_erikseen">
					<td colspan="14">			
						<button class="btn btn-warning btn-sm btn-block esittele_tyotunnit_erikseen" nvtilanne="' . $nvtilanne . '"
							pvm="' . $date . '"
							tid="' . $tid . '"

							matka		="'.round($matkatunnit/3600, 2).'"
							tyoilta		="'.round($iltatunnit/3600, 2).'"
							tyoyo		="'.round($yotunnit/3600, 2).'"
							tyosu		="'.round($sutunnit/3600, 2).'"

							sl			="' . (int)$sl . '"
							spl			="' . (int)$spl . '"
							ls			="' . (int)$ls . '"
							vl			="' . (int)$vl . '"
							ap			="' . (int)$ap . '"
							pv			="' . (int)$pv . '"
						></button>
					</td>
				</tr>';
		  }

		  echo '
		</table>
	</td></tr>';


						$yhtSuunnittelutWeek 	+= $suunnittelut;
						$yhtTotpvmtid 		+= $hyv_tyotunnit;
						$yhteensaToteutuneet	+= $hyv_tyotunnit;

						if (isset($luetut_laatikot['tunnit'][$date])) {
							$yhtLuetutpvmtid 	+= array_sum($luetut_laatikot['tunnit'][$date]);
							$yhteensaLuetut 	+= array_sum($luetut_laatikot['tunnit'][$date]);
						}

						$yhtTyotunnitWeek	+= $tyotunnit;
						$yhtMatkaWeek 	+= $matkatunnit;
						$yhtLounaatWeek	+= $lounaat;
						$yhtIltaWeek 	+= $iltatunnit;
						$yhtYoWeek 		+= $yotunnit;
						$yhtSuWeek 		+= $sutunnit;
						$yhtPyWeek 		+= $pyhapaivat_tunnit;
						$yhtElWeek 		+= $erikoislauantai_tunnit;
						$yhtSLWeek 		+= $sl;
						$yhtSPLWeek		+= $spl;
						$yhtLSWeek 		+= $ls;
						$yhtVLWeek 		+= $vl;
						$yhtVKLWeek 	+= $vkl;
						$yhtAPWeek 		+= $ap;
						$yhtPVWeek 		+= $pv;
						$tid 		= $tid;

						if (date('N', strtotime($date)) == 7) {


							echo '<tr><td colspan="4">' . Yii::t('main', 'Viikko') . ' ' . date("W", strtotime($date)) . '</td></tr>';
							echo '
	<tr><td colspan="4">
		<table class="table everyviikko" cellspacing="0" cellpadding="0">
		 <thead>
		  <tr>
			<th>' . Yii::t('main', 'Suunn.') . '</th>
			<th>' . Yii::t('main', 'Luetut') . '</th>
			<th>' . Yii::t('main', 'Hyväksytyt') . '</th>
			<th>' . Yii::t('main', 'Työtunnit') . '</th>
			<th>' . Yii::t('main', 'Matkat') . '</th>
			<th>' . Yii::t('main', 'Lounaat') . '</th>
			<th>' . Yii::t('main', 'Ilta') . '</th>
			<th>' . Yii::t('main', 'Yö') . '</th>
			<th>' . Yii::t('main', 'Su') . '</th>
			<th>' . Yii::t('main', 'Py') . '</th>
			<th>' . Yii::t('main', 'El') . '</th>
			<th>' . Yii::t('main', 'SL') . '</th>
			<th>' . Yii::t('main', 'SPL') . '</th>
			<th>' . Yii::t('main', 'LS') . '</th>
			<th>' . Yii::t('main', 'VL') . '</th>
			<!--<th>' . Yii::t('main', 'VKL') . '</th>-->
			<th>' . Yii::t('main', 'AP') . '</th>
			<th>' . Yii::t('main', 'PV') . '</th>
		  </tr>
		 </thead>
		  <tr>
			<td class="suunnWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtSuunnittelut) . '<br>' . $this->num($yhtSuunnittelut) . '</td>
			<td class="luetutWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtLuetutpvmtid) . '<br>' . $this->num($yhtLuetutpvmtid) . '</td>
			<td class="totWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtTotpvmtid) . '<br>' . $this->num($yhtTotpvmtid) . '</td>
			<td class="tyotunnitWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtTyotunnitWeek) . '<br>' . $this->num($yhtTyotunnitWeek) . '</td>
			<td class="matkatWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtMatkaWeek) . '<br>' . $this->num($yhtMatkaWeek) . '</td>
			<td class="lounaatWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtLounaatWeek) . '<br>' . $this->num($yhtLounaatWeek) . '</td>
			<td class="iltaWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtIltaWeek) . '<br>' . $this->num($yhtIltaWeek) . '</td>
			<td class="yoWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtYoWeek) . '<br>' . $this->num($yhtYoWeek) . '</td>
			<td class="suWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtSuWeek) . '<br>' . $this->num($yhtSuWeek) . '</td>
			<td>' . $this->sprint($yhtPyWeek) . '<br>' . $this->num($yhtPyWeek) . '</td>
			<td>' . $this->sprint($yhtElWeek) . '<br>' . $this->num($yhtElWeek) . '</td>

			<td class="SLWeek_' . date("W", strtotime($date)) . '">' . $yhtSLWeek . '</td>
			<td class="SPLWeek_' . date("W", strtotime($date)) . '">' . $yhtSPLWeek . '</td>
			<td class="LSWeek_' . date("W", strtotime($date)) . '">' . $yhtLSWeek . '</td>
			<td class="VLWeek_' . date("W", strtotime($date)) . '">' . $yhtVLWeek . '</td>
			<!--<td class="VKLWeek_' . date("W", strtotime($date)) . '">' . $this->sprint($yhtVKLWeek) . '</td>-->
			<td class="APWeek_' . date("W", strtotime($date)) . '">' . $yhtAPWeek . '</td>
			<td class="PVWeek_' . date("W", strtotime($date)) . '">' . $yhtPVWeek . '</td>
		  </tr>
		</table>
	</td></tr>';


							$yhtTyotunnitWeek = 0;
							$yhtMatkaWeek 	= 0;
							$yhtLounaatWeek	= 0;
							$yhtIltaWeek 	= 0;
							$yhtYoWeek 	= 0;
							$yhtSuWeek 	= 0;
							$yhtPyWeek 	= 0;
							$yhtElWeek 	= 0;
							$yhtTotpvmtid 	= 0;
							$yhtLuetutpvmtid = 0;
							$yhtSuunnittelut = 0;
							$yhtSLWeek 	= 0;
							$yhtSPLWeek	= 0;
							$yhtLSWeek 	= 0;
							$yhtVLWeek 	= 0;
							$yhtVKLWeek = 0;
							$yhtAPWeek	= 0;
							$yhtPVWeek	= 0;
						}
					}
					?>

				</tbody>
				<tfoot id="yhteensaTfoot">
					<tr>
						<td colspan="4">
							<table class="table" cellspacing="0" cellpadding="0" id="yhteensaFooterTaulu" border="0">
								<thead class="myBgColors">
									<tr>
										<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
										<th><?php echo Yii::t('main', 'Suunn.'); ?></th>
										<th><?php echo Yii::t('main', 'Luetut'); ?></th>
										<th><?php echo Yii::t('main', 'Hyväksytyt'); ?></th>
										<th><?php echo Yii::t('main', 'Työtunnit'); ?></th>
										<th><?php echo Yii::t('main', 'Matkat'); ?></th>
										<th><?php echo Yii::t('main', 'Lounaat'); ?></th>
										<th><?php echo Yii::t('main', 'Ilta'); ?></th>
										<th><?php echo Yii::t('main', 'Yö'); ?></th>
										<th><?php echo Yii::t('main', 'Su'); ?></th>
										<th><?php echo Yii::t('main', 'Py'); ?></th>
										<th><?php echo Yii::t('main', 'El'); ?></th>
										<th><?php echo Yii::t('main', 'SL'); ?></th>
										<th><?php echo Yii::t('main', 'SPL'); ?></th>
										<th><?php echo Yii::t('main', 'LS'); ?></th>
										<th><?php echo Yii::t('main', 'VL'); ?></th>
										<!--<th><?php echo Yii::t('main', 'VKL'); ?></th>-->
										<th><?php echo Yii::t('main', 'AP'); ?></th>
										<th><?php echo Yii::t('main', 'PV'); ?></th>
									</tr>
								</thead>
								<tr>
									<td></td>
									<td><span class="suunnFoot"><?php echo $this->sprint($yhtSuunnittelutWeek); ?><br><?php echo $this->num($yhtSuunnittelutWeek); ?></span></td>
									<td><?php echo $this->sprint($yhteensaLuetut); ?><br><?php echo $this->num($yhteensaLuetut); ?></td>
									<td><span class="totFoot"><?php echo $this->sprint($yhteensaToteutuneet); ?><br><?php echo $this->num($yhteensaToteutuneet); ?></span></td>
									<td><span class="tyotunnitFoot"><?php echo $this->sprint($yhtTyotunnit); ?><br><?php echo $this->num($yhtTyotunnit); ?></span></td>
									<td><span class="matkatFoot"><?php echo $this->sprint($yhtMatka); ?><br><?php echo $this->num($yhtMatka); ?></span></td>
									<td><span class="lounaatFoot"><?php echo $this->sprint($yhtLounaat); ?><br><?php echo $this->num($yhtLounaat); ?></span></td>
									<td><span class="iltaFoot"><?php echo $this->sprint($yhtIlta); ?><br><?php echo $this->num($yhtIlta); ?></span></td>
									<td><span class="yoFoot"><?php echo $this->sprint($yhtYo); ?><br><?php echo $this->num($yhtYo); ?></span></td>
									<td><span class="suFoot"><?php echo $this->sprint($yhtSu); ?><br><?php echo $this->num($yhtSu); ?></span></td>
									<td><?php echo $this->sprint($yhtPy); ?></td>
									<td><?php echo $this->sprint($yhtEl); ?></td>
									<td><span class="SLFoot"><?php echo $yhtSL; ?></span></td>
									<td><span class="SPLFoot"><?php echo $yhtSPL; ?></span></td>
									<td><span class="LSFoot"><?php echo $yhtLS; ?></span></td>
									<td><span class="VLFoot"><?php echo $yhtVL; ?></span></td>
									<td><span class="APFoot"><?php echo $yhtAP; ?></span></td>
									<td><span class="PVFoot"><?php echo $yhtPV; ?></span></td>
									<!--<td><span class="VKLFoot"><?php echo $this->sprint($yhtVKL); ?></span></td>-->
								</tr>
							</table>
						</td>
					</tr>

				</tfoot>
			</table>

		</div>
	</div>

<?php endif; ?>



<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>


<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
<?php /* Yii::app()->clientScript->registerPackage('tyovuoroot'); */ ?>
<?php Yii::app()->clientScript->registerPackage('toteuma'); ?>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/totrivi_poista.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$(".select2").select2();

		$('.mob_kesto').removeClass('hidden');
		$('.tv_kesto').removeClass('hidden');

		yhteensaTfoot();

		setTimeout(function() {
		    if (typeof jQuery.hovertietoja === "function") {
		        jQuery.hovertietoja();
		    } else {
		        console.error("Функция jQuery.hovertietoja не найдена.");
		    }
		}, 2000);

		function yhteensaTfoot() {
			var getContent = $('#yhteensaTfoot').html();
			$('#yhteensaTfootContent').html('<div class="row table-responsive"><table class="table">' + getContent + '</table></div>');
		}

		$(".haemob").click(function() {
			$("#yhtveto").submit();
		});

		$("#yhtveto").on('submit', function(e) {

			var from = $("#from").val();
			var to = $("#to").val();
			var nimi = $("#nimi").val();

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
			if (nimi === 'kaikki') {
				$('#nimi').css({
					"border": "2px #f14010 solid"
				}).focus();
				return false;
			}


		});

		$(".lahetaKaikki").click(function() {
			$(".esittele_tyotunnit").each(function() {
				if ($(this).attr('nvtilanne') !== '1') {
					$(this).click();
				}
			});
		});

		$(".lahetaKaikki_erikseen").click(function() {
			$(".esittele_tyotunnit_erikseen").each(function() {
				if ($(this).attr('nvtilanne') !== '1') {
					$(this).click();
				}
			});
		});

		LahetaPainike();

		function LahetaPainike() {
			$(".esittele_tyotunnit").each(function() {
				if ($(this).attr('nvtilanne') === '1') {
					$(this).text('Lähetetty').removeClass('btn-default').addClass('btn-success');
				} else {
					$(this).text('Lähetä').removeClass('btn-success');
				}
			});

			$(".esittele_tyotunnit_erikseen").each(function() {
				
				if( $(this).closest('table').closest('tr').prev('.su_lu_tot').find('.fullRivi').find('.fa-retweet').length > 0 )
				{
					$(this).text('Lähetys ei onnistuu. Tunnit ei saa olla eri päivässä.').removeClass('btn-success, btn-default, esittele_tyotunnit_erikseen').addClass('btn-danger');
				} else {

					if ($(this).attr('nvtilanne') === '1') {
						$(this).text('Lähetetty').removeClass('btn-default').addClass('btn-success');
					} else {
						$(this).text('Lähetä').removeClass('btn-success');
					}
				}
			});
		}

		$(".esittele_tyotunnit").click(function() {
			var thisButton = this;
			var json = new Array();
			var object = {};
			$(this).each(function() {
				$.each(this.attributes, function() {
					if (this.specified) {
						if ((this.name !== 'class') && (this.name !== 'nvtilanne')) {
							object[this.name] = this.value;
						}
					}
				});
			});
			json.push(object);
			//console.log( json );
			//return false;	

			$.ajax({
				url: 'hyvaksy_pvm_tid',
				type: "POST",
				data: {
					json: json
				},
				success: function(data) {
					data = JSON.parse(data);
					console.log(data);
					if (data['netvisorOK']) {
						$(thisButton).after('<div class="alert bg-success">' + data['netvisorOK'] + '</div>');
						$(thisButton).attr('NVtilanne', '1');
						LahetaPainike();
					} else if (data['TallennettuMuttaEiLahetetty']) {
						$(thisButton).after('<div class="alert bg-warning">' + data['TallennettuMuttaEiLahetetty'] + '</div>');
					} else if (data['statusError']) {
						$(thisButton).after('<div class="alert bg-danger">' + data + '</div>');
					} else {
						$(thisButton).after('<div class="alert bg-danger">' + JSON.stringify(data) + '</div>');
					}
				}
			});
		});

		$(".esittele_tyotunnit_erikseen").click(function() {

			var thisButton = this;
			var json = new Array();
			var object = {};
			$(this).each(function() {
				$.each(this.attributes, function() {
					if (this.specified) {
						if ((this.name !== 'class') && (this.name !== 'nvtilanne')) {
							object[this.name] = this.value;
						}
					}
				});
			});
			json.push(object);
			//console.log( json );
			//return false;

			$.ajax({
				url: 'lahetanetvisoriin',
				type: "POST",
				data: {
					json: json
				},
				success: function(data) {
					data = JSON.parse(data);
					console.log(data);

					if (data['OK']) {
						$(thisButton).after('<div class="alert bg-success">' + data['OK'] + '</div>');
						$(thisButton).attr('NVtilanne', '1');
					}
					if (data['ERROR'])
						$(thisButton).after('<div class="alert bg-danger">' + data['ERROR'] + '</div>');
				}
			});

		});

		$(".avaaModalFor").click(function() {

			var forThis = $(this).attr("taulu");
			var pvm = $(this).attr("pvm");
			var tid = $(this).attr("tid");

			avaaModalFor(forThis, pvm, tid)
		});


		function avaaModalFor(avaaSen, pvm, tid) {
			$.ajax({
				url: 'korvaus_ylitunnit_ennakko',
				type: "POST",
				data: {
					taulu: avaaSen,
					pvm: pvm,
					tid: tid
				},
				success: function(data) {
					data = JSON.parse(data);
					//console.log(data);
					$('#showres').modal().html(data);
				}
			});
		}


		$('.tv_edit').after('<i class="link fa fa-arrow-right pull-right sirraToteutuun" style="margin-top:2px; font-size: 130%; z-index: 99999999" data-toggle="tooltip" data-placement="left" title="Siirrä toteutuun"></i>');

		$(".sirraToteutuun").tooltip({
			classes: {
				"ui-tooltip": "highlight"
			}
		});

		$(".hyvaksyminen").click(function() {
			if (!confirm('Oletko varmaa?')) {
				return false;
			}
			$.ajax({
				url: 'index',
				type: "POST",
				data: {
					hyvaksyminen: $(this).attr('for'),
					arvo: $(this).attr('arvo'),
					tid: $(this).attr('tid')
				},
				success: function(data) {
					console.log(data);
					window.location.reload();
				}
			});
		});




	});
</script>
