<?php
/* @var $this OhjevideotController */
/* @var $model Ohjevideot */
/* @var $form CActiveForm */
?>

<div class="form row">
 <div class="col-sm-4">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ohjevideot-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'otsiko'); ?>
		<?php echo $form->textField($model,'otsiko',array('size'=>60,'maxlength'=>500, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'otsiko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kuvaus'); ?>
		<?php echo $form->textArea($model,'kuvaus',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kuvaus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tiedoston_nimi'); ?>
		<?php echo $form->FileField($model,'tiedoston_nimi', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'tiedoston_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sort'); ?>
		<?php echo $form->numberField($model,'sort',array('size'=>60,'maxlength'=>3, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sort'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
 </div>
</div><!-- form -->
