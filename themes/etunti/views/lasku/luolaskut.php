<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKU AUTOMAATTIO ESIKATSELU'); ?></h2>

	</div>

	<center>
		<?php
		if( count($lista) > 0 ){
			echo CHtml::link(Yii::t('main', 'LÄHETÄ KAIKKI LASKUT'), 
				array('lasku/luolaskut', 'from' => $from, 'to' => $to, 'alvsis' => $alvsis, 'laheta' => true), 
				array(
					'class' => 'btn btn-block btn-success',
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
	<table class="table table-bordered">
	<tr>
	<th>Asiakas</th>
	<th>Laskutuksen tiedot</th>
	<th>Laskutuksen rivit</th>
	</tr>
	<?php foreach($lista as $item) : ?>
	<tr>
	<td><h3><?=$item->Fullname?></h3></td>
	<td>
	<?php
	$yhteensa_total_veroton	= 0;
	$yhteensa_total	= 0;
	$is_ok_lasku 	= true;
	$l_class	= 'class="bg-default"';
	if( 
		empty( $item->laskutus_kanava )
		or empty( $item->maksuehto )
		or empty( $item->viivastyskorko )
		or ( $item->laskutus_kanava == 'sahkoposti' and empty($item->sahkoposti) )
		or ( $asetukset->palvelu_tyyppi == 4 and $asetukset->netvisor_kaytto == 1 and $item->netvisorkey == 0 )
	){ 
		$is_ok_lasku 	= false; 
		$l_class	= 'class="alert bg-danger"';
	}
	?>
	<div <?=$l_class?>>
	<p><b><?=Yii::t('main', 'Laskutus kanava')?></b>-<?=(!empty($item->laskutus_kanava))? Yii::t('main', $item->laskutus_kanava):''?></p>
	<p><b><?=Yii::t('main', 'Eräpäivä')?></b>-<?=(!empty($item->maksuehto))? date("d.m.Y",strtotime("+$item->maksuehto day")):''?></p>
	<p><b><?=Yii::t('main', 'Maksuehto')?></b>-<?=(!empty($item->maksuehto))? $item->maksuehto:''?></p>
	<p><b><?=Yii::t('main', 'Viivästyskorko')?></b>-<?=(!empty($item->viivastyskorko))? $item->viivastyskorko:''?></p>
	<?=($item->laskutus_kanava == 'sahkoposti' and empty($item->sahkoposti))? '<p>'.Yii::t('main', 'Sähköpostiosoite ei saisi olla tyhjä tässä laskutus kanavassa.').'</p>':''?>
	<?=( $asetukset->palvelu_tyyppi == 4 and $asetukset->netvisor_kaytto == 1 and $item->netvisorkey == 0 )? '<p>'.Yii::t('main', 'Asiakas ei vielä saanut netvisorkey. Päivittä tämän asiakkaan tiedot.').'</p>':''?>
	</div>
	</td>
	<td>


	<!-- Lahetys Pää lasku -->
	<?php if( $is_ok_lasku and $laheta !== null ){

		$laskunumero = 1;
		$criteria = new CDbCriteria();
       		$criteria->order = " laskunumero!='' DESC,id DESC ";
		$vm = Lasku::model()->find($criteria);
		if( isset($vm->id) ){ $laskunumero = $vm->laskunumero+1; }

		$lasku = new Lasku;
		$lasku->yid = 1;
		$lasku->tilanne = 1;
		$lasku->laskunumero = $laskunumero;
		$lasku->tapahtumapvm = date("Y-m-d H:i:s");
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
		$lasku->paivays = date("Y-m-d");
		$lasku->erapaiva = date("Y-m-d",strtotime("+$item->maksuehto day"));
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

		<?php $hyv_lista = $this->hyvaksyttyListaByAsiakas($item->id, $from, $to); ?>
		<table class="table table-striped">
		<tr>
		<th width="100">Tuote</th>
		<th width="10">Hinta</th>
		<th width="10">Yksikkö</th>
		<th width="10">Määrä</th>
		<th width="10">ALV</th>
		<th width="10">Veroton</th>
		<th width="10">Yhteensä</th>
		<th>Free text</th>
		</tr>
		<?php $key = 0; ?>
		<?php foreach($hyv_lista as $mob) : ?>
		<?php $key++; ?>
		<?php
			$t 		= $this->num(strtotime($mob->loppui)-strtotime($mob->aloitan));
			$rivi_kpl 	= 0;
			$r		= [];
			$r 		= $this->hinnastoHintaat($mob->tyovuoroot->tuoteID, $item->asiakasnumero, $mob->kohteet, $t, $rivi_kpl);
			$tp_id		= (( isset($r['tp_id']) )? $r['tp_id']:0);
			$nimike		= (( isset($r['tp_nimike']) )? $r['tp_nimike']:'');
			$kpl 		= (( isset($r['kpl']) )? $r['kpl']:0);
			$hinta 		= (( isset($r['hinta']) )? $r['hinta']:0);
			$alv 		= (( isset($r['alv']) )? $r['alv']:0);
			$yksikko	= (( isset($r['yksikko']) )? $r['yksikko']:'');
			$freetext	= $mob->kohde_kannasta.' - '.date("d.m.Y", strtotime($mob->aloitan)).', '.date("H:i", strtotime($mob->aloitan)).'-'.date("H:i", strtotime($mob->loppui));

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
		<td><?=$nimike?></td>
		<td><?=number_format($hinta, 2, ',', ' ')?></td>
		<td><?=$yksikko?></td>
		<td><?=$kpl?></td>
		<td><?=$alv?></td>
		<td><?=number_format($veroton, 2, ',', ' ')?></td>
		<td><?=number_format($yht, 2, ',', ' ')?></td>
		<td><?=$freetext?></td>
		</tr>
		<?php endif; ?>

		<!-- Lisatuote -->
		<?php $lisa_tuotteet = json_decode($mob->tyovuoroot->lisa_tuotteet, true); ?>
		<?php if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote']) ) : ?>
		<?php foreach($lisa_tuotteet['tuote'] as $k => $v) : ?>
		<?php $key++; ?>
		<?php
			$t 		= json_decode($mob->tyovuoroot->lisa_tuotteet, true)['maara'][$k];
			$rivi_kpl 	= json_decode($mob->tyovuoroot->lisa_tuotteet, true)['maara'][$k];
			$r		= [];
			$r 		= $this->hinnastoHintaat($v, $item->asiakasnumero, $mob->kohteet, $t, $rivi_kpl);
			$tp_id		= (( isset($r['tp_id']) )? $r['tp_id']:0);
			$nimike		= (( isset($r['tp_nimike']) )? $r['tp_nimike']:'');
			$kpl 		= (( isset($r['kpl']) )? $r['kpl']:0);
			$hinta 		= (( isset($r['hinta']) )? $r['hinta']:0);
			$alv 		= (( isset($r['alv']) )? $r['alv']:0);
			$yksikko	= (( isset($r['yksikko']) )? $r['yksikko']:'');
			$freetext	= $mob->kohde_kannasta.' - '.date("d.m.Y", strtotime($mob->aloitan)).', '.date("H:i", strtotime($mob->aloitan)).'-'.date("H:i", strtotime($mob->loppui));

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
		<td><?=$nimike?></td>
		<td><?=number_format($hinta, 2, ',', ' ')?></td>
		<td><?=$yksikko?></td>
		<td><?=$kpl?></td>
		<td><?=$alv?></td>
		<td><?=number_format($veroton, 2, ',', ' ')?></td>
		<td><?=number_format($yht, 2, ',', ' ')?></td>
		<td><?=$freetext?></td>
		</tr>
		<?php endif; ?>

		<?php endforeach; ?>
		<?php endif; ?>
		<!-- / Lisatuote -->

		<!-- Update tyovuoro -->
		<?php if( $is_ok_lasku and $laheta !== null and isset($lasku->id) and $mob->tv_id > 0 ){
			$tl = Tyovuoroot::model()->findByPk($mob->tv_id);
			if( isset($tl->id) ){
			   Tyovuoroot::model()->updateByPk($tl->id, array('lasku_id' => $lasku->id));
			}
		} ?>
		<!-- / Update tyovuoro -->
		<?php endforeach; ?>
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
				$criteria->condition = " lasku_id='".$lasku->id."' ";
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
				array('lasku/luolaskut', 'from' => $from, 'to' => $to, 'alvsis' => $alvsis, 'asiakas_id' => $item->id, 'laheta' => true), 
				array(
					'class' => 'btn btn-block btn-success',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä')
				)
			); 
		?>
		</th>
		<th><div class="text-right"><h3><?=Yii::t('main', 'Yhteensä')?></h3></div></th>
		<th><div class="text-left"><h3><?=number_format($yhteensa_total, 2, ',', ' ')?>&euro;</h3></div></th>
	<?php else: ?>
		<tr><th><button class="btn btn-block btn-danger">Lasku ei mennyt läpi</button></th></tr>
	<?php endif; ?>
	<!-- / Painikkeet -->
	</tr>
	<?php endforeach; ?>
	</table>
	<?php endif; // count($lista) > 0 ?>

	<?php if( $laheta ){
		$this->redirect(array('luolaskut', 'from' => $from, 'to' => $to));
	} ?>


</div>
</p>
