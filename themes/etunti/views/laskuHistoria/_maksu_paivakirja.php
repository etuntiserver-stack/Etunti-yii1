<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */


	$l = Lasku::model()->findbypk($data->lid);

	$nimi = '';
	$laskunumero = '';
	$as_nro = '';

if(isset($l->id))
{
   if($l->tyyppi == 'henkilo')
	$nimi = $l->nimi;
   if($l->tyyppi == 'yritys')
	$nimi = $l->yritys;

	$laskunumero = $l->laskunumero;
	$as_nro = $l->as_nro;
}
?>

<tr>

	<td>
	<?php echo CHtml::encode($as_nro); ?>
	</td>

	<td>
	<?php echo CHtml::encode($nimi); ?>
	</td>

	<td>
	<?php echo CHtml::link($laskunumero, Yii::app()->request->baseUrl.'/index.php/lasku/update?id='.$data->lid); ?>
	</td>

	<td>
	<?php 
		// Trust
		if($asetukset->palvelu_tyyppi == 2 and isset($data->palvelu) and $data->palvelu == 'trust')
		{

		    $json = json_decode($data->status, true);

		   if(isset($json['statustext']) and !empty($json['statustext']))
		   {
		      	echo '<b>'.date("d.m.Y H:i",strtotime($json['statustime'])).'</b><br> '.$json['statustext'];
		   }

		}

		// Postita tai Local
		if($asetukset->palvelu_tyyppi == 1 or $asetukset->palvelu_tyyppi == 3 or $asetukset->palvelu_tyyppi == 4)
		{
		      	echo '<b>'.date("d.m.Y H:i",strtotime($data->time)).'</b>';
		}


	?>
	</td>

	<td>
	<?php echo number_format($data->yht_euro, 2, ',', ' '); ?>
	</td>


</tr>
