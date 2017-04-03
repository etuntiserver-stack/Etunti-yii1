<?php
	$head = '';
	$as = Asiakkaat::model()->findbypk($data->asiakas_id);
	//$yht = Yhteystiedot::model()->findbypk($data->yhteystiedot_id);

	if(isset($as->id) and $as->tyyppi == 'yritys' and !empty($as->yrityksen_nimi))
	$head = $as->yrityksen_nimi.', '.$as->osoite;
	elseif(isset($as->id) and $as->tyyppi == 'henkilo' and !empty($as->yhteyshenkilo))
	$head = $as->yhteyshenkilo.', '.$as->osoite;

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
<!--
	<td>
		<?php echo $head; ?>
	</td>
-->
	<td>
		<?php echo $data->kohteen_osoite; ?>
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
		<?php 
			//  and	(file_exists(Yii::app()->basePath."/../tiedostot/crm/tarjoukset/".Yii::app()->user->domain."/".$data->liite.".pdf")
			if(!empty($data->asiakkaan_sahkoposti) and $data->status == 0)
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
</tr>


