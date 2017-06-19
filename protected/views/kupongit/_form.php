<?php
/* @var $this KupongitController */
/* @var $model Kupongit */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kupongit-form',
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
		<?php echo $form->labelEx($model,'kupongin_id'); ?>
		<?php echo $form->textField($model,'kupongin_id'); ?>
		<?php echo $form->error($model,'kupongin_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'voimassa'); ?>
		<?php echo $form->textField($model,'voimassa'); ?>
		<?php echo $form->error($model,'voimassa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'euro_maara'); ?>
		<?php echo $form->textField($model,'euro_maara'); ?>
		<?php echo $form->error($model,'euro_maara'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'prosentti_maara'); ?>
		<?php echo $form->textField($model,'prosentti_maara'); ?>
		<?php echo $form->error($model,'prosentti_maara'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maara_tyyppi'); ?>
		<?php echo $form->textField($model,'maara_tyyppi',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'maara_tyyppi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'jatkuva'); ?>
		<?php echo $form->textField($model,'jatkuva'); ?>
		<?php echo $form->error($model,'jatkuva'); ?>
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