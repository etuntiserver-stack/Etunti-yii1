<?php
/* @var $this VinkkiExtranetController */
/* @var $data VinkkiExtranet */
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('nimi')); ?>:</b>
	<?php echo CHtml::encode($data->nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('puhelin')); ?>:</b>
	<?php echo CHtml::encode($data->puhelin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('teksti')); ?>:</b>
	<?php echo CHtml::encode($data->teksti); ?>
	<br />


</div>