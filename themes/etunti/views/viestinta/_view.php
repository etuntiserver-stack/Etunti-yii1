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
		<?php 
			echo '<p><b>'.Yii::t('main', 'Keskustelu nro.').': '.$data->id.'</b></p>';
			echo str_replace("\n","<br>",$data->viesti); 
		?>
	</td>
	<td>
		<?php echo $this->lahettajaMuutosTheme($data->admin); ?>
	</td>
	<td>
		<?php echo $this->tekijaMuutosTheme($data->tekija); ?>
	</td>
</tr>



<?php
/*


<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pvm')); ?>:</b>
	<?php echo CHtml::encode($data->pvm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekija')); ?>:</b>
	<?php echo CHtml::encode($data->tekija); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('viesti')); ?>:</b>
	<?php echo CHtml::encode($data->viesti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('admin')); ?>:</b>
	<?php echo CHtml::encode($data->admin); ?>
	<br />


</div>
*/
?>
