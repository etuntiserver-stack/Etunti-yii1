<?php
/* @var $this KohteetController */
/* @var $data Kohteet */
?>

<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>
	<td>
		<?php 
		if(isset($data->domain->yritys))
		{
			echo 'Yritys: <b>'.$data->domain->yritys.'</b><br>'; 
			echo 'Domain: <b>'.$data->domain->domain.'</b>'; 
		}
		?>
	</td>
	<td>
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo Yii::t('main', $data->tapahtuma); ?>
	</td>
	<td>
		<?php echo Yii::t('main', $data->paketti); ?>
	</td>
</tr>

