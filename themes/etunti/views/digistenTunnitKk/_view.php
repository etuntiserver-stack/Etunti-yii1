<?php
/* @var $this DigistenTunnitKkController */
/* @var $data DigistenTunnitKk */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('domain')); ?>:</b>
	<?php echo CHtml::encode($data->domain); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year')); ?>:</b>
	<?php echo CHtml::encode($data->year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('month')); ?>:</b>
	<?php echo CHtml::encode($data->month); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tunnit')); ?>:</b>
	<?php echo CHtml::encode($data->tunnit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tasot')); ?>:</b>
	<?php echo CHtml::encode($data->tasot); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('maksettu')); ?>:</b>
	<?php echo CHtml::encode($data->maksettu); ?>
	<br />

	*/ ?>

</div>