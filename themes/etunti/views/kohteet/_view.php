<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

	$asiakas='';
	$a = Asiakkaat::model()->findbypk($data->asiakas_id);
	if(isset($a->id) and !empty($a->yrityksen_nimi))
	$asiakas = $a->yrityksen_nimi;
	elseif(isset($a->id) and empty($a->yrityksen_nimi) and !empty($a->yhteyshenkilo))
	$asiakas = $a->yhteyshenkilo;

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
		<?php echo $asiakas; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?php echo $data->etu_suku_nimet; ?>
	</td>
	<td>
		<?php echo $data->email; ?>
	</td>
	<td>
		<?php echo $data->puh_nro; ?>
	</td>
	<td>
		<?php echo $data->avain; ?>
	</td>
	<td>
		<?php echo ($data->aktiivinen == 1)? Yii::t('main', 'Kyllä'):'<span class="text-danger">'.Yii::t('main', 'Ei').'</span>'; ?>
	</td>
</tr>

