<?php
/* @var $this AsetuksetController */
/* @var $data Asetukset */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('syntyrin_emails')); ?>:</b>
	<?php echo CHtml::encode($data->syntyrin_emails); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('paivan_uutinen')); ?>:</b>
	<?php echo CHtml::encode($data->paivan_uutinen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('logon_polkku')); ?>:</b>
	<?php echo CHtml::encode($data->logon_polkku); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('logon_korkeus')); ?>:</b>
	<?php echo CHtml::encode($data->logon_korkeus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('johtaja')); ?>:</b>
	<?php echo CHtml::encode($data->johtaja); ?>
	<br />


</div>