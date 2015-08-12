<?php
/* @var $this SivexkuittiController */
/* @var $model Sivexkuitti */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sivexkuitti-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'asiakas_num'); ?>
		<?php echo $form->textField($model,'asiakas_num',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'asiakas_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'requests'); ?>
		<?php echo $form->textField($model,'requests'); ?>
		<?php echo $form->error($model,'requests'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'puh_numero'); ?>
		<?php echo $form->textField($model,'puh_numero',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'puh_numero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'imei'); ?>
		<?php echo $form->textField($model,'imei',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'imei'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bluetooth_name'); ?>
		<?php echo $form->textField($model,'bluetooth_name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'bluetooth_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sim_serial_number'); ?>
		<?php echo $form->textField($model,'sim_serial_number',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'sim_serial_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'subscriber_id'); ?>
		<?php echo $form->textField($model,'subscriber_id',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'subscriber_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'my_location'); ?>
		<?php echo $form->textField($model,'my_location',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'my_location'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->textField($model,'kohde_kannasta',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID'); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textField($model,'viesti',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'etaisyys'); ?>
		<?php echo $form->textField($model,'etaisyys',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'etaisyys'); ?>
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
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin'); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hyvaksytty'); ?>
		<?php echo $form->textField($model,'hyvaksytty',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'hyvaksytty'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->