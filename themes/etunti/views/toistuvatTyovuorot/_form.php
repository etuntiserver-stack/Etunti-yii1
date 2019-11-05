<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $model ToistuvatTyovuorot */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'toistuvat-tyovuorot-form',
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
		<?php echo $form->labelEx($model,'pfrom'); ?>
		<?php echo $form->textField($model,'pfrom',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'pfrom'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pto'); ?>
		<?php echo $form->textField($model,'pto',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'pto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viikkoja'); ?>
		<?php echo $form->textField($model,'viikkoja'); ?>
		<?php echo $form->error($model,'viikkoja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viikko_paivat'); ?>
		<?php echo $form->textArea($model,'viikko_paivat',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'viikko_paivat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php echo $form->textField($model,'kohde'); ?>
		<?php echo $form->error($model,'kohde'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>50,'maxlength'=>50)); ?>
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
		<?php echo $form->labelEx($model,'kesto'); ?>
		<?php echo $form->textField($model,'kesto',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kesto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php echo $form->textField($model,'tyoajanmerkinta',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tyoajanmerkinta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyopaari'); ?>
		<?php echo $form->textArea($model,'tyopaari',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tyopaari'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->