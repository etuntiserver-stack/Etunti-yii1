<?php
/* @var $this DigistenTunnitKkController */
/* @var $model DigistenTunnitKk */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'digisten-tunnit-kk-form',
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
		<?php echo $form->labelEx($model,'domain'); ?>
		<?php echo $form->textField($model,'domain',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'domain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'year'); ?>
		<?php echo $form->textField($model,'year'); ?>
		<?php echo $form->error($model,'year'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'month'); ?>
		<?php echo $form->textField($model,'month'); ?>
		<?php echo $form->error($model,'month'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tunnit'); ?>
		<?php echo $form->textField($model,'tunnit'); ?>
		<?php echo $form->error($model,'tunnit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tasot'); ?>
		<?php echo $form->textArea($model,'tasot',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tasot'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maksettu'); ?>
		<?php echo $form->textField($model,'maksettu'); ?>
		<?php echo $form->error($model,'maksettu'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->