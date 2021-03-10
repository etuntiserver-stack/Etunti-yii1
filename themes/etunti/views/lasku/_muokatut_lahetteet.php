<?php

?>
<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update_autolahetteet', 'id'=>$data->id), 
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
	<td><?=date("d.m.Y H:i", strtotime($data->time))?></td>
	<td>
	<?php
	if(isset($data->asiakkaat->id)){
		echo $data->asiakkaat->Fullname;
	}
	?>
	</td>

	<td><b><?=date("d.m.Y", strtotime($data->from_date))?> - <?=date("d.m.Y", strtotime($data->to_date))?></b></td>
	<?php /* <td><?=($data->laskutettu == 1)? 'Laskutettu':'Ei laskutettu' ?></td> */ ?>
	<td>
		<?php echo CHtml::link('<i class="fa fa-trash" aria-hidden="true" style="font-size: 110%"></i>', 
				array('delete_autolahetteet', 'id'=>$data->id), 
				array(
					'class'=>'pull-right btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>
</tr>
