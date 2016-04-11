<?php
/* @var $this OnlinevarausController */
/* @var $data Onlinevaraus */
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
		<?php 
		$k = Kohteet::model()->findbypk($data->kohde_id);
		if(isset($k->id))
			   echo $k->osoite;
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
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>

