<?php
/* @var $this KohteetController */
/* @var $data Kohteet */
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
		<?php echo $data->tekijan_nimi; ?>
	</td>
	<td>
		<?php echo $data->teksti; ?>
	</td>
	<td>
	<?php
		if(file_exists(Yii::app()->basePath."/../".$this->polkku()."/".$data->tiedosto.".docx"))
	 	echo '<a href="../../'.$this->polkku().'/'.$data->tiedosto.'.docx">'.$data->tiedosto.'.docx</a>';
		echo '<br>';
		if(file_exists(Yii::app()->basePath."/../".$this->polkku()."/".$data->tiedosto.".pdf"))
		echo '<a href="../../'.$this->polkku().'/'.$data->tiedosto.'.pdf">'.$data->tiedosto.'.pdf</a>';
		
	?>
	</td>
</tr>


