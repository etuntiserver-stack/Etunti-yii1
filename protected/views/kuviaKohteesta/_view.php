<?php
/* @var $this KuviaKohteestaController */
/* @var $data KuviaKohteesta */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kohde_id')); ?>:</b>
	<?php echo CHtml::encode($data->kohde_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</b>
	<?php echo CHtml::encode($data->tid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tiedosto')); ?>:</b>
	<?php echo CHtml::encode($data->tiedosto); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('kuvaus')); ?>:</b>
	<?php echo CHtml::encode($data->kuvaus); ?>
	<br />

	*/ ?>

</div>