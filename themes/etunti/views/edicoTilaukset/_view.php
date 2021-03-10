<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */
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
		<?php echo date("d.m.Y  H:i",strtotime($data->time)); ?>
	</td>
	<td>
		<?php if(isset($data->asiakkaat->id)) : ?>
		<?php echo $data->asiakkaat->Fullname; ?>
		<?php endif; ?>
	</td>
	<td>
		<?php echo $data->osoite; ?>
	</td>
	<td>
		<?=date("d.m.Y", strtotime($data->toivottu_pvm))?>, <?=$data->toivottu_aloitus?> - <?=$data->toivottu_lopetus?>
	</td>
	<td>
		<?php $tuotteet = json_decode( $data->tuotteet, true ); ?>
		<?php foreach($tuotteet as $item) : ?>
		<div class="well">
		 <?=$item['nimike']?>, 
		 <?=number_format($item['hinta_alv_sis'], 2, ',', ' ')?> &euro; / <?=$item['yksikko']?>
		</div>
		<?php endforeach; ?>
	</td>
	<td>
		<?php echo str_replace("\n","<br>",$data->viesti); ?>
	</td>
</tr>
