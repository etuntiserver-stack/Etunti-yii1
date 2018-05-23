<?php
/* @var $this EdicoViestintaController */
/* @var $data EdicoViestinta */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakas_id')); ?>:</b>
	<?php echo CHtml::encode($data->asiakas_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('otsikko')); ?>:</b>
	<?php echo CHtml::encode($data->otsikko); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>