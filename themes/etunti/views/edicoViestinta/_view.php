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
		<?=$data->id?>
	</td>
	<td>
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $data->luoja; ?>
	</td>
	<td>
		<?php echo $asiakas; ?>
	</td>
	<td>
		<?=$data->otsikko?>
	</td>
	<td>
		<?php if( count($data->rivit) > 0 and isset(max($data->rivit)->teksti) ){ echo max($data->rivit)->teksti; } ?>
	</td>
	<td>
		<?php 
		$lista = '<button class="btn btn-primary myBgColors" data-toggle="collapse" data-target="#ava_'.$data->id.'">
				Näytä kaikki viestit <i class="caret"></i>
		</button>';
		$lista .= '<div id="ava_'.$data->id.'" class="collapse"><p>';
		foreach($data->rivit as $rivi){
			$lista .= '<p><b>'.date("d.m.Y H:i", strtotime($rivi->time)).'</b>: '.$rivi->teksti.'</p>';
		}
		$lista .= '</p></div>';
		?>
		<?=$lista?>
	</td>
	<td>
		<form action="index" method="POST">
		<input type="hidden" name="id" value="<?=$data->id?>">
		<input type="hidden" name="asiakas_id" value="<?=$data->asiakas_id?>">
		<textarea name="vastaus" class="form-control" placeholder="Vasta tähään keskusteluun.."></textarea>
		<button type="submit" class="btn btn-primary btn-block myBgColors">Lähetä</button>
		</form>
	</td>
	<td>
		<?php echo CHtml::link('<i class="fa fa-trash-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('delete', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Poista') 
				)
			); 
		?>
	</td>
</tr>

