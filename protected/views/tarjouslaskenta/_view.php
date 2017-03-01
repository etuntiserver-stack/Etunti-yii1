<?php
/* @var $this TarjouslaskentaController */
/* @var $data Tarjouslaskenta */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteystiedot_id')); ?>:</b>
	<?php echo CHtml::encode($data->yhteystiedot_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asiakas_id')); ?>:</b>
	<?php echo CHtml::encode($data->asiakas_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tuote_palvelu_id')); ?>:</b>
	<?php echo CHtml::encode($data->tuote_palvelu_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hinta_tyyppi')); ?>:</b>
	<?php echo CHtml::encode($data->hinta_tyyppi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('neliot')); ?>:</b>
	<?php echo CHtml::encode($data->neliot); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('kayntikerrat')); ?>:</b>
	<?php echo CHtml::encode($data->kayntikerrat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tuntien_maara')); ?>:</b>
	<?php echo CHtml::encode($data->tuntien_maara); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteensa')); ?>:</b>
	<?php echo CHtml::encode($data->yhteensa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tavoite_myyntikate')); ?>:</b>
	<?php echo CHtml::encode($data->tavoite_myyntikate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('palkkakustannus')); ?>:</b>
	<?php echo CHtml::encode($data->palkkakustannus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('matkat')); ?>:</b>
	<?php echo CHtml::encode($data->matkat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iltalisa')); ?>:</b>
	<?php echo CHtml::encode($data->iltalisa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yolisa')); ?>:</b>
	<?php echo CHtml::encode($data->yolisa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('muut_kulut')); ?>:</b>
	<?php echo CHtml::encode($data->muut_kulut); ?>
	<br />

	*/ ?>

</div>