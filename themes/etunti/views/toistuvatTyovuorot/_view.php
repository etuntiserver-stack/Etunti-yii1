<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $data ToistuvatTyovuorot */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pfrom')); ?>:</b>
	<?php echo CHtml::encode($data->pfrom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pto')); ?>:</b>
	<?php echo CHtml::encode($data->pto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('viikkoja')); ?>:</b>
	<?php echo CHtml::encode($data->viikkoja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('viikko_paivat')); ?>:</b>
	<?php echo CHtml::encode($data->viikko_paivat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</b>
	<?php echo CHtml::encode($data->tid); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('kohde')); ?>:</b>
	<?php echo CHtml::encode($data->kohde); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pvm')); ?>:</b>
	<?php echo CHtml::encode($data->pvm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alku')); ?>:</b>
	<?php echo CHtml::encode($data->alku); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('loppu')); ?>:</b>
	<?php echo CHtml::encode($data->loppu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kesto')); ?>:</b>
	<?php echo CHtml::encode($data->kesto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyoajanmerkinta')); ?>:</b>
	<?php echo CHtml::encode($data->tyoajanmerkinta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tietoja')); ?>:</b>
	<?php echo CHtml::encode($data->tietoja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyopaari')); ?>:</b>
	<?php echo CHtml::encode($data->tyopaari); ?>
	<br />

	*/ ?>

</div>