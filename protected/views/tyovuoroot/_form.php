<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
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
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php echo $form->textField($model,'kohde',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'kohde'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'pvm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alku'); ?>
		<?php echo $form->textField($model,'alku',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'alku'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<?php echo $form->textField($model,'loppu',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'loppu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pituus'); ?>
		<?php echo $form->textField($model,'pituus',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'pituus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ruokatauko'); ?>
		<?php echo $form->textField($model,'ruokatauko',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'ruokatauko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alku_r'); ?>
		<?php echo $form->textField($model,'alku_r',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'alku_r'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kesto'); ?>
		<?php echo $form->textField($model,'kesto',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kesto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php echo $form->textField($model,'tyoajanlaatu',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tyoajanlaatu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php echo $form->textField($model,'tyoajanmerkinta',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tyoajanmerkinta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoiteOnline'); ?>
		<?php echo $form->textField($model,'osoiteOnline',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'osoiteOnline'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->