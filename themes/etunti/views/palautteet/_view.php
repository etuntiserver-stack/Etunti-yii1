<?php
/* @var $this PalautteetController */
/* @var $data Palautteet */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('keskustelu_id')); ?>:</b>
	<?php echo CHtml::encode($data->keskustelu_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakas_id')); ?>:</b>
	<?php echo CHtml::encode($data->asiakas_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('teksti')); ?>:</b>
	<?php echo CHtml::encode($data->teksti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('otsikko')); ?>:</b>
	<?php echo CHtml::encode($data->otsikko); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>