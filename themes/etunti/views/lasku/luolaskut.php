<?php
	$tp_lisatuotteet = array();
?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKUTUKSEN AUTOMAATIO ESIKATSELU'); ?></h2>

	</div>

	<center>
		<?php
		if( count($lista) > 0 ){
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
					'erapaiva' => $erapaiva,
					'alvsis' => $alvsis,
					'laheta' => true
				), 
				array(
					'class' => 'btn btn-block btn-success lahetakaikki',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä')
				)
			);
		}
		?>
	</center>
	<br>
<p>
<div class="container-fluid">
	<?php if( count($lista) > 0 ): ?>
	<table class="table table-bordered paa_taulu">
	<tr>
	<th>Laskutuksen tiedot</th>
	<th>Laskutuksen rivit</th>
	</tr>
	<?php foreach($lista as $item) : ?>
	<tr>
	<td width="17%">
	<h3><?=$item->Fullname?></h3>
	<?php
	$yhteensa_total_veroton	= 0;
	$yhteensa_total	= 0;
	$is_ok_lasku 	= true;
	$l_class	= 'class="bg-default"';
	$ep		= '';

	if(isset($asetukset->id) and empty( $item->maksuehto ) and !empty( $asetukset->asiakas_maksuehto )){
		$item->maksuehto = $asetukset->asiakas_maksuehto;
	}

	if(!empty($item->maksuehto) and empty($erapaiva)){
		$ep = date("d.m.Y",strtotime($paivays ." +$item->maksuehto day"));
	} elseif(!empty($erapaiva)){
		$ep = $erapaiva;
	}

	if(isset($asetukset->id) and empty( $item->laskutus_kanava ) and !empty( $asetukset->asiakas_laskutus_kanava )){
		$item->laskutus_kanava = $asetukset->asiakas_laskutus_kanava;
	}
	if(isset($asetukset->id) and empty( $item->kirjeenluokka ) and !empty( $asetukset->asiakas_kirjeenluokka )){
		$item->kirjeenluokka = $asetukset->asiakas_kirjeenluokka;
	}
	if(isset($asetukset->id) and empty( $item->viivastyskorko ) and !empty( $asetukset->asiakas_viivastyskorko )){
		$item->viivastyskorko = $asetukset->asiakas_viivastyskorko;
	}


	if( 
		( $item->laskutus_kanava == 'sahkoposti' and empty($item->sahkoposti) )
		or ( $asetukset->palvelu_tyyppi == 4 and $asetukset->netvisor_kaytto == 1 and $item->netvisorkey == 0 )
	){ 
		$is_ok_lasku 	= false; 
		$l_class	= 'class="alert bg-danger"';
	}
	?>
	<div>
	<p><b><?=Yii::t('main', 'Laskutus kanava')?></b>-<?=(!empty($item->laskutus_kanava))? Yii::t('main', $item->laskutus_kanava):''?></p>
	<p><b><?=Yii::t('main', 'Päiväys')?></b>-<?=date("d.m.Y",strtotime($paivays))?></p>
	<p><b><?=Yii::t('main', 'Eräpäivä')?></b>-<?=date("d.m.Y",strtotime($ep))?></p>
	<p><b><?=Yii::t('main', 'Maksuehto')?></b>-<?=(!empty($item->maksuehto))? $item->maksuehto:''?></p>
	<p><b><?=Yii::t('main', 'Viivästyskorko')?></b>-<?=(!empty($item->viivastyskorko))? $item->viivastyskorko:''?></p>
	 <div <?=$l_class?>>
	 <?=($item->laskutus_kanava == 'sahkoposti' and empty($item->sahkoposti))? '<p>'.Yii::t('main', 'Sähköpostiosoite ei saisi olla tyhjä tässä laskutus kanavassa.').'</p>':''?>
	 <?=( $asetukset->palvelu_tyyppi == 4 and $asetukset->netvisor_kaytto == 1 and $item->netvisorkey == 0 )? '<p>'.Yii::t('main', 'Asiakas ei vielä saanut netvisorkey. Päivittä tämän asiakkaan tiedot.').'</p>':''?>
	 </div>
	</div>
	</td>
	<td class="lahetys_laatikko" asiakas_id="<?=$item->id?>" asiakasnumero="<?=$item->asiakasnumero?>" from="<?=$from?>" to="<?=$to?>">

	<!-- Lahetys Pää lasku -->
	<?php if( $is_ok_lasku and $laheta !== null ){

		$laskunumero = '';
		if($asetukset->lasku_laskunumero == 1){
			$criteria = new CDbCriteria();
       			$criteria->select = " id, MAX(ABS(laskunumero)) as laskunumero ";
			$vm = Lasku::model()->find($criteria);
			if( isset($vm->id) ){ $laskunumero = $vm->laskunumero+1; }
		}

		$lasku = new Lasku;
		$lasku->yid = 1;
		$lasku->tilanne = 1;
		$lasku->laskunumero = $laskunumero;
		$lasku->tapahtumapvm = date("Y-m-d H:i:s");
		$lasku->netvisor_dimension_name = $item->netvisor_dimension_name;
		$lasku->netvisor_dimension_item = $item->netvisor_dimension_item;
		$lasku->tyyppi = $item->tyyppi;
		$lasku->yritys = $item->yrityksen_nimi;
		$lasku->y_tunnus = $item->y_tunnus;
		$lasku->nimi = $item->yhteyshenkilo;
		$lasku->as_nro = $item->asiakasnumero;
		$lasku->osoite = $item->osoite;
		$lasku->toimitusosoite = $item->osoite;
		$lasku->postinumero = $item->postinumero;
		$lasku->toimipaikka = $item->kaupunki;
		$lasku->laskutus = $item->laskutus_kanava;
		$lasku->sahkoposti = $item->sahkoposti;
		$lasku->verkkolaskuosoite = $item->verkkolaskuosoite;
		$lasku->v_tunnus = $item->valittajan_tunnus;
		$lasku->yhteyshenkilo = $item->yhteyshenkilo;
		$lasku->puhelin = $item->puhelin;
		$lasku->paivays = $paivays;
		$lasku->erapaiva = $ep;
		$lasku->maksuehto = $item->maksuehto;
		$lasku->viitenumero = $this->Viite($item->asiakasnumero."00".$item->id);
		$lasku->yhteensa_total = $yhteensa_total;
		$lasku->saaja_iban = $asetukset->iban;
		$lasku->laskun_nimetys = 'Lasku';
		$lasku->alv_muoto = $alvsis;
		if(!$lasku->save()){
			print_r($lasku->getErrors());
		}
	} ?>
	<!-- / Lahetys -->

		<?php
		$hyv_lista = array();
		$criteria = new CDbCriteria();
		$criteria->condition = " from_date='".$from."' AND to_date='".$to."' AND asiakas_id='".$item->id."' ";
		$al = Autolahetteet::model()->find($criteria);
		if( isset($al->id) and is_array(json_decode($al->tab_array, true)) ){
			$hyv_lista = json_decode($al->tab_array, true);
		} else {
			$hyv_lista = $this->hyvaksyttyListaByAsiakas($item->id, $from, $to, $tunnit);
		}
		?>
		<p class="m_icons"><i class="link fa fa-2x fa-edit"></i></p>
		<div class="row alv_valinta" style="display:none">
		 <div class="col-sm-3">
			<p><select class="form-control lasku_alv_muoto">
			<option value="0">Hinnat ALV 0%</option>
			<option value="1">Hinnat sis. ALV</option>
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
		<?php $key++; ?>
		<?php
			$tv_id		= 0;
			$tp_id		= 0;
			$hinta 		= 0;
			$kpl 		= 0;
			$alv 		= 0;
			$rivi_kpl 	= 0;
			$r		= [];

			if( isset($mob->tv_id) ){
				$tv_id		= $mob->tv_id;
				$t 		= $this->num(strtotime($mob->loppui)-strtotime($mob->aloitan));
				$r 		= $this->hinnastoHintaat($mob->tyovuoroot->tuoteID, $item, $mob->kohteet, $t, $rivi_kpl); // MOB
			}
			if( isset($mob->kohde) ){
				$tv_id		= $mob->id;
				$t 		= $this->num(strtotime($mob->loppu)-strtotime($mob->alku));
				$r 		= $this->hinnastoHintaat($mob->tuoteID, $item, $mob->kohteet, $t, $rivi_kpl); // TV
			}

			if( isset($r['tp_id']) ){ $tp_id = $r['tp_id'];	}
			if( isset($mob['tp_id']) ){ $tp_id = $mob['tp_id']; } // jos autolahetteet
			if( isset($mob->tuoteID) ){ $tp_id = $mob->tuoteID; }
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
			if( isset($mob->kohde_kannasta) ){ $freetext = $mob->kohde_kannasta.' - '.date("d.m.Y", strtotime($mob->aloitan)); }
			if( isset($mob['freetext']) ){ $freetext = $mob['freetext']; }
			if( isset($mob->kohde) and isset($mob->kohteet->osoite) ){ $freetext = $mob->kohteet->osoite.' - '.$mob->pvm; } //TV

			// <-- ALV laskin
			$veroton 	= 0;
			$yht 		= 0;
			if( $alvsis == 0 ){
				$laske = ($hinta*$kpl)/100*$alv;
				$veroton = round(($hinta*$kpl), 2);
				$yht = $laske+$veroton;
			}
			if( $alvsis == 1 ){
				$yht = $hinta*$kpl;
				$jakaa = '1.'.$alv;
				$l = $yht/$jakaa;
				$veroton = round($l, 2);
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
			   }
			}
	        ?>
		<?php if( $tp_id != 0 and $laheta == null ) : ?>
		<tr>
		<td class="input_nimike" tp_id="<?=$tp_id?>" tv_id="<?=$tv_id?>"><?=$nimike?></td>
		<td class="input_hinta"><?=number_format($hinta, 2, ',', ' ')?></td>
		<td class="input_yksikko"><?=$yksikko?></td>
		<td class="input_kpl"><?=$kpl?></td>
		<td class="input_alv"><?=$alv?></td>
		<td class="input_veroton"><?=number_format($veroton, 2, ',', ' ')?></td>
		<td class="input_yhteensa"><?=number_format($yht, 2, ',', ' ')?></td>
		<td class="input_freetext"><?=$freetext?></td>
		</tr>
		<?php endif; ?>

		<!-- Lisatuote -->
		<?php if( isset($mob->id) ) : ?>
		<?php 
			if( isset($mob->tv_id)){
				$lisa_tuotteet = json_decode($mob->tyovuoroot->lisa_tuotteet, true); //MOB
			}
			if( isset($mob->kohde)){
				$lisa_tuotteet = json_decode($mob->lisa_tuotteet, true); //TV
			}
		?>
		<?php if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote']) ) : ?>
		<?php foreach($lisa_tuotteet['tuote'] as $k => $v) : ?>
		<?php $key++; ?>
		<?php
			if( isset($mob->tv_id) and isset($tp_lisatuotteet[$mob->tyovuoroot->tyopaari][$mob->tyovuoroot->pvm][$v]) ){ continue; }
			if( isset($mob->kohde) and isset($tp_lisatuotteet[$mob->tyopaari][$mob->pvm][$v]) ){ continue; }

			if( isset($mob->tv_id)){
				$t 		= json_decode($mob->tyovuoroot->lisa_tuotteet, true)['maara'][$k];
				$rivi_kpl 	= json_decode($mob->tyovuoroot->lisa_tuotteet, true)['maara'][$k]; //MOB
			}
			if( isset($mob->kohde)){
				$t 		= json_decode($mob->lisa_tuotteet, true)['maara'][$k];
				$rivi_kpl 	= json_decode($mob->lisa_tuotteet, true)['maara'][$k]; // TV
			}
			$r		= [];
			$r 		= $this->hinnastoHintaat($v, $item, $mob->kohteet, $t, $rivi_kpl);
			$tp_id		= (( isset($r['tp_id']) )? $r['tp_id']:0);
			$nimike		= (( isset($r['tp_nimike']) )? $r['tp_nimike']:'');
			$kpl 		= (( isset($r['kpl']) )? $r['kpl']:0);
			$hinta 		= (( isset($r['hinta']) )? $r['hinta']:0);
			$alv 		= (( isset($r['alv']) )? $r['alv']:0);
			$yksikko	= (( isset($r['yksikko']) )? $r['yksikko']:'');
			if( isset($mob->kohde_kannasta) ){ $freetext = $mob->kohde_kannasta.' - '.date("d.m.Y", strtotime($mob->aloitan)); }
			if( isset($mob->kohde) and isset($mob->kohteet->osoite) ){ $freetext = $mob->kohteet->osoite.' - '.$mob->pvm; } //TV

			// <-- ALV laskin
			$veroton 	= 0;
			$yht 		= 0;
			if( $alvsis == 0 ){
				$laske = ($hinta*$kpl)/100*$alv;
				$veroton = round(($hinta*$kpl), 2);
				$yht = $laske+$veroton;
			}
			if( $alvsis == 1 ){
				$yht = $hinta*$kpl;
				$jakaa = '1.'.$alv;
				$l = $yht/$jakaa;
				$veroton = round($l, 2);
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
			   }
			}
	        ?>
		<?php if( $tp_id != 0 and $laheta == null ) : ?>
		<tr>
		<td class="input_nimike" tp_id="<?=$tp_id?>" tv_id="<?=$tv_id?>"><?=$nimike?></td>
		<td class="input_hinta"><?=number_format($hinta, 2, ',', ' ')?></td>
		<td class="input_yksikko"><?=$yksikko?></td>
		<td class="input_kpl"><?=$kpl?></td>
		<td class="input_alv"><?=$alv?></td>
		<td class="input_veroton"><?=number_format($veroton, 2, ',', ' ')?></td>
		<td class="input_yhteensa"><?=number_format($yht, 2, ',', ' ')?></td>
		<td class="input_freetext"><?=$freetext?></td>
		</tr>
		<?php endif; ?>
		<?php 
			if( isset($mob->kohde_kannasta) and isset($mob->tyovuoroot) and is_array(json_decode($mob->tyovuoroot->tyopaari, true))){
				$tp_lisatuotteet[$mob->tyovuoroot->tyopaari][$mob->tyovuoroot->pvm][$v] = $item->id; //MOB
			}
			if( isset($mob->kohde) and is_array(json_decode($mob->tyopaari, true))){
				$tp_lisatuotteet[$mob->tyopaari][$mob->pvm][$v] = $item->id; //TV
			}
		?>
		<?php endforeach; ?>
		<?php endif; ?>
		<?php endif; ?>
		<!-- / Lisatuote -->

		<!-- Update tyovuoro -->
		<?php if( $is_ok_lasku and $laheta !== null and isset($lasku->id) and $tv_id > 0 ){
			$tl = Tyovuoroot::model()->findByPk($tv_id);
			if( isset($tl->id) ){
			   Tyovuoroot::model()->updateByPk($tl->id, array('lasku_id' => $lasku->id));
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
			'yhteensa_total_verot' => round(($yhteensa_total-$yhteensa_total_veroton), 2), 
			'yhteensa_total_veroton' => round($yhteensa_total_veroton, 2), 
			'yhteensa_total' => $yhteensa_total
		));

		// <<- Lahetys Trust
		if(isset($lasku->id) 
			and $lasku->tilanne == 1 
			and $asetukset->palvelu_tyyppi == 2 
		){
			$l = Lasku::model()->findByPk($lasku->id); 
			$resp = $this->finvoiceAuto($l->id, 'finvoiceTrust');
			echo $resp;
		}
		// Lahetys Trust -->

		// <<- Lahetys Netvisor
		if(isset($lasku->id) 
			and $lasku->tilanne == 1 
			and $asetukset->palvelu_tyyppi == 4
			and $asetukset->netvisor_kaytto == 1
		){
			$return = $this->lahetaNetvisoriin($lasku->id);
			if( $return != false ){
				$criteria=new CDbCriteria;
				$criteria->condition = " lasku_id='".$lasku->id."' AND peruutettu=0 ";
				Tyovuoroot::model()->updateAll(array('laskutettu' => '1'), $criteria);
			}
		}
		// Lahetys Netvisor -->
	} ?>

	<tr>
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
					'asiakas_id' => $item->id, 
					'paivays' => $paivays, 
					'erapaiva' => $erapaiva, 
					'laheta' => true
				), 
				array(
					'class' => 'btn btn-block btn-success',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä')
				)
			); 
		?>
		</th>
		<th>
		    <h3><?=Yii::t('main', 'Yhteensä')?>: <span class="yhteensa_last"><?=number_format($yhteensa_total, 2, ',', ' ')?></span>&euro;</h3>
		</th>
	<?php else: ?>
		<tr><th><button class="btn btn-block btn-danger">Lasku ei mennyt läpi</button></th></tr>
	<?php endif; ?>
	<!-- / Painikkeet -->
	</tr>
	<?php endforeach; ?>
	</table>
	<?php endif; // count($lista) > 0 ?>

	<?php if( $laheta ){
		$this->redirect(array('/lasku/auto'));
	} ?>


</div>
</p>

<script type="text/javascript">
$(document).ready(function(){

  $(".lahetakaikki").click(function(e){
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
		'<td class="input_hinta"><input type="number" name="hinta" class="form-control" placeholder="Hinta"></td>' +
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
    $( $(this).closest('table').find('.rivintaulu td.input_nimike') ).each(function( index ) {
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
    $( $(this).closest('table').find('.rivintaulu td.input_hinta') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_hinta" width="110"><input type="number" name="hinta" class="form-control" value="'+ todigit +'"></td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_yksikko') ).each(function( index ) {
	lista = '<select class="form-control lista" name="yksikko"><?=$this->yksikkot("'+ $(this).text() +'")?></select>';
	$(this).replaceWith('<td class="input_yksikko">'+ lista +'</td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_kpl') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_kpl" width="100"><input type="number" name="kpl" class="form-control" value="'+ todigit +'"></td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_alv') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_alv" width="100"><input type="number" name="alv" class="form-control" value="'+ todigit +'"></td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_veroton') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_veroton" width="100"><input type="number" name="veroton" class="form-control" value="'+ todigit +'" readonly></td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_yhteensa') ).each(function( index ) {
	todigit = $(this).text().replace(/\,/g, '.');
	$(this).replaceWith('<td class="input_yhteensa" width="100"><input type="number" name="yhteensa" class="form-control" value="'+ todigit +'" readonly></td>');
    });
    $( $(this).closest('table').find('.rivintaulu td.input_freetext') ).each(function( index ) {
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

    $.ajax({
           url: 'insert_lahete',
	   type:'POST',
	   data: { asiakas_id : asiakas_id, from : from, to : to, tab_array : tab_array },
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
    $( $(this).closest('table').find('.rivintaulu td.input_hinta') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('table').find('.rivintaulu td.input_yksikko') ).find('select').each(function( index ) {
	todigit = $(this).val();
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('table').find('.rivintaulu td.input_kpl') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('table').find('.rivintaulu td.input_alv') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('table').find('.rivintaulu td.input_veroton') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('table').find('.rivintaulu td.input_yhteensa') ).find('input').each(function( index ) {
	todigit = $(this).val().replace(/\./g, ',');
	$(this).replaceWith(todigit);
    });
    $( $(this).closest('table').find('.rivintaulu td.input_freetext') ).find('input').each(function( index ) {
	$(this).replaceWith($(this).val());
    });
  });

  $(document).delegate("input[type=number]","keyup change paste",function(){
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

	$(this).closest('table').find('.input_yhteensa input').each(function( index ) {
		yhteensa_last += parseFloat($(this).val());
	});
	parseFloat($(this).closest('.paa_taulu').find('.yhteensa_last').text(yhteensa_last.toFixed(2).replace(/\./g, ',')));
	return false;
  });

  $(document).delegate(".fa-trash","click",function(){
	$(this).closest('tr').remove();
	$('.input_alv input').change();
	return false;
  });
  //     muokkaus -->

});
</script>
