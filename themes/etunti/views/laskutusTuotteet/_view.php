<?php
/* @var $this LaskutusTuotteetController */
/* @var $data LaskutusTuotteet */
?>

<tr>
	<td>
		<?php echo $data->tuotenimi; ?>
	</td>
	<td>
		<?php echo $data->hinta_alv_0; ?>
	</td>
	<td>
		<?php echo $data->hinta_alv_sis; ?>
	</td>
	<td>
		<?php echo $data->alv; ?>
	</td>
	<td>
		<?php echo $data->yksikko; ?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>
