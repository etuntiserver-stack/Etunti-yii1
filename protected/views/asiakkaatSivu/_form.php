<?php
/* @var $this AsiakkaatSivuController */
/* @var $model AsiakkaatSivu */
/* @var $form CActiveForm */
?>

<div class="form row">
 <div class="col-sm-12">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakkaat-sivu-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'asiakkaan_nimi'); ?>
		<?php echo $form->textField($model,'asiakkaan_nimi',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakkaan_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'html_content'); ?>
		<?php echo $form->textArea($model,'html_content',array('rows'=>30, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'html_content'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>
 <div>
</div><!-- form -->
