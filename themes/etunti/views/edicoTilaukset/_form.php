<?php
/* @var $this EdicoTulauksetController */
/* @var $model EdicoTulaukset */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'edico-tulaukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="row form">
 <div class="col-sm-4">

	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('class' => 'form-control', 'readonly' => 'yes', 'size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero', array('class' => 'form-control', 'readonly' => 'yes')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('class' => 'form-control', 'readonly' => 'yes', 'size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
	</div>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'asiakas_puhelinnumero'); ?>
		<?php echo $form->textField($model,'asiakas_puhelinnumero',array('class' => 'form-control', 'readonly' => 'yes', 'size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'asiakas_puhelinnumero'); ?>
	</div>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'toivottu_pvm'); ?>
		<?php echo $form->textField($model,'toivottu_pvm',array('class' => 'form-control', 'readonly' => 'yes', 'size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'toivottu_pvm'); ?>
	</div>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'toivottu_aloitus'); ?>
		<?php echo $form->textField($model,'toivottu_aloitus',array('class' => 'form-control', 'readonly' => 'yes', 'size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'toivottu_aloitus'); ?>
	</div>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'toivottu_lopetus'); ?>
		<?php echo $form->textField($model,'toivottu_lopetus',array('class' => 'form-control', 'readonly' => 'yes', 'size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'toivottu_lopetus'); ?>
	</div>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textArea($model,'viesti',array('class' => 'form-control', 'readonly' => 'yes', 'rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

	<div class="section fill mb">
		<?php echo $form->labelEx($model,'tuotteet'); ?>
		<?php echo $form->textArea($model,'tuotteet',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tuotteet'); ?>
	</div>

	<div class="section fill mb">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
 </div>
</div><!-- form -->

<?php $this->endWidget(); ?>


