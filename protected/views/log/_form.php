<?php
/* @var $this LogController */
/* @var $model Log */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'log-form',
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
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kuka'); ?>
		<?php echo $form->textField($model,'kuka',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'kuka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'log_category'); ?>
		<?php echo $form->textField($model,'log_category'); ?>
		<?php echo $form->error($model,'log_category'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_to'); ?>
		<?php echo $form->textField($model,'email_to',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_subject'); ?>
		<?php echo $form->textField($model,'email_subject',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email_subject'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_message'); ?>
		<?php echo $form->textArea($model,'email_message',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'email_message'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_attachment'); ?>
		<?php echo $form->textField($model,'email_attachment',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'email_attachment'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->