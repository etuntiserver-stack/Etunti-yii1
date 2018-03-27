<?php
/* @var $this TietosuojaController */
/* @var $data Tietosuoja */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('col_1')); ?>:</b>
	<?php echo CHtml::encode($data->col_1); ?>
	<br />


</div>