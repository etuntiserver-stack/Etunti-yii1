<?php
/* @var $this LaskutusTuotteetController */
/* @var $model LaskutusTuotteet */
/* @var $form CActiveForm */
?>

<div class="section fill mb5">
  <div class="col-sm-3">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'laskutus-tuotteet-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuotenimi'); ?>
		<?php echo $form->textField($model,'tuotenimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tuotenimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_alv_0'); ?>
		<?php echo $form->textField($model,'hinta_alv_0',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'hinta_alv_0'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv'); ?>
		<?php echo $form->textField($model,'alv',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yksikko'); ?>
		<?php echo $form->textField($model,'yksikko',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yksikko'); ?>
	</div>
<br>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
  </div>
</div><!-- form -->

<?php
/*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_alv_sis'); ?>
		<?php echo $form->textField($model,'hinta_alv_sis',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'hinta_alv_sis'); ?>
	</div>
*/
?>
