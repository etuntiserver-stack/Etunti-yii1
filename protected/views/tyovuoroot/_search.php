<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
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
		<?php echo $form->label($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kohde'); ?>
		<?php echo $form->textField($model,'kohde',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alku'); ?>
		<?php echo $form->textField($model,'alku',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'loppu'); ?>
		<?php echo $form->textField($model,'loppu',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pituus'); ?>
		<?php echo $form->textField($model,'pituus',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ruokatauko'); ?>
		<?php echo $form->textField($model,'ruokatauko',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alku_r'); ?>
		<?php echo $form->textField($model,'alku_r',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kesto'); ?>
		<?php echo $form->textField($model,'kesto',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyoajanlaatu'); ?>
		<?php echo $form->textField($model,'tyoajanlaatu',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyoajanmerkinta'); ?>
		<?php echo $form->textField($model,'tyoajanmerkinta',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osoiteOnline'); ?>
		<?php echo $form->textField($model,'osoiteOnline',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->