<?php
/* @var $this TyontekijatController */
/* @var $data Tyontekijat */
?>

<div class="well col-sm-3">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id); ?>
	<br />


	<?php echo CHtml::link(CHtml::encode($data->tekijan_nimi), array('update', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_puh')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_puh); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('laiten_puh')); ?>:</b>
	<?php echo CHtml::encode($data->laiten_puh); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_email')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyoryhma')); ?>:</b>
	<?php echo CHtml::encode($data->tyoryhma); ?>
	<br />

	<?php /*

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_henkilotunnus')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_henkilotunnus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('imei')); ?>:</b>
	<?php echo CHtml::encode($data->imei); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('laiten_puh')); ?>:</b>
	<?php echo CHtml::encode($data->laiten_puh); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_lanka_puh')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_lanka_puh); ?>
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



	<b><?php echo CHtml::encode($data->getAttributeLabel('tyoehtosopimus')); ?>:</b>
	<?php echo CHtml::encode($data->tyoehtosopimus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_kulunvalvonta')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_kulunvalvonta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_pankkitili')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_pankkitili); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_konttori')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_konttori); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aktiivinen')); ?>:</b>
	<?php echo CHtml::encode($data->aktiivinen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_tietoja')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_tietoja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_muisti')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_muisti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('salasana')); ?>:</b>
	<?php echo CHtml::encode($data->salasana); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('online_varauksen_valmina')); ?>:</b>
	<?php echo CHtml::encode($data->online_varauksen_valmina); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kortit')); ?>:</b>
	<?php echo CHtml::encode($data->kortit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ayjasenyys')); ?>:</b>
	<?php echo CHtml::encode($data->ayjasenyys); ?>
	<br />

	*/ ?>

</div>
