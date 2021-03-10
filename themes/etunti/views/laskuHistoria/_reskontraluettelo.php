<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */

	$maksupvm = '';

if($asetukset->palvelu_tyyppi == 2)
{
       	$criteria = new CDbCriteria();
       	$criteria->order = "id DESC";
       	$criteria->condition = " 
		lid='".$data->id."' 
		AND trust_statuscode='101'
	";
	$lh = LaskuHistoria::model()->find($criteria);

	if(isset($lh->id))
	$maksupvm = date("d.m.Y",strtotime($lh->time));
}

	$yht_euro = 0;
	if(isset($lh->id))
	$yht_euro = $lh->yht_euro;

	$asiakas='';
	$a = Asiakkaat::model()->find( " asiakasnumero='".$data->as_nro."' ");
	if(isset($a->id))
		$asiakas = $a->Fullname;
?>

<tr>

	<td>
	<?php echo CHtml::encode($data->laskunumero); ?>
	</td>

	<td>
	<?php echo CHtml::encode($asiakas); ?>
	</td>

	<td>
	<?php echo date("d.m.Y",strtotime($data->paivays)); ?>
	</td>

	<td>
	<?php echo $maksupvm; ?>
	</td>

	<td>
	<?php echo date("d.m.Y",strtotime($data->erapaiva)); ?>
	</td>

	<td>
	<?php echo number_format($data->yhteensa_total, 2, ',', ' '); ?>
	</td>

	<td>
	<?php echo number_format($yht_euro, 2, ',', ' '); ?>
	</td>

</tr>
