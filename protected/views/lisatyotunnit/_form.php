<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lisatyotunnit-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

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
		<?php echo $form->labelEx($model,'syy'); ?>
		<?php echo $form->textField($model,'syy',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'syy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prosentti'); ?>
		<?php echo $form->textField($model,'prosentti',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'prosentti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tunnimaara'); ?>
		<?php echo $form->textField($model,'tunnimaara',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'tunnimaara'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->