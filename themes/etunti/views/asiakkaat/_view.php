<?php
/* @var $this AsiakkaatController */
/* @var $data Asiakkaat */
  $tyyppi = '';
  $tauste = 'bg-default';
  if($data->tyyppi == 'yritys'){
  	$tyyppi = Yii::t('main', 'Yritys');
	$tauste = 'bg-primary';
  } else if($data->tyyppi == 'henkilo') {
  	$tyyppi = Yii::t('main', 'Henkilö');
	$tauste = 'bg-warning';
  }
?>

<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>
	<td>
		<p><a href="https://www.google.com/maps/place/<?=urlencode($data->osoite)?>,<?=urlencode($data->postinumero)?> <?=urlencode($data->kaupunki)?>" target="_blank"><span class="fa fa-2x fa-map-marker"></span></a></p>
	</td>
	<?php if($this->tas(2)) : ?>
	<td align="center">
		<?php 
/*
		$criteria=new CDbCriteria;
		$criteria->condition = " 
			peruutettu=0
			AND kohde IN( SELECT id FROM sivex_kohdet WHERE asiakas_id='".$data->id."')
		";
		$chktv = Tyovuoroot::model()->find($criteria);
		if( isset($chktv->id) ){

		Se tekee tosi hidas sivu
*/
			echo CHtml::link('<i class="fa fa-table" aria-hidden="true"></i>', 
				array('showshift', 'id'=>$data->id), 
				array(
					'style'=>'font-size: 150%',
					'data-toggle'=>'tooltip',
					'data-placement'=>'top',
					'title'=>Yii::t('main', 'Näytä tyovuorot') 
				)
			); 
		//}
		?>
	</td>
	<?php endif; ?>
	<td>
		<?php echo $data->yrityksen_nimi; ?>
	</td>
	<td>
		<?php echo $data->asiakasnumero; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?php echo $data->postinumero; ?>
	</td>
	<td>
		<?php echo $data->kaupunki; ?>
	</td>
	<td>
		<?php echo $data->Etusukunimi; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $this->TyoryhmaName($data->tyoryhma); ?>
	</td>
	<td>
		<?php echo $this->ryhmaMuutos($data->ryhma); ?>
	</td>
	<td class="<?php echo $tauste; ?>" align="center">
		<?php echo $tyyppi; ?>
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


