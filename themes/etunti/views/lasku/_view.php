<?php
/* @var $this LaskuController */
/* @var $data Lasku */
$dat = Lasku::model()->findbypk($data->id);
?>
<tr>
	<td>
		<?php echo $data->laskunumero; ?>
	</td>
	<td>
		<?php echo $data->viitenumero; ?>
	</td>
	<td>
		<?php echo date("d.m.Y - H:i",strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $this->tilanneCheck($dat,null); ?>
	</td>
	<td>
		<?php echo $data->tapahtumapvm; ?>
	</td>
	<td>
		<?php echo number_format($data->yhteensa_total, 2, ",", " "); ?>
	</td>
	<td>
		<?php echo $this->avoinnaCheck($dat,null); ?>
	</td>
	<td>
		<?php echo $data->laskun_nimetys; ?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>
