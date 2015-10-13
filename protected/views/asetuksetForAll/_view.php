<?php
/* @var $this AsetuksetForAllController */
/* @var $data AsetuksetForAll */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asetus')); ?>:</b>
	<?php echo CHtml::encode($data->asetus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('api_access_key')); ?>:</b>
	<?php echo CHtml::encode($data->api_access_key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('muut')); ?>:</b>
	<?php echo CHtml::encode($data->muut); ?>
	<br />


</div>