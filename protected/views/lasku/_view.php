<?php
/* @var $this LaskuController */
/* @var $data Lasku */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lid')); ?>:</b>
	<?php echo CHtml::encode($data->lid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yid')); ?>:</b>
	<?php echo CHtml::encode($data->yid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyyppi')); ?>:</b>
	<?php echo CHtml::encode($data->tyyppi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yritys')); ?>:</b>
	<?php echo CHtml::encode($data->yritys); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('y_tunnus')); ?>:</b>
	<?php echo CHtml::encode($data->y_tunnus); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('nimi')); ?>:</b>
	<?php echo CHtml::encode($data->nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('as_nro')); ?>:</b>
	<?php echo CHtml::encode($data->as_nro); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postinumero')); ?>:</b>
	<?php echo CHtml::encode($data->postinumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('toimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->toimipaikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('laskutus')); ?>:</b>
	<?php echo CHtml::encode($data->laskutus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('verkkolaskuosoite')); ?>:</b>
	<?php echo CHtml::encode($data->verkkolaskuosoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('v_tunnus')); ?>:</b>
	<?php echo CHtml::encode($data->v_tunnus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteyshenkilo')); ?>:</b>
	<?php echo CHtml::encode($data->yhteyshenkilo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nimitarkenne')); ?>:</b>
	<?php echo CHtml::encode($data->nimitarkenne); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('puhelin')); ?>:</b>
	<?php echo CHtml::encode($data->puhelin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('t_yritys')); ?>:</b>
	<?php echo CHtml::encode($data->t_yritys); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('t_y_tunnus')); ?>:</b>
	<?php echo CHtml::encode($data->t_y_tunnus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('t_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->t_nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('t_osoite')); ?>:</b>
	<?php echo CHtml::encode($data->t_osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('t_postinumero')); ?>:</b>
	<?php echo CHtml::encode($data->t_postinumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('t_toimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->t_toimipaikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('t_puhelin')); ?>:</b>
	<?php echo CHtml::encode($data->t_puhelin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('t_sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->t_sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('toimitusosoite')); ?>:</b>
	<?php echo CHtml::encode($data->toimitusosoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('paivays')); ?>:</b>
	<?php echo CHtml::encode($data->paivays); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('erapaiva')); ?>:</b>
	<?php echo CHtml::encode($data->erapaiva); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('toimituspaiva')); ?>:</b>
	<?php echo CHtml::encode($data->toimituspaiva); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maksuehto')); ?>:</b>
	<?php echo CHtml::encode($data->maksuehto); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('viitenumero')); ?>:</b>
	<?php echo CHtml::encode($data->viitenumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('viivastyskorko')); ?>:</b>
	<?php echo CHtml::encode($data->viivastyskorko); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteensa_total_verot')); ?>:</b>
	<?php echo CHtml::encode($data->yhteensa_total_verot); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteensa_total_veroton')); ?>:</b>
	<?php echo CHtml::encode($data->yhteensa_total_veroton); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yhteensa_total')); ?>:</b>
	<?php echo CHtml::encode($data->yhteensa_total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('saaja_iban')); ?>:</b>
	<?php echo CHtml::encode($data->saaja_iban); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('saaja_virtualkoodi')); ?>:</b>
	<?php echo CHtml::encode($data->saaja_virtualkoodi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tilanne')); ?>:</b>
	<?php echo CHtml::encode($data->tilanne); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maksettu_euro')); ?>:</b>
	<?php echo CHtml::encode($data->maksettu_euro); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hyvityslasku')); ?>:</b>
	<?php echo CHtml::encode($data->hyvityslasku); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('laskun_nimetys')); ?>:</b>
	<?php echo CHtml::encode($data->laskun_nimetys); ?>
	<br />

	*/ ?>

</div>