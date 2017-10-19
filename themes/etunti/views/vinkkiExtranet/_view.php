<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

		$as = Asiakkaat::model()->findbypk($data->asiakas_id);
			
		$nimi = '';
		$as_id = '';

		if(isset($as->id) and $as->tyyppi == 'yritys')
		$nimi = $as->yrityksen_nimi;
		if(isset($as->id) and $as->tyyppi == 'henkilo')
		$nimi = $as->yhteyshenkilo;
		if(isset($as->id))
		$as_id = $as->id;

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
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
		<?php if(!empty($data->muutos_pvm)): ?>
		<br>
		<?=Yii::t('main', 'Muokkattu').' '.date("d.m.Y H:i", strtotime($data->muutos_pvm))?>
		<?php endif; ?>
	</td>
	<td>
		<?php echo CHtml::link($nimi, array('//asiakkaat/update', 'id'=>$as_id), array('class'=>'link')); ?>
	</td>
	<td>
		<?php echo $data->nimi; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $data->teksti; ?>
	</td>
	<td>
		<?php echo $this->tilaMuutos($data); ?>
	</td>
</tr>


