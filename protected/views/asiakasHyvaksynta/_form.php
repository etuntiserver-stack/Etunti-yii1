<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $model AsiakasHyvaksynta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakas-hyvaksynta-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php echo $form->textField($model,'asiakas_id'); ?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ids'); ?>
		<?php echo $form->textArea($model,'ids',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'ids'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'code'); ?>
		<?php echo $form->textField($model,'code',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'selitys'); ?>
		<?php echo $form->textArea($model,'selitys',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'selitys'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kirjen_body'); ?>
		<?php echo $form->textArea($model,'kirjen_body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'kirjen_body'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->