<?php
	$head = '';
	$as = Asiakkaat::model()->findbypk($data->asiakas_id);
	if(!empty($as->yrityksen_nimi) and empty($as->yhteyshenkilo))
	$head= $as->yrityksen_nimi.', '.$as->osoite;
	elseif(empty($as->yrityksen_nimi) and !empty($as->yhteyshenkilo))
	$head = $as->yhteyshenkilo.', '.$as->osoite;
?>

<tr>
	<td>
		<?php echo $head; ?>
	</td>
	<td>
	<?php
		if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".docx"))
	 	echo '<a href="../../tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$data->liite.'.docx">'.$data->liite.'.docx</a>';
		echo '<br>';
		if(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
		echo '<a href="../../tiedostot/crm/tarjoukset/'.Yii::app()->user->domain.'/'.$data->liite.'.pdf">'.$data->liite.'.pdf</a>';
		
	?>
	</td>
	<td>
		<?php if(isset($as->sahkoposti)) echo $as->sahkoposti; ?>
	</td>
	<td>
		<?php 
			if(isset($as->sahkoposti) and $data->status == 0 and
   		(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
			)
			{
				echo '<button class="btn btn-primary myBgColors laheta" for="'.$data->id.'">'.Yii::t('main', 'lähetä').'</button>';
			} elseif($data->status == 1){
				echo '<button class="btn btn-success">'.Yii::t('main', 'Lähetetty').'</button>';
			}
		?>
	</td>
	<td>
		<?php echo CHtml::link('', array('update', 'id'=>$data->id), array('class'=>'fa fa-pencil-square-o')); ?>
	</td>
</tr>


