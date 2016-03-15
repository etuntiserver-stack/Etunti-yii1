<?php
/* @var $this OnlinevarausTuotteetController */
/* @var $data OnlinevarausTuotteet */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nimike')); ?>:</b>
	<?php echo CHtml::encode($data->nimike); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hinta')); ?>:</b>
	<?php echo CHtml::encode($data->hinta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('selitysteksti')); ?>:</b>
	<?php echo CHtml::encode($data->selitysteksti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('palvelu')); ?>:</b>
	<?php echo CHtml::encode($data->palvelu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kesto')); ?>:</b>
	<?php echo CHtml::encode($data->kesto); ?>
	<br />


</div>