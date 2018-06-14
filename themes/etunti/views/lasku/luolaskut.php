<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKU AUTOMAATTIO ESIKATSELU'); ?></h2>

	</div>

	<center><button class="btn btn-lg btn-success"><?=Yii::t('main', 'LÄHETÄ KAIKKI LASKUT')?></button></center>
	<br>
<p>
<div class="container-fluid">
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
	$yhteensa_total	= 0;
	$is_ok_lasku 	= true;
	$l_class	= 'class="bg-default"';
	if( 
		empty($item->laskutus_kanava)
		or empty($item->maksuehto)
		or empty($item->viivastyskorko)
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
	</div>
	</td>
	<td>
		<?php $hyv_lista = $this->hyvaksyttyListaByAsiakas($item->id, $from, $to); ?>
		<table class="table table-striped">
		<tr>
		<th width="100">Tuote</th>
		<th width="10">Hinta</th>
		<th width="10">Yksikkö</th>
		<th width="10">Määrä</th>
		<th width="10">Yhteensä</th>
		<th>Free text</th>
		</tr>
		<?php foreach($hyv_lista as $mob) : ?>
		<?php
			$t 		= $this->num(strtotime($mob->loppui)-strtotime($mob->aloitan));
			$rivi_kpl 	= 0;
			$r		= [];
			$r 		= $this->hinnastoHintaat($mob->tyovuoroot->tuoteID, $item->asiakasnumero, $mob->kohteet, $t, $rivi_kpl);
			$nimike		= $r['tp_nimike'];
			$kpl 		= $r['kpl'];
			$hinta 		= $r['hinta'];
			$alv 		= $r['alv'];
			$yksikko	= $r['yksikko'];
			$yht		= ($r['kpl']*$r['hinta']);
			$yhteensa_total += $yht;
	        ?>
		<tr>
		<td><?=$nimike?></td>
		<td><?=number_format($hinta, 2, ',', ' ')?></td>
		<td><?=$yksikko?></td>
		<td><?=$kpl?></td>
		<td><?=number_format($yht, 2, ',', ' ')?></td>
		<td><?=$mob->kohde_kannasta?> - <?=date("d.m.Y", strtotime($mob->aloitan))?>, <?=date("H:i", strtotime($mob->aloitan))?> - <?=date("H:i", strtotime($mob->loppui))?></td>
		</tr>
		<!-- Lisatuote -->
		<?php if( is_array(json_decode($mob->tyovuoroot->lisa_tuotteet, true)['tuote']) ) : ?>
		<?php foreach(json_decode($mob->tyovuoroot->lisa_tuotteet, true)['tuote'] as $k => $v) : ?>
		<?php
			$t 		= 0;
			$rivi_kpl 	= json_decode($mob->tyovuoroot->lisa_tuotteet, true)['maara'][$k];
			$r		= [];
			$r 		= $this->hinnastoHintaat($v, $item->asiakasnumero, $mob->kohteet, $t, $rivi_kpl);
			$nimike		= $r['tp_nimike'];
			$kpl 		= $r['kpl'];
			$hinta 		= $r['hinta'];
			$alv 		= $r['alv'];
			$yksikko	= $r['yksikko'];
			$yht		= ($r['kpl']*$r['hinta']);
			$yhteensa_total += $yht;
	        ?>
		<tr>
		<td><?=$nimike?></td>
		<td><?=number_format($hinta, 2, ',', ' ')?></td>
		<td><?=$yksikko?></td>
		<td><?=$kpl?></td>
		<td><?=number_format($yht, 2, ',', ' ')?></td>
		<td><?=$mob->kohde_kannasta?> - <?=date("d.m.Y", strtotime($mob->aloitan))?>, <?=date("H:i", strtotime($mob->aloitan))?> - <?=date("H:i", strtotime($mob->loppui))?></td>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
		<!-- / Lisatuote -->
		<?php endforeach; ?>
		</table>
	</td>
	</tr>

	<!-- Lahetys -->
	<?php if($is_ok_lasku and $laheta and $asiakas_id == $item->id){
		$lasku = new Lasku;
		$lasku->yid = 1;
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
		if(!$lasku->save()){
			print_r($lasku->getErrors());
		}
	} ?>
	<!-- / Lahetys -->

	<!-- Painikkeet -->
	<?php if($is_ok_lasku): ?>
		<tr><th>
		<?php
			echo CHtml::link(Yii::t('main', 'Lähetä lasku'), 
				array('lasku/luolaskut', 'from' => $from, 'to' => $to, 'asiakas_id' => $item->id, 'laheta' => true), 
				array(
					'class' => 'btn btn-block btn-success',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä')
				)
			); 
		?>
		</th></tr>
	<?php else: ?>
		<tr><th><button class="btn btn-block btn-danger">Lasku ei mennyt läpi</button></th></tr>
	<?php endif; ?>
	<!-- / Painikkeet -->
	<?php endforeach; ?>
	</table>
</div>
</p>
