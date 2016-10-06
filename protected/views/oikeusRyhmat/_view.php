<?php
/* @var $this OikeusRyhmatController */
/* @var $data OikeusRyhmat */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nimike')); ?>:</b>
	<?php echo CHtml::encode($data->nimike); ?>
	<br />


</div>