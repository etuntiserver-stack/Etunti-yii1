<?php
/* @var $this SivexkuittiController */
/* @var $data Sivexkuitti */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakas_num')); ?>:</b>
	<?php echo CHtml::encode($data->asiakas_num); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('requests')); ?>:</b>
	<?php echo CHtml::encode($data->requests); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('puh_numero')); ?>:</b>
	<?php echo CHtml::encode($data->puh_numero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imei')); ?>:</b>
	<?php echo CHtml::encode($data->imei); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bluetooth_name')); ?>:</b>
	<?php echo CHtml::encode($data->bluetooth_name); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sim_serial_number')); ?>:</b>
	<?php echo CHtml::encode($data->sim_serial_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('subscriber_id')); ?>:</b>
	<?php echo CHtml::encode($data->subscriber_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('my_location')); ?>:</b>
	<?php echo CHtml::encode($data->my_location); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kohde_kannasta')); ?>:</b>
	<?php echo CHtml::encode($data->kohde_kannasta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kohdenID')); ?>:</b>
	<?php echo CHtml::encode($data->kohdenID); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aloitan')); ?>:</b>
	<?php echo CHtml::encode($data->aloitan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('loppui')); ?>:</b>
	<?php echo CHtml::encode($data->loppui); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('viesti')); ?>:</b>
	<?php echo CHtml::encode($data->viesti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</b>
	<?php echo CHtml::encode($data->tid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('etaisyys')); ?>:</b>
	<?php echo CHtml::encode($data->etaisyys); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tietoja')); ?>:</b>
	<?php echo CHtml::encode($data->tietoja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('admin')); ?>:</b>
	<?php echo CHtml::encode($data->admin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hyvaksytty')); ?>:</b>
	<?php echo CHtml::encode($data->hyvaksytty); ?>
	<br />

	*/ ?>

</div>