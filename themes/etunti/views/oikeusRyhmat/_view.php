<?php
/* @var $this OikeusRyhmatController */
/* @var $data OikeusRyhmat */
?>

<tr>
	<td>
		<?php echo $data->nimike; ?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>
