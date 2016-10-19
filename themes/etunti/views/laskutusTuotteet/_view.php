<?php
/* @var $this LaskutusTuotteetController */
/* @var $data LaskutusTuotteet */
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
<?php if($netvisor == true) : ?>
	<td>
		<?php echo $this->onkoNetvisor($data->id); ?>
	</td>
<?php endif; ?>

</tr>
