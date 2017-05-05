<?php
/* @var $this DigistenTunnitKkController */
/* @var $model DigistenTunnitKk */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'digisten-tunnit-kk-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="row">
 <div class="col-sm-4">

	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'domain'); ?>
		<?php echo $form->textField($model,'domain',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'domain'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'year'); ?>
		<?php echo $form->textField($model,'year', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'year'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'month'); ?>
		<?php echo $form->textField($model,'month', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'month'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tunnit'); ?>
		<?php echo $form->textField($model,'tunnit', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'tunnit'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tasot'); ?>
		<?php echo $form->textArea($model,'tasot',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tasot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'maksettu'); ?>
		<?php echo $form->textField($model,'maksettu', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'maksettu'); ?>
	</div>

 </div>
</div><!-- form -->

	<div class="section buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>


