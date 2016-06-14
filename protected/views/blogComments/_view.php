<?php
/* @var $this BlogCommentsController */
/* @var $data BlogComments */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('blog_id')); ?>:</b>
	<?php echo CHtml::encode($data->blog_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nimimerkki')); ?>:</b>
	<?php echo CHtml::encode($data->nimimerkki); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('teksti')); ?>:</b>
	<?php echo CHtml::encode($data->teksti); ?>
	<br />


</div>