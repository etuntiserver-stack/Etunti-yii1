<?php

?>
<?php $html = $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => Yii::app()->request->baseUrl.'/css/profile.css',
	'attributes'=>array(
		//'id',
		'asiakasnumero',
		//'time',
		'yrityksen_nimi',
		'y_tunnus',
		'yhteyshenkilo',
		'osoite',
		'kaupunki',
		'postinumero',
		'puhelin',
		'sahkoposti',
		'ryhma',
		'maksuehto',
		'viivastyskorko',
		//'aktiivinen',
	),
), true); ?>

		 <?=$html?>

<?php
	if( isset($_POST['asiakasID']) and isset($_POST['getExcel']) ){ // from eDico
	   $mobile = Yii::app()->createController('Mobile');
	   $mobile[0]->htmlToXlsSaveToTempeDico($html, 'asiakas_tiedot_xls_'.$_POST['asiakasID']);
	}
	if( isset($_POST['asiakasID']) and isset($_POST['getPDF']) ){ // from eDico
	   $mobile = Yii::app()->createController('Mobile');
	   $mobile[0]->htmlToPDFSaveToTempeDico($html, 'asiakas_tiedot_pdf_'.$_POST['asiakasID']);
	}
?>

    	  <button class="btn btn-primary myBgColors getExcel" asiakas_id="<?=$model->id?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors getPDF" asiakas_id="<?=$model->id?>"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>

