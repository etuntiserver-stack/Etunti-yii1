<?php
/* @var $this KohteetController */
/* @var $data Kohteet */
?>

<tr>
	<td>
		<?php echo $data->nimike; ?>
	</td>
	<td>
		<?php echo $data->hinta; ?>
	</td>
	<td>
		<?php echo $data->selitysteksti; ?>
	</td>
	<td>
		<?php echo $data->kesto; ?>
	</td>
	<td>
		<?php echo $data->nelio; ?>
	</td>
	<td>
		<?php echo $this->palvelu($data->palvelu); ?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>

