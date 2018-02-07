<?php
/* @var $this AvaimetController */
/* @var $data Avaimet */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('avainnumero')); ?>:</b>
	<?php echo CHtml::encode($data->avainnumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kohde_id')); ?>:</b>
	<?php echo CHtml::encode($data->kohde_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</b>
	<?php echo CHtml::encode($data->tid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sijainti')); ?>:</b>
	<?php echo CHtml::encode($data->sijainti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lisatiedot')); ?>:</b>
	<?php echo CHtml::encode($data->lisatiedot); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>