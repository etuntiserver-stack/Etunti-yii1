<?php
/* @var $this LaskuHistoriaController */
/* @var $model LaskuHistoria */
/* @var $form CActiveForm */
?>

<div class="row form">
<div class="col-sm-4">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lasku-historia-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'lid'); ?>
		<?php echo $form->textField($model,'lid', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'lid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textarea($model,'status',array('rows'=>6,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'palvelu'); ?>
		<?php echo $form->textField($model,'palvelu', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'palvelu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yht_euro'); ?>
		<?php echo $form->textField($model,'yht_euro',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yht_euro'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary btn-sm')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->
