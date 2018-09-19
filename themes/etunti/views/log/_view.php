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

		<?php if(!empty($data->model)) : ?>
		<?php echo Yii::t('log', $data->model); ?>
		<?php else: ?>
		<?php echo $this->nimikeMuutos($data->log_nimike); ?>
		<?php endif; ?>

	</td>
	<?php if(isset($_GET['log_category']) and $_GET['log_category'] == 1): ?>
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
	<?php elseif(isset($_GET['log_category']) and $_GET['log_category'] == 2): ?>
	<td>
		<?php echo $data->kuka; ?>
	</td>
	<td>
		<?php echo Yii::t('log', $data->tilanne); ?>
	</td>
	<td>
		<?php 
		if(is_array(json_decode($data->old_values, true)) and isset($data->model) and !empty($data->model))
		{
			$m = new $data->model;
			$arr = array();
			$arr = json_decode($data->old_values, true);
			foreach($arr as $k=>$item)
			{
			    if(isset($k) and $k == 'tid')
			    $item = $item.' ('.$this->etuSukunimi($item).')';
			    if(isset($k) and $k == 'kohde' and $this->kohdeOsoite($item))
			    $item = $item.' ('.$this->kohdeOsoite($item).')';
			    if(isset($k) and !empty($item) and $k == 'time')
			    $item = date("d.m.Y H:i", strtotime($item));
			    if(is_array(json_decode($item, true)))
			    $item = '<textarea class="form-control">'.$item.'</textarea>';

			    if(!empty($item))
			    {
				if($m->getAttributeLabel($k))
					echo '<b>'.$m->getAttributeLabel($k).':</b> '.((is_array($item))? json_encode($item) : $item ).'<br>';
				else
					echo '<b>'.$k.':</b> '.((is_array($item))? json_encode($item) : $item ).'<br>';
			    }
			}
		}
		?>
	</td>
	<td>
		<?php 
		if(is_array(json_decode($data->new_values, true)) and isset($data->model) and !empty($data->model))
		{
			$m = new $data->model;
			$arr = array();
			$arr = json_decode($data->new_values, true);
		  	foreach($arr as $k=>$item)
			{
			    if(isset($k) and $k == 'tid')
			    $item = $item.' ('.$this->etuSukunimi($item).')';
			    if(isset($k) and $k == 'kohde' and $this->kohdeOsoite($item))
			    $item = $item.' ('.$this->kohdeOsoite($item).')';
			    if(isset($k) and !empty($item) and $k == 'time')
			    $item = date("d.m.Y H:i", strtotime($item));
			    if(is_array($item))
			    $item = '<textarea class="form-control">'.json_encode($item).'</textarea>';
			    if(is_array(json_decode($item, true)))
			    $item = '<textarea class="form-control">'.$item.'</textarea>';

			    if(!empty($item))
			    {
				if($m->getAttributeLabel($k))
					echo '<b>'.$m->getAttributeLabel($k).':</b> '.((is_array($item))? json_encode($item) : $item ).'<br>';
				else
					echo '<b>'.$k.':</b> '.((is_array($item))? json_encode($item) : $item ).'<br>';
			    }
			}
		}
		?>
	</td>
	<?php elseif(isset($_GET['log_category']) and $_GET['log_category'] == 3): ?>
	<td>
		<?php echo $data->kuka; ?>
	</td>
	<td>
		<?php echo Yii::t('log', $data->tilanne); ?>
	</td>
	<?php endif; ?>
</tr>

