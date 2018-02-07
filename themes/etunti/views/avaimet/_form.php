<?php
/* @var $this AvaimetController */
/* @var $model Avaimet */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'avaimet-form',
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
		<?php echo $form->labelEx($model,'avainnumero'); ?>
		<?php echo $form->textField($model,'avainnumero',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'avainnumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohde_id'); ?>
		<?php echo $form->textField($model,'kohde_id'); ?>
		<?php echo $form->error($model,'kohde_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sijainti'); ?>
		<?php echo $form->textField($model,'sijainti',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'sijainti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lisatiedot'); ?>
		<?php echo $form->textArea($model,'lisatiedot',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'lisatiedot'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->