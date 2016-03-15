<?php
/* @var $this OnlinevarausTuotteetController */
/* @var $model OnlinevarausTuotteet */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'onlinevaraus-tuotteet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'nimike'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->textField($model,'hinta',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'selitysteksti'); ?>
		<?php echo $form->textArea($model,'selitysteksti',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'selitysteksti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'palvelu'); ?>
		<?php echo $form->textField($model,'palvelu'); ?>
		<?php echo $form->error($model,'palvelu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kesto'); ?>
		<?php echo $form->textField($model,'kesto',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'kesto'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->