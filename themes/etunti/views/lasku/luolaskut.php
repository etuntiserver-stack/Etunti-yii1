<?php
ini_set('memory_limit', '1024M');
//set_time_limit(300);

$tp_lisatuotteet = array();
?>
        <!-- begin: .tray-center -->
        <div class="tray-center">
	Yhteensä <?=count($asiakkaat_ids)?> kpl.
        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKUTUKSEN AUTOMAATIO ESIKATSELU'); ?></h2>

	</div>

	<center>
		<?php
		if( count($asiakkaat_ids) > 0 ){
			echo CHtml::link(Yii::t('main', 'LÄHETÄ KAIKKI LASKUT'), 
				array('lasku/luolaskut', 
					'filter_tyyppi' => (isset($_GET['filter_tyyppi']))?$_GET['filter_tyyppi']:'',
					'filter_postitoimipaikka' => (isset($_GET['filter_postitoimipaikka']))?$_GET['filter_postitoimipaikka']:'',
					'filter_tyoryhma' => (isset($_GET['filter_tyoryhma']))?$_GET['filter_tyoryhma']:'',
					'filter_asiakasryhma' => (isset($_GET['filter_asiakasryhma']))?$_GET['filter_asiakasryhma']:'',
					'tunnit' => $tunnit,
					'from' => $from,
					'to' => $to,
					'paivays' => $paivays,
					'toimituspaiva' => $toimituspaiva,
					'erapaiva' => $erapaiva,
					'alvsis' => $alvsis,
					'decimal' => $decimal,
					'yrityksen_nimi' => $yrityksen_nimi,
					'viestikenta' => (isset($_GET['viestikenta']))?$_GET['viestikenta']:'',
					'laheta' => true
				), 
				array(
					'class' => 'btn btn-block btn-success myBgColors lahetakaikki',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä')
				)
			);
		}
		?>
	</center>
	<br>
	<?php 
	// <-- Laskunumero netvisorista
	$laskunumero = 1;
	if( $laheta !== null ){
	   if( $this->lastLaskunumero() > 0 ){
		$laskunumero = $this->lastLaskunumero()+0;
	   }
	} 
	//  Laskunumero netvisorista -->
	?>
<p>
<?php
$asiakas_maksuehto 		= $asetukset->asiakas_maksuehto;
$asiakas_laskutus_kanava 	= $asetukset->asiakas_laskutus_kanava;
$asiakas_kirjeenluokka		= $asetukset->asiakas_kirjeenluokka;
$asiakas_viivastyskorko		= $asetukset->asiakas_viivastyskorko;
$palvelu_tyyppi			= $asetukset->palvelu_tyyppi;
$netvisor_kaytto		= $asetukset->netvisor_kaytto;
$iban				= $asetukset->iban;
?>
<?php // holds hover results for invoice row
// result include employee names, planned, tracked and approved hours
// related to the invoice
?>
<div id="hover-track-result" class="hide">
</div>
<div class="container-fluid">
	<?php if( count($asiakkaat_ids) > 0 ): ?>
	<table class="table table-bordered paa_taulu">
	<tr>
	<th>Laskutuksen tiedot</th>
	<th>Laskutuksen rivit</th>
	</tr>
	<?php foreach($asiakkaat_ids as $asiakas_nimi => $item) : ?>
	<?php
	if( !isset($item['asiakas']) ){ continue; }
	$asiakas = $item['asiakas'];
	unset($item['asiakas']);
	?>
	<tr id="<?=$asiakas['id']?>">
	<td width="17%">
		<h3><?php echo CHtml::link($asiakas_nimi, 
				array('/asiakkaat/update', 'id'=>$asiakas['id']), 
				array(
					'data-toggle'=>'tooltip',
					'data-placement'=>'top',
					'target' => '_blank',
					'title'=>Yii::t('main', 'Asiakkaan kortti') 
				)
			); 
		?>
		<?php echo CHtml::link('<i class="fa fa-table" aria-hidden="true"></i>', 
				array('/asiakkaat/showshift', 'id'=>$asiakas['id']), 
				array(
					'class' => 'pull-right',
					'data-toggle'=>'tooltip',
					'data-placement'=>'top',
					'target' => '_blank',
					'title'=>Yii::t('main', 'Näytä tyovuorot') 
				)
			); 
		?></h3>
	<?php
	$yhteensa_total_veroton	= 0;
	$yhteensa_total	= 0;
	$is_ok_lasku 	= true;
	$l_class	= 'class="bg-default"';
	$ep		= '';

	if(empty( $asiakas['maksuehto'] ) and !empty( $asiakas_maksuehto )){
		$asiakas['maksuehto'] = $asiakas_maksuehto;
	}

	if(!empty($asiakas['maksuehto']) and empty($erapaiva)){
		$ep = date("d.m.Y",strtotime($paivays ." +".$asiakas['maksuehto']." day"));
	} elseif(!empty($erapaiva)){
		$ep = $erapaiva;
	}

	if(empty( $asiakas['laskutus_kanava'] ) and !empty( $asiakas_laskutus_kanava )){
		$asiakas['laskutus_kanava'] = $asiakas_laskutus_kanava;
	}
	if(empty( $asiakas['kirjeenluokka'] ) and !empty( $asiakas_kirjeenluokka )){
		$asiakas['kirjeenluokka'] = $asiakas_kirjeenluokka;
	}
	if(empty( $asiakas['viivastyskorko'] ) and !empty( $asiakas_viivastyskorko )){
		$asiakas['viivastyskorko'] = $asiakas_viivastyskorko;
	}
	// we used to include model->id here as well, but that can make the reference number
	// over 20 characters long, which is invalid for netvisor.
	$viitenumero = $this->Viite($asiakas['asiakasnumero']."00".date("md"));

	if( 
		( $asiakas['laskutus_kanava'] == 'sahkoposti' and empty($asiakas['sahkoposti']) )
		or ( $palvelu_tyyppi == 4 and $netvisor_kaytto == 1 and $asiakas['netvisorkey'] == 0 )
	){ 
		$is_ok_lasku 	= false; 
		$l_class	= 'class="alert bg-danger"';
	}
	?>
	<div>
	<p><b><?=Yii::t('main', 'Laskutus kanava')?></b>-<?=(!empty($asiakas['laskutus_kanava']))? Yii::t('main', $asiakas['laskutus_kanava']):''?></p>
	<p><b><?=Yii::t('main', 'Viitenumero')?></b>-<?=$viitenumero?></p>
	<p><b><?=Yii::t('main', 'Päiväys')?></b>-<?=date("d.m.Y",strtotime($paivays))?></p>
	<p><b><?=Yii::t("main", "Toimituspäivä")?></b>-<?=date("d.m.Y",strtotime($toimituspaiva))?></p>
	<p><b><?=Yii::t('main', 'Eräpäivä')?></b>-<?=date("d.m.Y",strtotime($ep))?></p>
	<p><b><?=Yii::t('main', 'Maksuehto')?></b>-<?=(!empty($asiakas['maksuehto']))? $asiakas['maksuehto']:''?></p>
	<p><b><?=Yii::t('main', 'Viivästyskorko')?></b>-<?=(!empty($asiakas['viivastyskorko']))? $asiakas['viivastyskorko']:''?></p>
	<p><b><?=Yii::t('main', 'Lisätietoja laskutuksesta')?></b>: <?=(!empty($asiakas['lisatietoja_laskutuksesta']))? $asiakas['lisatietoja_laskutuksesta']:''?></p>
	 <div <?=$l_class?>>
	 <?=($asiakas['laskutus_kanava'] == 'sahkoposti' and empty($asiakas['sahkoposti']))? '<p>'.Yii::t('main', 'Sähköpostiosoite ei saisi olla tyhjä tässä laskutus kanavassa.').'</p>':''?>
	 <?=( $palvelu_tyyppi == 4 and $netvisor_kaytto == 1 and $asiakas['netvisorkey'] == 0 )? '<p>'.Yii::t('main', 'Asiakas ei vielä saanut netvisorkey. Päivittä tämän asiakkaan tiedot.').'</p>':''?>
	 </div>
	</div>
	</td>
	<td class="lahetys_laatikko" asiakas_id="<?=$asiakas['id']?>" asiakasnumero="<?=$asiakas['asiakasnumero']?>" from="<?=$from?>" to="<?=$to?>">

	<!-- Lahetys Pää lasku -->
	<?php if( $is_ok_lasku and $laheta !== null ){

		$laskunumero++;

		$lasku = new Lasku;
		$lasku->yid = 1;
		$lasku->tilanne = 1;
		$lasku->laskunumero = $laskunumero;
		$lasku->tapahtumapvm = date("Y-m-d H:i:s");
		$lasku->netvisor_dimension_name = $asiakas['netvisor_dimension_name'];
		$lasku->netvisor_dimension_item = $asiakas['netvisor_dimension_item'];
		$lasku->tyyppi = $asiakas['tyyppi'];
		$lasku->yritys = $asiakas['yrityksen_nimi'];
		$lasku->y_tunnus = $asiakas['y_tunnus'];
		$lasku->nimi = $asiakas_nimi;
		$lasku->as_nro = $asiakas['asiakasnumero'];
		$lasku->osoite = $asiakas['osoite'];
		$lasku->toimitusosoite = $asiakas['osoite'];
		$lasku->postinumero = $asiakas['postinumero'];
		$lasku->toimipaikka = $asiakas['kaupunki'];
		$lasku->laskutus = $asiakas['laskutus_kanava'];
		$lasku->sahkoposti = $asiakas['sahkoposti'];
		$lasku->verkkolaskuosoite = $asiakas['verkkolaskuosoite'];
		$lasku->v_tunnus = $asiakas['valittajan_tunnus'];
		$lasku->yhteyshenkilo = $asiakas_nimi;
		$lasku->puhelin = $asiakas['puhelin'];
		$lasku->paivays = $paivays;
		$lasku->toimituspaiva = $toimituspaiva;
		$lasku->erapaiva = $ep;
		$lasku->maksuehto = $asiakas['maksuehto'];
		$lasku->viitenumero = $viitenumero;
		$lasku->yhteensa_total = $yhteensa_total;
		$lasku->saaja_iban = $iban;
		$lasku->laskun_nimetys = 'Lasku';
		$lasku->alv_muoto = $alvsis;
		$lasku->viitemme = $asiakas["invoice_our_reference"] ?? "";
		$lasku->viitenne = $asiakas["invoice_your_reference"] ?? "";
		if(!$lasku->save()){
			print_r($lasku->getErrors());
			exit;
		}
	} ?>
	<!-- / Lahetys -->

		<?php
		$hyv_lista = array();
		if( isset($autolahetteet_asids[$asiakas['id']]['tab_array']) and is_array(json_decode($autolahetteet_asids[$asiakas['id']]['tab_array'], true)) ){
			$hyv_lista 	= json_decode($autolahetteet_asids[$asiakas['id']]['tab_array'], true);
			$al_id		= $autolahetteet_asids[$asiakas['id']]['al_id'];
		} else {
			$hyv_lista = $item;
		}
		?>
		<p class="m_icons"><i class="link fa fa-2x fa-edit"></i></p>
		<div class="row alv_valinta" style="display:none">
		 <div class="col-sm-3">
			<p><select class="form-control lasku_alv_muoto">
			<option value="0" <?=($alvsis == 0)?'selected':''?>>Hinnat ALV 0%</option>
			<option value="1" <?=($alvsis == 1)?'selected':''?>>Hinnat sis. ALV</option>
			</select></p>
		 </div>
		</div>
		<table class="table table-striped rivintaulu">
		<tr>
		<th class="th_tuote">Tuote</th>
		<th>Hinta</th>
		<th width="90">Yksikkö</th>
		<th>Määrä</th>
		<th>ALV</th>
		<th>Veroton</th>
		<th>Yhteensä</th>
		<th>Free text</th>
		</tr>
		<tbody>
		<?php $key = 0; ?>
		<?php foreach($hyv_lista as $mob) : ?>
		<?php
		if( isset($mob['mob_tunnit']) ){
			$mob_tunnit 	= $mob['mob_tunnit'];
			$mob_tunnit_tyovuoroot 	= $mob['mob_tunnit_tyovuoroot'];
			$kohteet 	= $mob['kohteet'];
		}
		if( isset($mob['tyovuoroot']) ){
			$tv_pvm				= $mob['this_pvm'];
			$tv_tid				= $mob['this_tid'];
			$tv_kesto			= $mob['tv_kesto'];

			$toistuva[$asiakas_nimi] 	= $mob['toistuva'];
			$this_id[$asiakas_nimi] 	= $mob['this_id'];
			$tyovuoroot[$asiakas_nimi] 	= $mob['tyovuoroot'];
			$kohteet 			= $mob['kohteet'];
			$mobile 			= $mob['mobile'];
			$toteutuneet 			= $mob['toteutuneet'];
		}
		// <-- Autolahetteet
		if( isset($al_id) and isset($mob['tv_id']) and !isset($mob['mob_tunnit']) and !isset($mob['tyovuoroot'])){
			$al_tv_id[$asiakas_nimi] 	= $mob['tv_id'];
			$get_id 			= $tv_controller[0]->this_id($mob['tv_id']);
			$toistuva[$asiakas_nimi] 	= $get_id['toistuva'];
			$tyovuoroot[$asiakas_nimi] 	= $get_id['model'];
			$tv_pvm				= $get_id['pvm'];
			$tv_tid				= $get_id['tid'];
		}
		?>
		<?php $key++; ?>
		<?php
			$tv_id		= 0;
			$tp_id		= 0;
			$hinta 		= 0;
			$kpl 		= 0;
			$alv 		= 0;
			$rivi_kpl 	= 0;
			$r		= [];
			$freetext 	= '';

			if( isset($mob_tunnit) ){
				$tv_id		= $mob_tunnit['tv_id'];
				$t 		= $this->num(strtotime($mob_tunnit['loppui'])-strtotime($mob_tunnit['aloitan']));
				$r 		= $this->hinnastoHintaat($mob_tunnit_tyovuoroot['tuoteID'], $item, $kohteet, $t, $rivi_kpl); // MOB
			}
			if( !isset($al_tv_id[$asiakas_nimi]) and isset($tyovuoroot[$asiakas_nimi]['kohde']) ){
				$tv_id		= $this_id[$asiakas_nimi];
				$t 		= $this->num($tv_kesto);
				$r 		= $this->hinnastoHintaat($tyovuoroot[$asiakas_nimi]['tuoteID'], $asiakas, $kohteet, $t, $rivi_kpl); // TV
				$tv_vertailu	= $this->TyovuoroMobileVertailu($tyovuoroot[$asiakas_nimi]['kohde'], $tyovuoroot[$asiakas_nimi]['id'], $tv_pvm);
			}

			if( isset($r['tp_id']) ){ $tp_id = $r['tp_id']; }
			if( isset($mob['tp_id']) ){ $tp_id = $mob['tp_id']; } // jos autolahetteet
			if( isset($mob['tv_id']) ){ $tv_id = $mob['tv_id']; }
			if( isset($r['tp_nimike']) ){ $nimike = $r['tp_nimike']; }
			if( isset($mob['nimike']) ){ $nimike = $mob['nimike']; }
			if( isset($r['kpl']) ){ $kpl = $r['kpl']; }
			if( isset($mob['kpl']) ){ $kpl = $mob['kpl']; }
			if( isset($r['hinta']) ){ $hinta = $r['hinta']; }
			if( isset($mob['hinta']) ){ $hinta = $mob['hinta']; }
			if( isset($r['alv']) ){ $alv = $r['alv']; }
			if( isset($mob['alv']) ){ $alv = $mob['alv']; }
			if( isset($r['yksikko']) ){ $yksikko = $r['yksikko']; }
			if( isset($mob['yksikko']) ){ $yksikko = $mob['yksikko']; }
			if( isset($mob['freetext']) ){ $freetext = $mob['freetext']; }

			// <-- TV
			if( !isset($al_tv_id[$asiakas_nimi]) and isset($tyovuoroot[$asiakas_nimi]['tuoteID']) ){ $tp_id = $tyovuoroot[$asiakas_nimi]['tuoteID']; }

			if( isset($r['alvsis']) and $r['alvsis'] == 'nolla'){ $alvsis = 0; }
			if( isset($r['hinta_sis']) and isset($r['alvsis']) and $r['alvsis'] == 'sis'){ $alvsis = 1; $hinta = $r['hinta_sis']; }

			// <-- MOB
			$nimike_append = ' ';
			if( isset($mob_tunnit['aloitan']) and isset($mob_tunnit['kohde_kannasta']) ){
				if(isset($_GET['viestikenta']) and in_array('pvm', $_GET['viestikenta'])){
					$nimike_append .= date("d.m.Y", strtotime($mob_tunnit['aloitan']));
				}
				if(isset($_GET['viestikenta']) and in_array('osoite', $_GET['viestikenta'])){
					if(!empty($nimike_append)){ $nimike_append .= ', '; }
					$nimike_append .= $mob_tunnit['kohde_kannasta'];
				}
			}
			// <-- TV
			if( isset($tyovuoroot[$asiakas_nimi]['id']) and isset($kohteet['osoite']) ){
				if(isset($_GET['viestikenta']) and in_array('pvm', $_GET['viestikenta'])){
					$nimike_append .= $tv_pvm;
				}
				if(isset($_GET['viestikenta']) and in_array('osoite', $_GET['viestikenta'])){
					if(!empty($nimike_append)){ $nimike_append .= ', '; }
					$nimike_append .= $kohteet['osoite'];
				}
			}
			if (empty($nimike_append) || ctype_space($nimike_append))
				$nimike_append = '';
			$nimike .= $nimike_append;

			if( $hinta == 0 ){
				echo '<h1>Hinta ei saa olla nolla.</h1>';
				break;
			}
			// <-- ALV laskin
			$veroton 	= 0;
			$yht 		= 0;
			if(!is_numeric($hinta) or !is_numeric($kpl) or !is_numeric($alv)){
				echo '<div class="alert bg-danger">
					Hinta:'.$hinta.' KPL:'.$kpl.' ALV:'.$alv.'<br>
					<h1>Hinta tai ALV ei saa olla teksti muodossa tai pilkulla.</h1>
				</div>';
				break;
			}
			if( $alvsis == 0 ){
				$laske = ($hinta*$kpl)/100*$alv;
				$veroton = round(($hinta*$kpl), $decimal);
				$yht = $laske+$veroton;
			}
			if( $alvsis == 1 ){
				$yht = $hinta*$kpl;
				$jakaa = '1.'.$alv;
				$l = $yht/$jakaa;
				$veroton = round($l, $decimal);
			}
			//     ALV laskin -->

			$yhteensa_total += $yht;
			$yhteensa_total_veroton += $veroton;

			if( isset($lasku->id) and $tp_id != 0){
			   $lr = new LaskunRivit;
			   $lr->lid	= $lasku->id;
			   $lr->rivi	= $key;
			   $lr->tkoodi	= $nimike;
			   $lr->kpl	= $kpl;
			   $lr->yksikko	= $yksikko;
			   $lr->hinta	= $hinta;
			   $lr->hinta_alv= $yht-$veroton;
			   $lr->veroton	= $veroton;
			   $lr->yhteensa_alv= $yht;
			   $lr->alv	= $alv;
			   $lr->ale	= 0;
			   $lr->tuoteID = $tp_id;
			   $lr->free_text= $freetext;
			   if(!$lr->save()){
				print_r($lr->getErrors());
				exit;
			   }
			}
	        ?>
		<?php if( $tp_id != 0 and $laheta == null ) : ?>
		<?php
		$hyvaksyty_kontentti = '';
		if(!isset($mobile)){
			$hyvaksyty = false;
		}
		// <-- TV
		if($tunnit == 'tv' and isset($mobile)){
		    foreach($mobile as $mobile_item){
			if(isset($mobile_item) and $mobile_item['hyvaksytty'] == ''){
				$hyvaksyty_kontentti .= '<td class="text-danger"><b>Hyväksymättömät luetut tunnit: </b><br>'.$mobile_item['tekijan_nimi'].'<br>'. date("d.m.Y", strtotime($mobile_item['aloitan'])).', '.date("H:i", strtotime($mobile_item['aloitan'])).'-'.date("H:i", strtotime($mobile_item['loppui'])).'</td>';
			}
		    }
		    foreach($toteutuneet as $toteutuneet_item){
			if(isset($toteutuneet_item) and $toteutuneet_item['hyvaksytty'] == ''){
				$hyvaksyty_kontentti .= '<td class="text-danger"><b>Hyväksymättömät toteutuneet tunnit: </b><br>'.$mobile_item['tekijan_nimi'].'<br>'. date("d.m.Y", strtotime($toteutuneet_item['aloitan'])).', '.date("H:i", strtotime($toteutuneet_item['aloitan'])).'-'.date("H:i", strtotime($toteutuneet_item['loppui'])).'</td>';
			}
		    }
		}
		?>
		<tr class="<?=((isset($tv_vertailu) and $tv_vertailu == true)?'text-success':'')?><?=((isset($tv_vertailu) and $tv_vertailu == false)?'text-danger':'')?>">
		<td class="input_nimike" tp_id="<?=$tp_id?>" tv_id="<?=$tv_id?>">(<?=$tp_id?>) <?=$nimike?></td>
		<td class="input_hinta"><?=number_format($hinta, $decimal, ',', ' ')?></td>
		<td class="input_yksikko"><?=$yksikko?></td>
		<td class="input_kpl"><?=$kpl?></td>
		<td class="input_alv"><?=$alv?></td>
		<td class="input_veroton"><?=number_format($veroton, $decimal, ',', ' ')?></td>
		<td class="input_yhteensa"><?=number_format($yht, $decimal, ',', ' ')?></td>
		<td class="input_freetext"><?=$freetext?></td>
		<?=$hyvaksyty_kontentti?>
		</tr>
		<?php endif; ?>

		<!-- Lisatuote -->
		<?php if( isset($mob_tunnit_tyovuoroot['id']) or isset($tyovuoroot[$asiakas_nimi]['lisa_tuotteet']) ) : ?>
		<?php 
			if( isset($mob_tunnit_tyovuoroot['id'])){
				$lisa_tuotteet = json_decode($mob_tunnit_tyovuoroot['lisa_tuotteet'], true); //MOB
			}
			if( isset($tyovuoroot[$asiakas_nimi]['kohde'])){
				$lisa_tuotteet = json_decode($tyovuoroot[$asiakas_nimi]['lisa_tuotteet'], true); //TV
			}
		?>
		<?php if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote']) ) : ?>
		<?php foreach($lisa_tuotteet['tuote'] as $k => $v) : ?>
		<?php $key++; ?>
		<?php
			if( isset($mob_tunnit_tyovuoroot['id']) and isset($tp_lisatuotteet[$mob_tunnit_tyovuoroot['tyopaari']][$mob_tunnit_tyovuoroot['pvm']][$v]) ){ continue; }
			if( isset($tyovuoroot[$asiakas_nimi]['kohde']) and isset($tp_lisatuotteet[$tyovuoroot[$asiakas_nimi]['tyopaari']][$tyovuoroot[$asiakas_nimi]['pvm']][$v]) ){ continue; }

			$r		= [];

			// <-- MOB
			if( isset($mob_tunnit_tyovuoroot['id'])){
				$t 		= json_decode($mob_tunnit_tyovuoroot['lisa_tuotteet'], true)['maara'][$k];
				$rivi_kpl 	= json_decode($mob_tunnit_tyovuoroot['lisa_tuotteet'], true)['maara'][$k];
				$r 		= $this->hinnastoHintaat($v, $item, $kohteet, $t, $rivi_kpl);
			}
			// <-- TV
			if( !isset($al_tv_id[$asiakas_nimi]) and isset($tyovuoroot[$asiakas_nimi]['kohde'])){
				$t 		= json_decode($tyovuoroot[$asiakas_nimi]['lisa_tuotteet'], true)['maara'][$k];
				$rivi_kpl 	= json_decode($tyovuoroot[$asiakas_nimi]['lisa_tuotteet'], true)['maara'][$k];
				$r 		= $this->hinnastoHintaat($v, $item, $kohteet, $t, $rivi_kpl);
			}

			$tp_id		= (( isset($r['tp_id']) )? $r['tp_id']:0);
			$nimike		= (( isset($r['tp_nimike']) )? $r['tp_nimike']:'');
			$kpl 		= (( isset($r['kpl']) )? $r['kpl']:0);
			$hinta 		= (( isset($r['hinta']) )? $r['hinta']:0);
			$alv 		= (( isset($r['alv']) )? $r['alv']:0);
			$yksikko	= (( isset($r['yksikko']) )? $r['yksikko']:'');

			if( isset($r['alvsis']) and $r['alvsis'] == 'nolla'){ $alvsis = 0; }
			if( isset($r['hinta_sis']) and isset($r['alvsis']) and $r['alvsis'] == 'sis'){ $alvsis = 1; $hinta = $r['hinta_sis']; }
			// <-- MOB
			$nimike_append = ' ';
			if( isset($mob_tunnit['kohde_kannasta']) ){
				$nimike_append = ' ';
				if(isset($_GET['viestikenta']) and in_array('pvm', $_GET['viestikenta'])){
					$nimike_append .= date("d.m.Y", strtotime($mob_tunnit['aloitan']));
				}
				if(isset($_GET['viestikenta']) and in_array('osoite', $_GET['viestikenta'])){
					if(!empty($nimike_append)){ $nimike_append .= ', '; }
					$nimike_append .= $mob_tunnit['kohde_kannasta'];
				}
			}
			// <-- TV
			if( isset($tyovuoroot[$asiakas_nimi]['id']) and isset($kohteet['osoite']) ){
				$nimike_append = ' ';
				if(isset($_GET['viestikenta']) and in_array('pvm', $_GET['viestikenta'])){
					$nimike_append .= $tv_pvm;
				}
				if(isset($_GET['viestikenta']) and in_array('osoite', $_GET['viestikenta'])){
					if(!empty($nimike_append)){ $nimike_append .= ', '; }
					$nimike_append .= $kohteet['osoite'];
				}
			}
			if (empty($nimike_append) || ctype_space($nimike_append))
				$nimike_append = '';
			$nimike .= $nimike_append;

			// <-- ALV laskin
			$veroton 	= 0;
			$yht 		= 0;
			if( $alvsis == 0 ){
				$laske = ($hinta*$kpl)/100*$alv;
				$veroton = round(($hinta*$kpl), $decimal);
				$yht = $laske+$veroton;
			}
			if( $alvsis == 1 ){
				$yht = $hinta*$kpl;
				$jakaa = '1.'.$alv;
				$l = $yht/$jakaa;
				$veroton = round($l, $decimal);
			}
			//     ALV laskin -->

			$yhteensa_total += $yht;
			$yhteensa_total_veroton += $veroton;

			if( isset($lasku->id) and $tp_id != 0){
			   $lr = new LaskunRivit;
			   $lr->lid	= $lasku->id;
			   $lr->rivi	= $key;
			   $lr->tkoodi	= $nimike;
			   $lr->kpl	= $kpl;
			   $lr->yksikko	= $yksikko;
			   $lr->hinta	= $hinta;
			   $lr->hinta_alv= $yht-$veroton;
			   $lr->veroton	= $veroton;
			   $lr->yhteensa_alv= $yht;
			   $lr->alv	= $alv;
			   $lr->ale	= 0;
			   $lr->tuoteID = $tp_id;
			   $lr->free_text= $freetext;
			   if(!$lr->save()){
				print_r($lr->getErrors());
				exit;
			   }
			}
	        ?>
		<?php if( $tp_id != 0 and $laheta == null ) : ?>
		<tr>
		<td class="input_nimike" tp_id="<?=$tp_id?>" tv_id="<?=$tv_id?>">(<?=$tp_id?>) <?=$nimike?></td>
		<td class="input_hinta"><?=number_format($hinta, $decimal, ',', ' ')?></td>
		<td class="input_yksikko"><?=$yksikko?></td>
		<td class="input_kpl"><?=$kpl?></td>
		<td class="input_alv"><?=$alv?></td>
		<td class="input_veroton"><?=number_format($veroton, $decimal, ',', ' ')?></td>
		<td class="input_yhteensa"><?=number_format($yht, $decimal, ',', ' ')?></td>
		<td class="input_freetext"><?=$freetext?></td>
		</tr>
		<?php endif; ?>
		<?php 
			// <-- MOB
			if( isset($mob_tunnit_tyovuoroot['tyopaari']) and is_array(json_decode($mob_tunnit_tyovuoroot['tyopaari'], true))){
				$tp_lisatuotteet[$mob_tunnit_tyovuoroot['tyopaari']][$mob_tunnit_tyovuoroot['pvm']][$v] = $asiakas['id'];
			}
			// <-- TV
			if( isset($tyovuoroot[$asiakas_nimi]['tyopaari']) and is_array(json_decode($tyovuoroot[$asiakas_nimi]['tyopaari'], true))){
				$tp_lisatuotteet[$tyovuoroot[$asiakas_nimi]['tyopaari']][$tyovuoroot[$asiakas_nimi]['pvm']][$v] = $asiakas['id'];
			}
		?>
		<?php endforeach; ?>
		<?php endif; ?>
		<?php endif; ?>
		<!-- / Lisatuote -->

		<!-- Update tyovuoro -->
		<?php if( $is_ok_lasku and $laheta !== null and isset($lasku->id)){
			if(isset($tyovuoroot[$asiakas_nimi]['id']) or isset($al_tv_id[$asiakas_nimi])){
				if($toistuva[$asiakas_nimi])
				{
					$tilanne 	= ['laskutettu' => 1, 'lasku_id' => $lasku->id];
					$poisto_by	= 'ByAutolaskutus';
					$tv_controller[0]->VirtualtoTV($tyovuoroot[$asiakas_nimi]['id'], $tv_tid, $tv_pvm, $tilanne, $poisto_by);
				} else {
					$tl = Tyovuoroot::model()->findByPk($tv_id);
					if( isset($tl->id) )
						Tyovuoroot::model()->updateByPk($tl->id, array('laskutettu' => 1, 'lasku_id' => $lasku->id));
				}
			}
		} ?>
		<!-- / Update tyovuoro -->
		<?php endforeach; ?>
		</tbody>
		</table>
	</td>
	</tr>

	<?php if( $is_ok_lasku and $laheta !== null and isset($lasku->id) ){
		Lasku::model()->updateByPk($lasku->id, 
			array(
			'yhteensa_total_verot' => round(($yhteensa_total-$yhteensa_total_veroton), $decimal), 
			'yhteensa_total_veroton' => round($yhteensa_total_veroton, $decimal), 
			'yhteensa_total' => round($yhteensa_total, $decimal),
		));

		// <<- Lahetys Trust
		if(isset($lasku->id) 
			and $lasku->tilanne == 1 
			and $palvelu_tyyppi == 2 
		){
			$l = Lasku::model()->findByPk($lasku->id); 
			$resp = $this->finvoiceAuto($l->id, 'finvoiceTrust');
			echo $resp;
		}
		// Lahetys Trust -->

		// <-- Update autolahetteet
		if( $is_ok_lasku and $laheta !== null and isset($lasku->id) and isset($al_id) ){
			Autolahetteet::model()->updateByPk($al_id, array('lasku_id' => $lasku->id, 'laskutettu' => 1));
		}
		// Update autolahetteet -->

		// <<- Lahetys Netvisor
		if(isset($lasku->id) 
			and $lasku->tilanne == 1 
			and $palvelu_tyyppi == 4
			and $netvisor_kaytto == 1
		){
			$return = $this->lahetaNetvisoriin($lasku->id);
			if( $return != false ){
				$criteria=new CDbCriteria;
				$criteria->condition = " lasku_id='".$lasku->id."' AND peruutettu != 1 ";
				Tyovuoroot::model()->updateAll(array('laskutettu' => '1'), $criteria);
				if( $laheta and $ajax == true ){
					echo '<input type="text" class="lahetetty" value="'.$asiakas['id'].'" asiakas_nimi="'.$asiakas_nimi.'">';
				}
			}
		}
		// Lahetys Netvisor -->
	} ?>

	<tr class="painikkeet">
	<!-- Painikkeet -->
	<?php if($is_ok_lasku): ?>
		<th>
		<?php
			echo CHtml::link(Yii::t('main', 'Lähetä lasku'), 
				array('lasku/luolaskut', 
					'tunnit' => $tunnit, 
					'from' => $from, 
					'to' => $to, 
					'alvsis' => $alvsis, 
					'asiakas_id' => $asiakas['id'], 
					'paivays' => $paivays, 
					'toimituspaiva' => $toimituspaiva,
					'erapaiva' => $erapaiva, 
					'decimal' => $decimal,
					'viestikenta' => (isset($_GET['viestikenta']))?$_GET['viestikenta']:'',
					'laheta' => true,
					'ajax' => true
				), 
				array(
					'class' => 'btn btn-block btn-success myBgColors ajax_lahetys',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä')
				)
			); 
		?>
		</th>
		<th>
			<button class="pull-right btn btn-success myBgColors hover-track" 
				data-from="<?=$from?>" data-to="<?=$to?>" data-client="<?=$asiakas["id"]?>">
				<i class="fa fa-hourglass"></i>
			</button>
		    <h3><?=Yii::t('main', 'Yhteensä')?>: <span class="yhteensa_last"><?=number_format($yhteensa_total, $decimal, ',', ' ')?></span>&euro;</h3>
		</th>
	<?php else: ?>
		<tr><th><button class="btn btn-block btn-danger">Lasku ei mennyt läpi</button></th></tr>
	<?php endif; ?>
	<!-- / Painikkeet -->
	</tr>
	<?php endforeach; ?>
	</table>
	<?php endif; // count($lista) > 0 ?>

	<?php 
	if( $laheta and $ajax == null ){
		$this->redirect(array('/lasku/auto'));
	}
	?>

</div>
</p>

<script type="text/javascript">
$(document).ready(function(){

  $(".ajax_lahetys").click(function(e){
	$(this).remove();
	e.preventDefault();
	if(!confirm('Oletko varma')){
		return false;
	} else {
    	  $.ajax({
           url: $(this).attr('href'),
           success: function(data){
        	var parsedResponse = $.parseHTML(data);
	        var result = parseInt($(parsedResponse).find(".lahetetty").val());
		var asiakas_nimi = $(parsedResponse).find(".lahetetty").attr('asiakas_nimi');
		if( result > 0 ){
			$('#' + result).next('.painikkeet').remove();
			$('#' + result).replaceWith('<tr><td class="bg-success"><h4>' + asiakas_nimi + ' - Lähetetty</h4></td><td></td></tr>');
	  		console.log( result );
		} else {
			console.log(parsedResponse);
		}
           }
    	  });
	}
  });

  $(".lahetakaikki").click(function(e){
	$(this).remove();
	e.preventDefault();
	if(!confirm('Oletko varma')){
		return false;
	} else {
		window.location.href=$(this).attr('href');
	}
  });

  // <-- muokkaus
  $(document).delegate(".uusirivi","click",function(){
	lista = '<select class="form-control lista" name="yksikko" ><?=$this->yksikkot(null)?></select>';
	$(this).closest('td').find('.rivintaulu tbody tr').last().after(''+
		'<tr>' +
		'<td class="poisto_td"><i class="link fa fa-2x fa-trash"></i></td>' +
		'<td class="input_nimike">' +
		'<div class="row"><div class="col-sm-2">' +
		'<select class="form-control tuotelista"><option value=></option><?=$this->tuotteetLista("", "")?></select>' +
		'</div><div class="col-sm-10">' +
		'<input type="text" name="nimike" class="form-control">' +
		'</div>' +
		'</td>' +
		'<td class="input_hinta"><input type="text" name="hinta" class="form-control" placeholder="Hinta"></td>' +
		'<td class="input_yksikko">'+ lista +'</td>' +
		'<td class="input_kpl"><input type="number" name="kpl" class="form-control" value="1"></td>' +
		'<td class="input_alv"><input type="number" name="alv" class="form-control"></td>' +
		'<td class="input_veroton"><input type="number" name="veroton"  class="form-control" readonly></td>' +
		'<td class="input_yhteensa"><input type="number" name="yhteensa" class="form-control" readonly></td>' +
		'<td class="input_freetext"><input type="text" name="freetext" class="form-control" placeholder="Teksti"></td>' +
		'</tr>'
	);
  });

  $(document).delegate(".tuotelista","change",function(){
    var thisTR = $(this).closest('tr');
    var thisTD = $(this).closest('td');
    var asiakasnumero = $(this).closest('.lahetys_laatikko').attr('asiakasnumero');
    $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/valitsetuote',
           type: "POST",
           data: { tuoteID : $('option:selected', this).val(), asiakas_nro : asiakasnumero },
           success: function(data){
		var sp = JSON.parse(data);
		//console.log(data)
		if(sp['id'])
		{
			thisTD.attr('tp_id', sp['id']);
			thisTD.find('input').val(sp['tuotenimi']);
			thisTR.find('input[name=hinta]').val(sp['hinta_alv_0']);
			thisTR.find('input[name=yksikko]').val(sp['yksikko']);
			thisTR.find('input[name=alv]').val(sp['alv']).change();
		}

           }
    });
  });

  $(document).delegate(".fa-edit","click",function(){
    $(this).removeClass('fa-edit').addClass('fa-save');
    $(this).closest('.m_icons').append('<i class="link fa fa-2x fa-plus uusirivi" style="margin-left:10px"></i>');
    $(this).closest('td').find('.th_tuote').before('<th class="th_poisto"></th>');
    $(this).closest('td').find('.alv_valinta').show(370);
    $( $(this).closest('tr').find('.rivintaulu td.input_nimike') ).each(function( index ) {
	$(this).replaceWith('' +
		'<td class="poisto_td"><i class="link fa fa-2x fa-trash"></i></td>' +
		'<td class="input_nimike" tp_id="'+ $(this).attr('tp_id') +'" tv_id="'+ $(this).attr('tv_id') +'">' + 
		'<div class="row"><div class="col-sm-2">' +
		'<select class="form-control tuotelista"><?=$this->tuotteetLista("'+ $(this).attr('tp_id') +'", "'+ $(this).text() +'")?></select>' +
		'</div><div class="col-sm-10">' +
		'<input type="text" name="nimike" class="form-control" value="'+ $(this).text() +'">' +
		'</div>' +
		'</td>'
	);
    });
    $( $(this).closest('td').find('.rivintaulu td.input_hinta') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_hinta" width="110"><input type="text" name="hinta" class="form-control" value="'+ todigit +'"></td>');
    });
    $( $(this).closest('td').find('.rivintaulu td.input_yksikko') ).each(function( index ) {
	lista = '<select class="form-control lista" name="yksikko"><?=$this->yksikkot("'+ $(this).text() +'")?></select>';
	$(this).replaceWith('<td class="input_yksikko">'+ lista +'</td>');
    });
    $( $(this).closest('td').find('.rivintaulu td.input_kpl') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_kpl" width="100"><input type="number" name="kpl" class="form-control" value="'+ todigit +'"></td>');
    });
    $( $(this).closest('td').find('.rivintaulu td.input_alv') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_alv" width="100"><input type="number" name="alv" class="form-control" value="'+ todigit +'"></td>');
    });
    $( $(this).closest('td').find('.rivintaulu td.input_veroton') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_veroton" width="100"><input type="number" name="veroton" class="form-control" value="'+ todigit +'" readonly></td>');
    });
    $( $(this).closest('td').find('.rivintaulu td.input_yhteensa') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_yhteensa" width="100"><input type="number" name="yhteensa" class="form-control" value="'+ todigit +'" readonly></td>');
    });
    $( $(this).closest('td').find('.rivintaulu td.input_freetext') ).each(function( index ) {
	$(this).replaceWith('<td class="input_freetext"><input type="text" name="freetext" class="form-control" value="'+ $(this).text() +'"></td>');
    });
  });

  $(document).delegate(".fa-save","click",function(){

    var tab_array = [];
    $(this).closest('td').find('.rivintaulu tbody>tr').each(function (i, row) {
	var tableTds = {};
        $(this).find("td input,td select").each(function(i, cell){
	    if( $(cell).hasClass('tuotelista') ){
           	tableTds['tp_id'] = $('option:selected', cell).val();
           	tableTds['tv_id'] = $(cell).closest('td').attr('tv_id');
           	tableTds['nimike'] = $(cell).closest('td').find('input[name=nimike]').val();
	    } else {
		cell_name = $(cell).attr('name');
           	tableTds[cell_name] = $(cell).val();
	    }
        });
	if( tableTds['tp_id'] ){
            tab_array.push(tableTds);
	}
    });
    console.log(tab_array);

    var asiakas_id = $(this).closest('.lahetys_laatikko').attr('asiakas_id');
    var from = $(this).closest('.lahetys_laatikko').attr('from');
    var to = $(this).closest('.lahetys_laatikko').attr('to');
    if(tab_array.length == 0){ 
	alert('Virhe. Ei löytyy yhtään riveja.');
	return false;
    }
    $.ajax({
           url: 'insert_lahete',
	   type:'POST',
	   data: { asiakas_id : asiakas_id, from : from, to : to, tab_array : tab_array, alvsis : '<?=$alvsis?>' },
           success: function(data){
        	console.log(data);
		if(data['error']){
	    		window.location.href=location.protocol + "//" + location.host + '/index.php/user/logout';
		}
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
    });

    $(this).removeClass('fa-save').addClass('fa-edit');
    $(this).closest('td').find('.th_poisto').remove();
    $(this).closest('td').find('.alv_valinta').hide(370);
    $(this).next('.uusirivi').remove();
    $( $(this).closest('table').find('.rivintaulu td.input_nimike') ).find('input').each(function( index ) {
	$(this).closest('tr').find('.poisto_td').remove();
	$(this).closest('td').find('select').remove();
	$(this).closest('td').replaceWith('<td class="input_nimike" tp_id="'+ $(this).closest('td').attr('tp_id') +'" tv_id="'+ $(this).closest('td').attr('tv_id') +'">'+ $(this).val()+ '</td>');
    });
    $( $(this).closest('td').find('.rivintaulu td.input_hinta') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('td').find('.rivintaulu td.input_yksikko') ).find('select').each(function( index ) {
	todigit = $(this).val();
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('td').find('.rivintaulu td.input_kpl') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('td').find('.rivintaulu td.input_alv') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('td').find('.rivintaulu td.input_veroton') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('td').find('.rivintaulu td.input_yhteensa') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('td').find('.rivintaulu td.input_freetext') ).find('input').each(function( index ) {
	$(this).replaceWith($(this).val());
    });
  });

  $(document).delegate("input[type=number], input[name=hinta]","keyup change paste",function(){
  	var alvsis = parseInt($(this).closest('.lahetys_laatikko').find('.lasku_alv_muoto').val());
	var hinta_alv_0 = 0;
	var alv = 0;
	var kpl = 0;
	var laske = 0;
	var veroton = 0;
	var yhteensa = 0;
	var yhteensa_last = 0;

	if(isNaN(parseFloat($(this).closest('tr').find('.input_hinta input').val())) || isNaN(parseFloat($(this).closest('tr').find('.input_alv input').val())) || isNaN(parseFloat($(this).closest('tr').find('.input_kpl input').val()))){
		return false;
	}

	hinta_alv_0 = parseFloat($(this).closest('tr').find('.input_hinta input').val());
	alv = parseFloat($(this).closest('tr').find('.input_alv input').val());
	kpl = parseFloat($(this).closest('tr').find('.input_kpl input').val());

	if( alvsis == '0'){
		laske = (hinta_alv_0*kpl)/100*alv;
		veroton = hinta_alv_0*kpl;
		yhteensa = laske+veroton;
	}
	if( alvsis == '1'){
		yhteensa = hinta_alv_0*kpl;
		var jakaa = '1.'+alv;
		var l = yhteensa/parseFloat(jakaa);
		veroton = l;
		laske = yhteensa-veroton;
	}

	parseFloat($(this).closest('tr').find('.input_veroton input').val(veroton.toFixed(2)));
	parseFloat($(this).closest('tr').find('.input_yhteensa input').val(yhteensa.toFixed(2)));

	$(this).closest('tr').find('.input_yhteensa input').each(function( index ) {
		yhteensa_last += parseFloat($(this).val());
	});
	parseFloat($(this).parents('tr').next('tr').find('.yhteensa_last').text(yhteensa_last.toFixed(2).replace(/\./g, ',')));
	return false;
  });

  $(document).delegate(".fa-trash","click",function(){
	$(this).closest('tr').remove();
	$('.input_alv input').change();
	return false;
  });
  //     muokkaus -->

  // hover track info stuff
  let lastSearch = {};
  let isSearching = false;

  const handlerIn = (e) => {
	const target = event.target;
	if(target) {
		const data = $(target).data();
		const trackResult = $("#hover-track-result");
		
		// don't execute search (again) if we're using the same data
		// but do show last searches results.
		if(lastSearch.from === data.from 
			&& lastSearch.to === data.to 
			&& lastSearch.client === data.client) {
				if(!isSearching) {
					trackResult.removeClass("hide");
				}
				return;
			}

		// table rows id attr is the clients ID
		const pos = $("#" + data.client).position();

		trackResult.css("top", pos.top);
		trackResult.css("left", pos.left);
		lastSearch = {from: data.from, to: data.to, client: data.client};
		let url = "/index.php/lasku/hovertrack?";
		url += "from=" + data.from;
		url += "&to=" + data.to;
		url += "&client_id=" + data.client;
		isSearching = true;
		trackResult.load(url, (fulfil) => {
			trackResult.removeClass("hide");
			isSearching = false;
		});
	}
  };

  const handlerOut = (e) => {
	$("#hover-track-result").addClass("hide");
  };

  $(".hover-track").mouseenter(handlerIn).mouseleave(handlerOut);

});
</script>
