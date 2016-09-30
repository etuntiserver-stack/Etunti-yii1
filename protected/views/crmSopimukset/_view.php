<?php
/* @var $this CrmSopimuksetController */
/* @var $data CrmSopimukset */
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('teksti')); ?>:</b>
	<?php echo CHtml::encode($data->teksti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hyvaksyn_koodi')); ?>:</b>
	<?php echo CHtml::encode($data->hyvaksyn_koodi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakkaan_sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->asiakkaan_sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('liite')); ?>:</b>
	<?php echo CHtml::encode($data->liite); ?>
	<br />

	*/ ?>

</div>