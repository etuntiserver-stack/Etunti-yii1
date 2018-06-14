<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKU AUTOMAATTIO ESIKATSELLU'); ?></h2>

	</div>


<p>
<div class="container-fluid">
	<table class="table">
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
	<?php if($is_ok_lasku): ?>
		<tr><th><button class="btn btn-block btn-success">Luo lasku</button></th></tr>
	<?php else: ?>
		<tr><th><button class="btn btn-block btn-danger">Lasku ei mennyt läpi</button></th></tr>
	<?php endif; ?>
	<?php endforeach; ?>
	</table>
	<center><button class="btn btn-lg btn-primary myBgColors"><?=Yii::t('main', 'LUO KAIKKI LASKUT')?></button></center>
</div>
</p>
