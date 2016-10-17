<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */

  $osoite = $data->osoite;
  if(!empty($data->postinumero))
  $osoite .= ', '.$data->postinumero;
  if(!empty($data->kaupunki))
  $osoite .= ', '.$data->kaupunki;
?>

<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 
				array('update', 'id'=>$data->id), 
				array(
					'style'=>'font-size: 150%', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>

	<td>
		<?php echo $data->yrityksen_nimi; ?>
	</td>
	<td>
		<?php echo $osoite; ?>
	</td>
	<td>
		<?php echo $data->yhteyshenkilo; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $this->ryhmaMuutos($data->ryhma); ?>
	</td>
	<td>
		<?php echo $data->tyyppi; ?>
	</td>

<?php if($netvisor == true) : ?>
	<td>
		<?php echo $this->onkoNetvisor($data->id); ?>
	</td>
<?php endif; ?>

<?php if($this->tas(2)) : ?>
	<td align="center">
		<?php echo CHtml::link('<i class="fa fa-table" aria-hidden="true"></i>', 
				array('showshift', 'id'=>$data->id), 
				array(
					'style'=>'font-size: 150%',
					'data-toggle'=>'tooltip',
					'data-placement'=>'top',
					'title'=>Yii::t('main', 'Näytä tyovuorot') 
				)
			); 
		?>
	</td>
<?php endif; ?>

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


