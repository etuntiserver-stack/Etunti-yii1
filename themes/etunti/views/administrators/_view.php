<?php
/* @var $this AdministratorsController */
/* @var $data Administrators */
?>



<tr>
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
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>

