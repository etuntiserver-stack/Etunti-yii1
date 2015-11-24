<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */

$l = Lasku::model()->findbypk($data->lid);

	$nimi = '';
   if($l->tyyppi == 'henkilo')
	$nimi = $l->nimi;
   if($l->tyyppi == 'yritys')
	$nimi = $l->yritys;
?>

<tr>

	<td>
	<?php echo CHtml::encode($data->id); ?>
	</td>

	<td>
	<?php echo date("d.m.Y H:i",strtotime($data->time)); ?>
	</td>

	<td>
	<?php echo CHtml::encode($data->yht_euro); ?>
	</td>

	<td>
	<?php echo CHtml::encode($nimi); ?>
	</td>


</tr>
