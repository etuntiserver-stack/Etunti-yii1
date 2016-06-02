<?php
/* @var $this KohteetController */
/* @var $data Kohteet */
?>

<tr>
	<td>
		<?php echo $data->tekijan_nimi; ?>
	</td>
	<td>
		<?php echo $data->kirjallisen_varoituksen; ?>
	</td>
	<td>
	<?php
		if(file_exists(Yii::app()->basePath."/../tiedostot/varoitukset/".Yii::app()->user->domain."/".$data->tiedosto.".docx"))
	 	echo '<a href="../../tiedostot/varoitukset/'.Yii::app()->user->domain.'/'.$data->tiedosto.'.docx">'.$data->tiedosto.'.docx</a>';
		echo '<br>';
		if(file_exists(Yii::app()->basePath."/../tiedostot/varoitukset/".Yii::app()->user->domain."/".$data->tiedosto.".pdf"))
		echo '<a href="../../tiedostot/varoitukset/'.Yii::app()->user->domain.'/'.$data->tiedosto.'.pdf">'.$data->tiedosto.'.pdf</a>';
		
	?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>


