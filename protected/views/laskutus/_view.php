<?php
/* @var $this LaskutusController */
/* @var $data Laskutus */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kohdenID')); ?>:</b>
	<?php echo CHtml::encode($data->kohdenID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('trtd')); ?>:</b>
	<?php echo CHtml::encode($data->trtd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('trtd7')); ?>:</b>
	<?php echo CHtml::encode($data->trtd7); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mista')); ?>:</b>
	<?php echo CHtml::encode($data->mista); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mihin')); ?>:</b>
	<?php echo CHtml::encode($data->mihin); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteensa')); ?>:</b>
	<?php echo CHtml::encode($data->yhteensa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('muoto')); ?>:</b>
	<?php echo CHtml::encode($data->muoto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakkaan_koodi')); ?>:</b>
	<?php echo CHtml::encode($data->asiakkaan_koodi); ?>
	<br />

	*/ ?>

</div>