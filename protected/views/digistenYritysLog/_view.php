<?php
/* @var $this DigistenYritysLogController */
/* @var $data DigistenYritysLog */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yritys_id')); ?>:</b>
	<?php echo CHtml::encode($data->yritys_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tapahtuma')); ?>:</b>
	<?php echo CHtml::encode($data->tapahtuma); ?>
	<br />


</div>