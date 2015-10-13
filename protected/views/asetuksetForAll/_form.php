<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asetukset-for-all-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'asetus'); ?>
		<?php echo $form->textField($model,'asetus',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'asetus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'api_access_key'); ?>
		<?php echo $form->textField($model,'api_access_key',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'api_access_key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'muut'); ?>
		<?php echo $form->textField($model,'muut',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'muut'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->