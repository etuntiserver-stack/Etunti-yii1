<?php
/* @var $this TyotodistusController */
/* @var $model Tyotodistus */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyotodistus-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'key'); ?>
		<?php echo $form->textField($model,'key'); ?>
		<?php echo $form->error($model,'key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyonantaja'); ?>
		<?php echo $form->textField($model,'tyonantaja',array('size'=>60,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'tyonantaja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tekijan_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tekijan_katuosoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'tekijan_pnumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_ptoimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_puh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_henkilotunnus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Alku'); ?>
		<?php echo $form->textField($model,'Alku',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'Alku'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Loppu'); ?>
		<?php echo $form->textField($model,'Loppu',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'Loppu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Tyokohde'); ?>
		<?php echo $form->textArea($model,'Tyokohde',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Tyokohde'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Tyotehtavat'); ?>
		<?php echo $form->textArea($model,'Tyotehtavat',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Tyotehtavat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TyosuhteenPaattamisenSyy'); ?>
		<?php echo $form->textArea($model,'TyosuhteenPaattamisenSyy',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'TyosuhteenPaattamisenSyy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Tyotaito'); ?>
		<?php echo $form->textArea($model,'Tyotaito',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Tyotaito'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Kaytos'); ?>
		<?php echo $form->textArea($model,'Kaytos',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Kaytos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Arvio'); ?>
		<?php echo $form->textArea($model,'Arvio',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Arvio'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Paivays'); ?>
		<?php echo $form->textField($model,'Paivays',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'Paivays'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Paikka'); ?>
		<?php echo $form->textField($model,'Paikka',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Paikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TyonantajanEdustaja'); ?>
		<?php echo $form->textField($model,'TyonantajanEdustaja',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'TyonantajanEdustaja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NimikeTehtava'); ?>
		<?php echo $form->textField($model,'NimikeTehtava',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'NimikeTehtava'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tiedosto'); ?>
		<?php echo $form->textField($model,'tiedosto',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tiedosto'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->