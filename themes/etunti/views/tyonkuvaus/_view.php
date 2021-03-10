<?php
/* @var $this TarjouslaskentaController */
/* @var $data Tarjouslaskenta */
	$asiakas='';
	$a = Asiakkaat::model()->findbypk($data->asiakas_id);
	if(isset($a->id))
	$asiakas = $a->Fullname;

	$y = Yhteystiedot::model()->findbypk($data->yhteystiedot_id);
	if(isset($y->id) and !empty($y->yrityksen_nimi))
	$asiakas = $y->yrityksen_nimi;
	elseif(isset($y->id) and empty($y->yrityksen_nimi) and !empty($y->yhteyshenkilo))
	$asiakas = $y->yhteyshenkilo;

	$k = Kohteet::model()->findbypk($data->kohde_id);
	$osoite='';
	if(isset($k->id))
	$osoite=$k->osoite.', '.$k->kaupunki.' '.$k->pnumero;

	$asiakas_id = '';
	if(isset($a->id))
	$asiakas_id = $a->id;
?>

<tr>

	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o btn btn-primary myBgColors" aria-hidden="true"></i>', 
				array('update', 'id'=>$data->id, 'asiakas_id' => $asiakas_id), 
				array(
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
		<?php echo $osoite; ?>
	</td>
	<td>
		<?php echo date("d.m.Y", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo ($data->aktiivinen == 1) ? 'Aktiivinen' : 'Passivinen'; ?>
	</td>
	<td>
		<?php echo CHtml::link('<i class="fa fa-file-pdf-o btn btn-primary myBgColors" aria-hidden="true"></i>', 
				array('pdf', 'id'=>$data->id, 'open_status' => 'openPDF'), 
				array(
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'PDF'),
					'target' => '_blank'
				)
			); 
		?>
	</td>
</tr>

