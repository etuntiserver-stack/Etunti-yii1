<?php
/* @var $this PalautteetController */
/* @var $model Palautteet */
/* @var $form CActiveForm */
?>

<div class="row">
 <div class="col-sm-4">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'palautteet-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->hiddenField($model,'asiakas_id',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
	<?php echo $form->errorSummary($model); ?>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'otsikko'); ?>
		<?php echo $form->textField($model,'otsikko',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'otsikko'); ?>
	</div>


	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Lähetä' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>
 </div>
</div><!-- form -->
