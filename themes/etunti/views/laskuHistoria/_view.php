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

		    if(count($json) > 0)
		    {
		      foreach($json as $key=>$value)
		      {
			   if(is_array($value))
			   {
				foreach($value as $k=>$v)
				echo "&nbsp;".$k.": ".$v."<br>";

			   } else {
				echo $key.": ".$value."<br>";
			   }
		      }
 		    }

		}
		// Trust -->

		// <-- Postita
		if(isset($data->palvelu) and $data->palvelu == 'postita') 
		{

		    if($json = json_decode($data->status, true)){

		    $str = str_replace(",","<br>",$json);
		    $str = str_replace('"',"",$str);
		    $str = str_replace('[{',"",$str);
		    $str = str_replace('}]',"",$str);

		    } else {
		    $str = $data->status;
		    }

		    echo $str;

		}
		// Postita -->


		// <-- Local
		if( isset($data->palvelu) and ($data->palvelu == 'local' or $data->palvelu == 'netvisor') ) 
		{

		    $str = $data->status;
		    echo $str;

		}
		// Local -->
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
