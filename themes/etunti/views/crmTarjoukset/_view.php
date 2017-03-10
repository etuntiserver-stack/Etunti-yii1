<?php
	$head = '';
	$as = Asiakkaat::model()->findbypk($data->asiakas_id);
	$yht = Yhteystiedot::model()->findbypk($data->yhteystiedot_id);

	if(isset($as->id) and !empty($as->yrityksen_nimi) and empty($as->yhteyshenkilo))
	$head= $as->yrityksen_nimi.', '.$as->osoite;
	elseif(isset($as->id) and empty($as->yrityksen_nimi) and !empty($as->yhteyshenkilo))
	$head = $as->yhteyshenkilo.', '.$as->osoite;
	elseif(isset($yht->id) and !empty($yht->yrityksen_nimi) and empty($yht->yhteyshenkilo))
	$head= $yht->yrityksen_nimi.', '.$yht->osoite;
	elseif(isset($yht->id) and empty($yht->yrityksen_nimi) and !empty($yht->yhteyshenkilo))
	$head = $yht->yhteyshenkilo.', '.$yht->osoite;

	$sahkoposti = '';
	if(isset($as->id) and $data->asiakas_id != 0)
	$sahkoposti = $as->sahkoposti;

	if(isset($yht->id) and $data->yhteystiedot_id != 0)
	$sahkoposti = $yht->sahkoposti;
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
		<?php if(!empty($sahkoposti)) echo $sahkoposti; ?>
	</td>
	<td>
		<?php 				echo '<button class="btn btn-primary btn-block laheta" for="'.$data->id.'">'.Yii::t('main', 'Lähetä').'</button>';
			if(!empty($sahkoposti) and $data->status == 0 and
   		(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf"))
			)
			{
				echo '<button class="btn btn-primary btn-block laheta" for="'.$data->id.'">'.Yii::t('main', 'Lähetä').'</button>';
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


