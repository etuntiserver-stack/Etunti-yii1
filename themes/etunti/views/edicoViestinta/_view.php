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
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $asiakas; ?>
	</td>
	<td>
		<?=$data->otsikko?>
	</td>
	<td>
		<?php 
		$lista = '<button class="pull-right btn btn-success" data-toggle="collapse" data-target="#ava_'.$data->id.'">
				Näytä viestit <i class="caret"></i>
		</button>';
		$lista .= '<div id="ava_'.$data->id.'" class="collapse">';
		foreach($data->rivit as $rivi){
			$lista .= '<p><b>'.date("d.m.Y H:i", strtotime($rivi->time)).'</b>: '.$rivi->teksti.'</p>';
		}
		$lista .= '</div>';
		?>
		<?=$lista?>
	</td>
	<td>
		<?php echo CHtml::link('<i class="fa fa-trash-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('delete', 'id'=>$data->id), 
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
</tr>

