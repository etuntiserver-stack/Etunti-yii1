<?php
/* @var $this AsiakkaatSivuController */
/* @var $data AsiakkaatSivu */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakkaan_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->asiakkaan_nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('html_content')); ?>:</b>
	<?php echo CHtml::encode($data->html_content); ?>
	<br />


</div>