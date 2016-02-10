<?php
/* @var $this LaskuHistoriaController */
/* @var $data LaskuHistoria */
?>

<tr>

	<td>
	<?php echo $data->lid; ?>
	</td>

	<td>
	<?php echo date("d.m.Y H:i",strtotime($data->time)); ?>
	</td>

	<td>
	<?php
		// <-- Trust
		if(isset($data->palvelu) and $data->palvelu == 'trust') 
		{
		    $json = json_decode($data->status, true);

		    echo '<pre>';
		    print_r($json);
		    echo '</pre>';
		}
		// Trust -->
	?>
	</td>

	<td>
	<?php echo $data->palvelu; ?>
	</td>

	<td>
	<?php echo $data->yht_euro; ?>
	</td>

	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>

</tr>
