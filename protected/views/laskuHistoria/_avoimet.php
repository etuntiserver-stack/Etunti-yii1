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
	<?php echo CHtml::encode($l->laskunumero).', '.$l->id; ?>
	</td>

	<td>
	<?php echo $data->trust_statuscode; ?>
	</td>

	<td>
	<?php echo date("d.m.Y H:i:s",strtotime($data->time)); ?>
	</td>

	<td>
	<?php echo number_format($l->yhteensa_total, 2, ',', ' '); ?>
	</td>

	<td>
	<?php echo CHtml::encode($l->as_nro.' '.$nimi); ?>
	</td>

</tr>
