<?php
/* @var $this OhjevideotController */
/* @var $data Ohjevideot */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('otsiko')); ?>:</b>
	<?php echo CHtml::encode($data->otsiko); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kuvaus')); ?>:</b>
	<?php echo CHtml::encode($data->kuvaus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tiedoston_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->tiedoston_nimi); ?>
	<br />


</div>