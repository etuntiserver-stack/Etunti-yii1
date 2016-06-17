<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

		$as = Asiakkaat::model()->findbypk($data->asiakas_id);
			
		$nimi = '';
		if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi))
		$nimi = $as->yrityksen_nimi;
		elseif(isset($as->yhteyshenkilo) and !empty($as->yhteyshenkilo))
		$nimi = $as->yhteyshenkilo;

?>

<tr>
	<td>
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $nimi; ?>
	</td>
	<td>
		<?php echo $data->teksti; ?>
	</td>
	<td>
		<?php echo $this->tilaMuutos($data->tila); ?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>


