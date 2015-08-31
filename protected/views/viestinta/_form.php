<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'viestinta-form',
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
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'pvm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekija'); ?>
		<?php echo $form->textField($model,'tekija',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'tekija'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textArea($model,'viesti',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->