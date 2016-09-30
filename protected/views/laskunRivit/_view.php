<?php
/* @var $this LaskunRivitController */
/* @var $data LaskunRivit */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lid')); ?>:</b>
	<?php echo CHtml::encode($data->lid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rivi')); ?>:</b>
	<?php echo CHtml::encode($data->rivi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tkoodi')); ?>:</b>
	<?php echo CHtml::encode($data->tkoodi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nimike')); ?>:</b>
	<?php echo CHtml::encode($data->nimike); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kpl')); ?>:</b>
	<?php echo CHtml::encode($data->kpl); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('yksikko')); ?>:</b>
	<?php echo CHtml::encode($data->yksikko); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hinta')); ?>:</b>
	<?php echo CHtml::encode($data->hinta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alv')); ?>:</b>
	<?php echo CHtml::encode($data->alv); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hinta_alv')); ?>:</b>
	<?php echo CHtml::encode($data->hinta_alv); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ale')); ?>:</b>
	<?php echo CHtml::encode($data->ale); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('veroton')); ?>:</b>
	<?php echo CHtml::encode($data->veroton); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteensa_alv')); ?>:</b>
	<?php echo CHtml::encode($data->yhteensa_alv); ?>
	<br />

	*/ ?>

</div>