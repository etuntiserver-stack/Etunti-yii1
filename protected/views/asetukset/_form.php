<?php
/* @var $this AsetuksetController */
/* @var $model Asetukset */
/* @var $form CActiveForm */
?>

<div class="row form">
  <div class="col-sm-3">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asetukset-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>


		<?php echo $form->hiddenField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'logon_polkku'); ?>
		<?php echo $form->textField($model,'logon_polkku',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'logon_polkku'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'logon_korkeus'); ?>
		<?php echo $form->textField($model,'logon_korkeus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'logon_korkeus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'johtaja'); ?>
		<?php echo $form->textField($model,'johtaja',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'johtaja'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
  </div>
</div><!-- form -->

<?php
/*
	<div class="row">
		<?php echo $form->labelEx($model,'syntyrin_emails'); ?>
		<?php echo $form->textArea($model,'syntyrin_emails',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syntyrin_emails'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'paivan_uutinen'); ?>
		<?php echo $form->textField($model,'paivan_uutinen',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'paivan_uutinen'); ?>
	</div>
*/
?>
