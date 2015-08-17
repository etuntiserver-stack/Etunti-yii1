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



	<?php echo $form->errorSummary($model); ?>

<div class="row">
  <div class="col-sm-4">
	<div class="row">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->textField($model,'kohde_kannasta',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textArea($model,'viesti',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'etaisyys'); ?>
		<?php echo $form->textField($model,'etaisyys',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'etaisyys'); ?>
	</div>
  </div>
  <div class="col-sm-8">
	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>20, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>
  </div>
</div>


<!--
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('class'=>'form-control')); ?>

		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hyvaksytty'); ?>
		<?php echo $form->textField($model,'hyvaksytty',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'hyvaksytty'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'asiakas_num'); ?>
		<?php echo $form->textField($model,'asiakas_num',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakas_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'imei'); ?>
		<?php echo $form->textField($model,'imei',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'imei'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bluetooth_name'); ?>
		<?php echo $form->textField($model,'bluetooth_name',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'bluetooth_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'my_location'); ?>
		<?php echo $form->textField($model,'my_location',array('size'=>60,'maxlength'=>1000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'my_location'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>
-->

<div class="row">
  <div class="col-sm-4">
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>
  </div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
