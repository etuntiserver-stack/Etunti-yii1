<?php
      	$ryh = Valikkoot::model()->findbypk($data->ryhma);

?>

<tr>
	<td>
		<?php echo $ryh->value; ?>
	</td>
	<td>
		<?php echo $data->teksti; ?>
	</td>
	<td>
	<?php
		if(file_exists(Yii::app()->basePath."/../tiedostot/crm/kirje/".Yii::app()->user->domain."/".$data->liite.".docx"))
	 	echo '<a href="../../tiedostot/crm/kirje/'.Yii::app()->user->domain.'/'.$data->liite.'.docx">'.$data->liite.'.docx</a>';
		echo '<br>';
		if(file_exists(Yii::app()->basePath."/../tiedostot/crm/kirje/".Yii::app()->user->domain."/".$data->liite.".pdf"))
		echo '<a href="../../tiedostot/crm/kirje/'.Yii::app()->user->domain.'/'.$data->liite.'.pdf">'.$data->liite.'.pdf</a>';
		
	?>
	</td>
	<td>
		<?php 
			if($data->status == 0 and
   		(file_exists(Yii::app()->basePath."/../tiedostot/crm/kirje/".Yii::app()->user->domain."/".$data->liite.".pdf"))
			)
			{
				echo '<button class="btn btn-primary btn-block laheta" for="'.$data->id.'">'.Yii::t('main', 'lähetä').'</button>';
			} elseif($data->status == 1){
				echo '<button class="btn btn-warning btn-block">'.Yii::t('main', 'Lähetetty').'</button>';
			} elseif($data->status == 2){
				echo '<button class="btn btn-success btn-block">'.Yii::t('main', 'Hyväksytty').'</button>';
			} elseif($data->status == 3){
				echo '<button class="btn btn-danger btn-block">'.Yii::t('main', 'Hylätty').'</button>';
			}
		?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>


