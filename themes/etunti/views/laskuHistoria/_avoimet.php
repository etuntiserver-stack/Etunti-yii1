<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */

   $criteria = new CDbCriteria();
   $criteria->order = " time DESC ";
   $criteria->condition = " lid='".$data->id."' ";
  $lh = LaskuHistoria::model()->find($criteria);

  $trust_statuscode = '';
  if(isset($lh->trust_statuscode))
  $trust_statuscode = $lh->trust_statuscode;

	$nimi = '';
   if($data->tyyppi == 'henkilo')
	$nimi = $data->nimi;
   if($data->tyyppi == 'yritys')
	$nimi = $data->yritys;

?>

<tr>

	<td>
	<?php echo CHtml::encode($data->laskunumero).', id:'.$data->id; ?>
	</td>

	<td>
	<?php echo $trust_statuscode; ?>
	</td>

	<td>
	<?php //echo $data->amount; ?>
	</td>

	<td>
	<?php //echo date("d.m.Y H:i:s",strtotime($data->time)); ?>
	</td>

	<td>
	<?php //echo number_format($data->yht_euro, 2, ',', ' '); ?>
	</td>

	<td>
	<?php echo CHtml::encode($data->as_nro.' '.$nimi); ?>
	</td>

</tr>
