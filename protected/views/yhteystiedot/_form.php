<?php
/* @var $this YhteystiedotController */
/* @var $model Yhteystiedot */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'yhteystiedot-form',
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
		<?php echo $form->labelEx($model,'yhteystieto_tyyppi'); ?>
		<?php echo $form->textField($model,'yhteystieto_tyyppi'); ?>
		<?php echo $form->error($model,'yhteystieto_tyyppi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yrityksen_nimi'); ?>
		<?php echo $form->textField($model,'yrityksen_nimi',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'yrityksen_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yhteyshenkilo'); ?>
		<?php echo $form->textField($model,'yhteyshenkilo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'yhteyshenkilo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero'); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ryhma'); ?>
		<?php echo $form->textField($model,'ryhma',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ryhma'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'myyja'); ?>
		<?php echo $form->textField($model,'myyja',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'myyja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->