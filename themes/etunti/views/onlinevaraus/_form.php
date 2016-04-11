<?php
/* @var $this OnlinevarausController */
/* @var $model Onlinevaraus */
/* @var $form CActiveForm */
?>

<div class="row">
  <div class="col-sm-3">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'onlinevaraus-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>
 </div>
</div><!-- form -->
