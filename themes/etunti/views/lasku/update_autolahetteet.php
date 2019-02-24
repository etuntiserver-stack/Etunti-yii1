<?php


?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakkaat-form',
	'enableAjaxValidation'=>false, // ala laita true, saat monta asiakaita update aikana netvisorissa
)); ?>


<div class="row">
  <div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Muoka lähete'); ?></h3>
	<?php
	if(isset($model->asiakkaat->id) and $model->asiakkaat->tyyppi == 'yritys'){
		echo $model->asiakkaat->yrityksen_nimi;
	}
	if(isset($model->asiakkaat->id) and $model->asiakkaat->tyyppi == 'henkilo'){
		echo $model->asiakkaat->yhteyshenkilo;
	}
	?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutettu'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'laskutettu', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'laskutettu'); ?>
	</div>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Luo') : Yii::t('main', 'Tallenna'),array('class'=>'btn btn-primary myBgColors luoTallennaAsiakas')); ?>
	</div>
  </div>
</div>

<?php $this->endWidget(); ?>
