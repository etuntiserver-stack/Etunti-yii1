<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */

?>

<tr>

	<td>
	<?php echo CHtml::encode($data->laskunumero); ?>
	</td>

	<td>
	<?php echo date("d.m.Y",strtotime($data->paivays)); ?>
	</td>

	<td>
	<?php echo $data->id; ?>
	</td>

	<td>
	<?php echo number_format($data->yhteensa_total, 2, ',', ' '); ?>
	</td>

	<td>
	00,00
	</td>

</tr>
