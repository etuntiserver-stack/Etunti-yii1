<?php
/* @var $this KorvauksetController */
/* @var $data Korvaukset */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</b>
	<?php echo CHtml::encode($data->tid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pvm')); ?>:</b>
	<?php echo CHtml::encode($data->pvm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('syy')); ?>:</b>
	<?php echo CHtml::encode($data->syy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('korvaus')); ?>:</b>
	<?php echo CHtml::encode($data->korvaus); ?>
	<br />


</div>