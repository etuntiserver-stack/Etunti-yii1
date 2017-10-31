<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */

	$asiakas = '';
	$get_kohde = Kohteet::model()->findByPk($data->kohde);
	if(isset($get_kohde->id))
	{
		$get_asiakas = Asiakkaat::model()->findByPk($get_kohde->asiakas_id);
		if(isset($get_asiakas->id) and $get_asiakas->tyyppi == 'yritys'){
			$asiakas = $get_asiakas->yrityksen_nimi;
		} elseif(isset($get_asiakas->id) and $get_asiakas->tyyppi == 'henkilo'){
			$asiakas = $get_asiakas->yhteyshenkilo;
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
	<td><?=$asiakas?></td>
	<td><?=(isset($this->getKohde($data->kohde)->osoite))?$this->getKohde($data->kohde)->osoite:''?></td>
	<td><?=($data->status != 0)?$this->tilanteet()[$data->status]:''?></td>
</tr>


