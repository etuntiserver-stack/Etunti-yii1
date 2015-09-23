<?php
/* @var $this LaskutusController */
/* @var $model Laskutus */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'laskutus-form',
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
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID'); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'trtd'); ?>
		<?php echo $form->textArea($model,'trtd',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'trtd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'trtd7'); ?>
		<?php echo $form->textArea($model,'trtd7',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'trtd7'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mista'); ?>
		<?php echo $form->textField($model,'mista',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'mista'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mihin'); ?>
		<?php echo $form->textField($model,'mihin',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'mihin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yhteensa'); ?>
		<?php echo $form->textField($model,'yhteensa',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'yhteensa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'muoto'); ?>
		<?php echo $form->textField($model,'muoto',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'muoto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'asiakkaan_koodi'); ?>
		<?php echo $form->textField($model,'asiakkaan_koodi',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'asiakkaan_koodi'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->