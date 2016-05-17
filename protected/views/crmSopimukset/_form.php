<?php
/* @var $this CrmSopimuksetController */
/* @var $model CrmSopimukset */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'crm-sopimukset-form',
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
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php echo $form->textField($model,'asiakas_id'); ?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'teksti'); ?>
		<?php echo $form->textArea($model,'teksti',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'teksti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hyvaksyn_koodi'); ?>
		<?php echo $form->textField($model,'hyvaksyn_koodi',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'hyvaksyn_koodi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'asiakkaan_sahkoposti'); ?>
		<?php echo $form->textField($model,'asiakkaan_sahkoposti',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'asiakkaan_sahkoposti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'liite'); ?>
		<?php echo $form->textField($model,'liite',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'liite'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->