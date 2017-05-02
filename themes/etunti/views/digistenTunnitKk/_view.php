<?php
/* @var $this DigistenTunnitKkController */
/* @var $data DigistenTunnitKk */
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
		<?php echo $data->domain; ?>
	</td>
	<td>
		<?php echo $data->year.' / '.$data->month; ?>
	</td>
	<td>
		<?php echo $data->tunnit; ?>
	</td>
</tr>

