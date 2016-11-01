<?php
/* @var $this AdministratorsController */
/* @var $data Administrators */
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
		<?php echo $data->id; ?>
	</td>
	<td>
		<?php echo $data->adm_login; ?>
	</td>
	<td>
		<?php echo $data->adm_email; ?>
	</td>
	<td>
		<?php echo $data->adm_nimi; ?>
	</td>
	<td>
		<?php echo $data->status; ?>
	</td>
</tr>

