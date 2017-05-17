<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

		$as = Asiakkaat::model()->findbypk($data->asiakas_id);
			
		$nimi = '';
		$as_id = '';

		if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi))
		$nimi = $as->yrityksen_nimi;
		elseif(isset($as->yhteyshenkilo) and !empty($as->yhteyshenkilo))
		$nimi = $as->yhteyshenkilo;

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


