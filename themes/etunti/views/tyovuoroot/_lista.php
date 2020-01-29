<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */

	$asiakas = '';
	$call = '';
	$get_kohde = Kohteet::model()->findByPk($data->kohde);
	if(isset($get_kohde->asiakkaat))
	{
		if( $get_kohde->asiakkaat->tyyppi == 'yritys' ){
			$asiakas = $get_kohde->asiakkaat->yrityksen_nimi;
		} elseif( $get_kohde->asiakkaat->tyyppi == 'henkilo' ){
			$asiakas = $get_kohde->asiakkaat->yhteyshenkilo;
		}
		if( $get_kohde->asiakkaat->puhelin != '' ){
			$call = '&nbsp;&nbsp;<a href="tel:'.$get_kohde->asiakkaat->puhelin.'"><i class="fa fa-2x fa-phone"></i></a>';
		}
	}
?>

<tr>
	<td>
		<?php if($data->tid == 0): ?>
		<?=Yii::t('main', 'VARAUS')?>
		<?php else: ?>
		<?=$this->etuSukunimi($data->tid)?>
		<?php endif; ?>
	</td>
	<td><?=$data->pvm?></td>
	<td><?=$data->alku?>-<?=$data->loppu?></td>
	<td><?=$asiakas?> <?=$call?></td>
	<td><?=$data->osoite?></td>
	<td><?=($data->status != 0)?$this->tilanteet()[$data->status]:''?></td>
</tr>


