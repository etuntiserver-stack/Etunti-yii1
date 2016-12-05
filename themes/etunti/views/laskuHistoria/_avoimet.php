<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */

/*
       	$criteria = new CDbCriteria();
       	$criteria->select = " palvelu,id,status ";
       	$criteria->order = " id DESC ";
       	$criteria->condition = " lid='".$data->id."' ";
	$l = LaskuHistoria::model()->find($criteria);


	$nimi = '';
   if($data->tyyppi == 'henkilo')
	$nimi = $data->nimi;
   if($data->tyyppi == 'yritys')
	$nimi = $data->yritys;
*/

	$l = Lasku::model()->findbypk($data->lid);
	$nimi = '';
   if(isset($l->tyyppi) and $l->tyyppi == 'henkilo')
	$nimi = $l->nimi;
   if(isset($l->tyyppi) and $l->tyyppi == 'yritys')
	$nimi = $l->yritys;
?>

<tr>

	<td>
	<?php echo CHtml::encode($nimi); ?>
	</td>

	<td>
	<?php echo CHtml::encode($data->laskunumero); ?>
	</td>

<?php /*
	<td>
	<?php 

		  if(isset($data->palvelu) and $data->palvelu == 'trust')
		  {

		    $json = json_decode($data->status, true);

		    if(is_array($json))
		    {
			echo '<pre>';
		      	print_r($json);
			echo '</pre>';
		    }

		  }


	?>
	</td>
*/ ?>

	<td>
	<?php echo $data->viitenumero; ?>
	</td>

	<td>
	<?php echo number_format($data->yht_euro, 2, ",", " "); ?>
	</td>

	<td>
	<?php echo CHtml::encode($data->palvelu); ?>
	</td>
</tr>
