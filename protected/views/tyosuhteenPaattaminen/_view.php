<?php
/* @var $this TyosuhteenPaattaminenController */
/* @var $data TyosuhteenPaattaminen */
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('titteli')); ?>:</b>
	<?php echo CHtml::encode($data->titteli); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kuuleminen')); ?>:</b>
	<?php echo CHtml::encode($data->kuuleminen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyosuhteen_paattaminen')); ?>:</b>
	<?php echo CHtml::encode($data->tyosuhteen_paattaminen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyonantaja')); ?>:</b>
	<?php echo CHtml::encode($data->tyonantaja); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postinumero')); ?>:</b>
	<?php echo CHtml::encode($data->postinumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postitoimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->postitoimipaikka); ?>
	<br />

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

	<b><?php echo CHtml::encode($data->getAttributeLabel('teksti')); ?>:</b>
	<?php echo CHtml::encode($data->teksti); ?>
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('alku_pvm')); ?>:</b>
	<?php echo CHtml::encode($data->alku_pvm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('loppu_pvm')); ?>:</b>
	<?php echo CHtml::encode($data->loppu_pvm); ?>
	<br />

	*/ ?>

</div>