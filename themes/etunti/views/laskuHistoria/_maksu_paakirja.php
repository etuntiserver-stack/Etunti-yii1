<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */

$nimi = '';

   if($data->tyyppi == 'henkilo')
	$nimi = $data->nimi;
   if($data->tyyppi == 'yritys')
	$nimi = $data->yritys;

?>

<tr>

	<td>
	<?php echo CHtml::encode($data->as_nro); ?>
	</td>

	<td>
	<?php echo CHtml::encode($nimi); ?>
	</td>

	<td>
	<?php echo CHtml::encode($data->laskunumero); ?>
	</td>

	<td>
	<?php echo date("d.m.Y",strtotime($data->paivays)); ?>
	</td>

	<td>
	<?php echo number_format($data->yhteensa_total, 2, ',', ' '); ?>
	</td>

	<td>
	<?php echo number_format($saldo, 2, ',', ' '); ?>
	</td>


</tr>
