<?php
/* @var $this TasotController */
/* @var $model Tasot */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'taso'); ?>
		<?php echo $form->textField($model,'taso'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nimetys'); ?>
		<?php echo $form->textField($model,'nimetys',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kuvaus'); ?>
		<?php echo $form->textArea($model,'kuvaus',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->