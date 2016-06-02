<?php
/* @var $this TyotodistusController */
/* @var $data Tyotodistus */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('key')); ?>:</b>
	<?php echo CHtml::encode($data->key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyonantaja')); ?>:</b>
	<?php echo CHtml::encode($data->tyonantaja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postinumero')); ?>:</b>
	<?php echo CHtml::encode($data->postinumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postitoimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->postitoimipaikka); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('puhelin')); ?>:</b>
	<?php echo CHtml::encode($data->puhelin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('y_tunnus')); ?>:</b>
	<?php echo CHtml::encode($data->y_tunnus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_email')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</b>
	<?php echo CHtml::encode($data->tid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_katuosoite')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_katuosoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_pnumero')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_pnumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_ptoimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_ptoimipaikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_puh')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_puh); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_henkilotunnus')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_henkilotunnus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Alku')); ?>:</b>
	<?php echo CHtml::encode($data->Alku); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Loppu')); ?>:</b>
	<?php echo CHtml::encode($data->Loppu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Tyokohde')); ?>:</b>
	<?php echo CHtml::encode($data->Tyokohde); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Tyotehtavat')); ?>:</b>
	<?php echo CHtml::encode($data->Tyotehtavat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TyosuhteenPaattamisenSyy')); ?>:</b>
	<?php echo CHtml::encode($data->TyosuhteenPaattamisenSyy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Tyotaito')); ?>:</b>
	<?php echo CHtml::encode($data->Tyotaito); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Kaytos')); ?>:</b>
	<?php echo CHtml::encode($data->Kaytos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Arvio')); ?>:</b>
	<?php echo CHtml::encode($data->Arvio); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Paivays')); ?>:</b>
	<?php echo CHtml::encode($data->Paivays); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Paikka')); ?>:</b>
	<?php echo CHtml::encode($data->Paikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TyonantajanEdustaja')); ?>:</b>
	<?php echo CHtml::encode($data->TyonantajanEdustaja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NimikeTehtava')); ?>:</b>
	<?php echo CHtml::encode($data->NimikeTehtava); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tiedosto')); ?>:</b>
	<?php echo CHtml::encode($data->tiedosto); ?>
	<br />

	*/ ?>

</div>