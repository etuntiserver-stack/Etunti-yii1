<?php
/* @var $this DigistenYritysLogController */
/* @var $model DigistenYritysLog */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'digisten-yritys-log-form',
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
		<?php echo $form->labelEx($model,'yritys_id'); ?>
		<?php echo $form->textField($model,'yritys_id'); ?>
		<?php echo $form->error($model,'yritys_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tapahtuma'); ?>
		<?php echo $form->textArea($model,'tapahtuma',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tapahtuma'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->