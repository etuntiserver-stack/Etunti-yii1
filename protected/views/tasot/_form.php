<?php
/* @var $this TasotController */
/* @var $model Tasot */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tasot-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'taso'); ?>
		<?php echo $form->textField($model,'taso'); ?>
		<?php echo $form->error($model,'taso'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nimetys'); ?>
		<?php echo $form->textField($model,'nimetys',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nimetys'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kuvaus'); ?>
		<?php echo $form->textArea($model,'kuvaus',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'kuvaus'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->