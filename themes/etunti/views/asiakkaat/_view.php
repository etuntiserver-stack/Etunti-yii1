<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */
?>

<tr>
	<td>
		<?php echo $data->id; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?php echo $this->asiakasMuutosTheme($data->yhteyshenkilo); ?>
	</td>
	<td>
		<?php echo $data->postinumero; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $data->tyyppi; ?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>

<?php
/*

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('etunimi')); ?>:</b>
	<?php echo CHtml::encode($data->etunimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sukunimi')); ?>:</b>
	<?php echo CHtml::encode($data->sukunimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kaupunki')); ?>:</b>
	<?php echo CHtml::encode($data->kaupunki); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postinumero')); ?>:</b>
	<?php echo CHtml::encode($data->postinumero); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('puhelin')); ?>:</b>
	<?php echo CHtml::encode($data->puhelin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ryhma')); ?>:</b>
	<?php echo CHtml::encode($data->ryhma); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('aktiivinen')); ?>:</b>
	<?php echo CHtml::encode($data->aktiivinen); ?>
	<br />

	*/ ?>


