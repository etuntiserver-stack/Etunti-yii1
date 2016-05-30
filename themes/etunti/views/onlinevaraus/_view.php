<?php
/* @var $this OnlinevarausController */
/* @var $data Onlinevaraus */

$tilauksen_kuvaus = json_decode($data->tilauksen_kuvaus, true);
?>

<tr>
	<td>
		<?php echo date("d.m.Y  H:i",strtotime($data->time)); ?>
	</td>
	<td>
		<?php 
		$tv = Tyovuoroot::model()->findbypk($data->tv_id);
		if(isset($tv->id))
		{
			$tekija = Tyontekijat::model()->findbypk($tv->tid);
			if(isset($tekija->id))
			{
			   echo '
				<b>'.$tekija->tekijan_nimi.'</b><br>
				'.$tv->pvm.'<br>
				'.$tv->alku.'-'.$tv->loppu.'
			   ';
			}
		}
		?>
	</td>
	<td>
		<?php echo $data->kesto.'h'; ?>
	</td>
	<td>
		<?php echo $data->hinta.'&euro;'; ?>
	</td>
	<td>
		<?php if($data->tila == 1) echo 'Maksettu'; ?>
	</td>
	<td>
		<?php

if(isset($tilauksen_kuvaus['paa']) and isset($tilauksen_kuvaus['lisa']))
{
  foreach($tilauksen_kuvaus['paa'] as $k=>$v)
	echo $k.' '.$v.' m²<br>';
  foreach($tilauksen_kuvaus['lisa'] as $k=>$v)
	echo  $k.' '.$v.' h<br>';
}
		?>
	</td>

	<td>
		<?php echo $data->yhteyshenkilo; ?>
	</td>
	<td>
		<?php echo $data->puhelin; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?php echo $data->sahkoposti; ?>
	</td>
	<td>
		<?php echo $data->lisatietoja; ?>
	</td>

	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>

