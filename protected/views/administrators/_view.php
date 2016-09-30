<?php
/* @var $this AdministratorsController */
/* @var $data Administrators */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('adm_login')); ?>:</b>
	<?php echo CHtml::encode($data->adm_login); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('adm_salasana')); ?>:</b>
	<?php echo CHtml::encode($data->adm_salasana); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('adm_email')); ?>:</b>
	<?php echo CHtml::encode($data->adm_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('adm_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->adm_nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>