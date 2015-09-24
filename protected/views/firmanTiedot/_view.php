<?php
/* @var $this FirmanTiedotController */
/* @var $data FirmanTiedot */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyonantaja')); ?>:</b>
	<?php echo CHtml::encode($data->tyonantaja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postinumero')); ?>:</b>
	<?php echo CHtml::encode($data->postinumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postitoimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->postitoimipaikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('puhelin')); ?>:</b>
	<?php echo CHtml::encode($data->puhelin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('y_tunnus')); ?>:</b>
	<?php echo CHtml::encode($data->y_tunnus); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tilinumero')); ?>:</b>
	<?php echo CHtml::encode($data->tilinumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iban')); ?>:</b>
	<?php echo CHtml::encode($data->iban); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bic')); ?>:</b>
	<?php echo CHtml::encode($data->bic); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('johtaja')); ?>:</b>
	<?php echo CHtml::encode($data->johtaja); ?>
	<br />

	*/ ?>

</div>