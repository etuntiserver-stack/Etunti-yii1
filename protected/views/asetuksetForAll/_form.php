<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */
/* @var $form CActiveForm */
?>

<div class="row form">
  <div class="col-sm-3">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asetukset-for-all-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'asetus'); ?>
		<?php echo $form->textField($model,'asetus',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asetus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'api_access_key'); ?>
		<?php echo $form->textField($model,'api_access_key',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'api_access_key'); ?>
	</div>
  </div>
</div><!-- form -->

<div class="row form">
  <div class="col-sm-12">
	<div class="row">
		<?php echo $form->labelEx($model,'ohjesivu'); ?>
		<?php echo $form->textarea($model,'ohjesivu',array('rows'=>20,'maxlength'=>50000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'ohjesivu'); ?>
	</div>
  </div>
</div><!-- form -->

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>


