<?php
/* @var $this LogController */
/* @var $data Log */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CHtml::encode($data->text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kuka')); ?>:</b>
	<?php echo CHtml::encode($data->kuka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('log_category')); ?>:</b>
	<?php echo CHtml::encode($data->log_category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_to')); ?>:</b>
	<?php echo CHtml::encode($data->email_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_subject')); ?>:</b>
	<?php echo CHtml::encode($data->email_subject); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('email_message')); ?>:</b>
	<?php echo CHtml::encode($data->email_message); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email_attachment')); ?>:</b>
	<?php echo CHtml::encode($data->email_attachment); ?>
	<br />

	*/ ?>

</div>