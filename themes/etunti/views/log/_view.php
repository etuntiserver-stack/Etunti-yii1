<?php
/* @var $this KohteetController */
/* @var $data Kohteet */


?>

<tr>
<!--
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
-->
	<td>
		<?php echo date("d.m.Y H:i", strtotime($data->time)); ?>
	</td>
	<td>
		<?php echo $this->nimikeMuutos($data->log_nimike); ?>
	</td>
	<?php if(isset($_POST['log_category']) and $_POST['log_category'] == 1): ?>
	<td>
		<?php echo $data->email_to; ?>
	</td>
	<td>
		<?php echo $data->email_subject; ?>
	</td>
	<td>
		<?php if(!empty($data->email_message)): ?>
			<span class="btn btn-default nayta" for="<?php echo $data->id; ?>" get="email_message"><?php echo Yii::t('main', 'Näytä'); ?></span>
		<?php endif; ?>
	</td>
	<td>
		<?php if(!empty($data->email_attachment_sisalto)): ?>
			<span class="btn btn-default nayta" for="<?php echo $data->id; ?>" get="email_attachment_sisalto"><?php echo Yii::t('main', 'Näytä'); ?></span>
		<?php endif; ?>
	</td>
	<?php elseif(isset($_POST['log_category']) and $_POST['log_category'] == 2): ?>
	<td>
		<?php echo $data->tapahtuma; ?>
	</td>
	<?php endif; ?>
</tr>

