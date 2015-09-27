<?php
/* @var $this LaskutusTuotteetController */
/* @var $data LaskutusTuotteet */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tuotenimi')); ?>:</b>
	<?php echo CHtml::encode($data->tuotenimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hinta_alv_0')); ?>:</b>
	<?php echo CHtml::encode($data->hinta_alv_0); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hinta_alv_sis')); ?>:</b>
	<?php echo CHtml::encode($data->hinta_alv_sis); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alv')); ?>:</b>
	<?php echo CHtml::encode($data->alv); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yksikko')); ?>:</b>
	<?php echo CHtml::encode($data->yksikko); ?>
	<br />


</div>