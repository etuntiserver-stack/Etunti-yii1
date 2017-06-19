<?php
/* @var $this KupongitController */
/* @var $data Kupongit */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kupongin_id')); ?>:</b>
	<?php echo CHtml::encode($data->kupongin_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('voimassa')); ?>:</b>
	<?php echo CHtml::encode($data->voimassa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('euro_maara')); ?>:</b>
	<?php echo CHtml::encode($data->euro_maara); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('prosentti_maara')); ?>:</b>
	<?php echo CHtml::encode($data->prosentti_maara); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maara_tyyppi')); ?>:</b>
	<?php echo CHtml::encode($data->maara_tyyppi); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('jatkuva')); ?>:</b>
	<?php echo CHtml::encode($data->jatkuva); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>