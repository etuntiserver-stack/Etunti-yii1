<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */

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

?>

<tr>

	<td>
	<?php echo CHtml::encode($nimi); ?>
	</td>

	<td>
	<?php echo CHtml::encode($data->laskunumero); ?>
	</td>

	<td>
	<?php 

		  if(isset($l->palvelu) and $l->palvelu == 'trust')
		  {

		    $json = json_decode($l->status, true);

		    if(isset($json['statustext']) and !empty($json['statustext']))
		    {
		      	echo '<b>'.date("d.m.Y H:i",strtotime($json['statustime'])).'</b><br> '.$json['statustext'];
		    }

		  }

	?>
	</td>

	<td>
	<?php echo $data->viitenumero; ?>
	</td>

	<td>
	<?php echo CHtml::encode($l->palvelu); ?>
	</td>

	<td>
		<?php echo number_format($data->yhteensa_total, 2, ",", " "); ?>
	</td>


</tr>
