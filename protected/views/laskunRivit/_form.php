<?php
/* @var $this LaskunRivitController */
/* @var $model LaskunRivit */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'laskun-rivit-form',
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
		<?php echo $form->labelEx($model,'lid'); ?>
		<?php echo $form->textField($model,'lid'); ?>
		<?php echo $form->error($model,'lid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rivi'); ?>
		<?php echo $form->textField($model,'rivi'); ?>
		<?php echo $form->error($model,'rivi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tkoodi'); ?>
		<?php echo $form->textField($model,'tkoodi'); ?>
		<?php echo $form->error($model,'tkoodi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'nimike'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kpl'); ?>
		<?php echo $form->textField($model,'kpl'); ?>
		<?php echo $form->error($model,'kpl'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yksikko'); ?>
		<?php echo $form->textField($model,'yksikko',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'yksikko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->textField($model,'hinta',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alv'); ?>
		<?php echo $form->textField($model,'alv',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hinta_alv'); ?>
		<?php echo $form->textField($model,'hinta_alv',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'hinta_alv'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ale'); ?>
		<?php echo $form->textField($model,'ale',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'ale'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'veroton'); ?>
		<?php echo $form->textField($model,'veroton',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'veroton'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yhteensa_alv'); ?>
		<?php echo $form->textField($model,'yhteensa_alv',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'yhteensa_alv'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->