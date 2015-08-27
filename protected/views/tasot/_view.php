<?php
/* @var $this TasotController */
/* @var $data Tasot */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('taso')); ?>:</b>
	<?php echo CHtml::encode($data->taso); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nimetys')); ?>:</b>
	<?php echo CHtml::encode($data->nimetys); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kuvaus')); ?>:</b>
	<?php echo CHtml::encode($data->kuvaus); ?>
	<br />


</div>