<?php
/* @var $this IlmoitusKaikkilleController */
/* @var $data IlmoitusKaikkille */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('viesti')); ?>:</b>
	<?php echo CHtml::encode($data->viesti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aloitus')); ?>:</b>
	<?php echo CHtml::encode($data->aloitus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lopetus')); ?>:</b>
	<?php echo CHtml::encode($data->lopetus); ?>
	<br />

</div>
