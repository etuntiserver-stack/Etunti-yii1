<?php
/* @var $this YhteystiedotController */
/* @var $data Yhteystiedot */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteystieto_tyyppi')); ?>:</b>
	<?php echo CHtml::encode($data->yhteystieto_tyyppi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yrityksen_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->yrityksen_nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('y_tunnus')); ?>:</b>
	<?php echo CHtml::encode($data->y_tunnus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteyshenkilo')); ?>:</b>
	<?php echo CHtml::encode($data->yhteyshenkilo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('postitoimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->postitoimipaikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postinumero')); ?>:</b>
	<?php echo CHtml::encode($data->postinumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('puhelin')); ?>:</b>
	<?php echo CHtml::encode($data->puhelin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ryhma')); ?>:</b>
	<?php echo CHtml::encode($data->ryhma); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('myyja')); ?>:</b>
	<?php echo CHtml::encode($data->myyja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	*/ ?>

</div>