<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */

       	$criteria = new CDbCriteria();
       	$criteria->order = " id DESC ";
       	$criteria->condition = " lid='".$data->id."' ";
	$lh = LaskuHistoria::model()->find($criteria);
	$yht_euro = '';
	if(isset($lh->id))
	$yht_euro = $lh->yht_euro;

	$asiakas='';
	$a = Asiakkaat::model()->find( " asiakasnumero='".$data->as_nro."' ");
	if(isset($a->id) and $a->tyyppi == 'yritys')
	$asiakas = $a->yrityksen_nimi;
	if(isset($a->id) and $a->tyyppi == 'henkilo')
	$asiakas = $a->yhteyshenkilo;
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
	<?php echo $data->id; ?>
	</td>

	<td>
	<?php echo number_format($data->yhteensa_total, 2, ',', ' '); ?>
	</td>

	<td>
	<?php echo number_format($yht_euro, 2, ',', ' '); ?>
	</td>

</tr>
