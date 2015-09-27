<?php
/* @var $this AsetuksetController */
/* @var $model Asetukset */
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
		<?php echo $form->label($model,'syntyrin_emails'); ?>
		<?php echo $form->textArea($model,'syntyrin_emails',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'paivan_uutinen'); ?>
		<?php echo $form->textField($model,'paivan_uutinen',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'logon_polkku'); ?>
		<?php echo $form->textField($model,'logon_polkku',array('size'=>60,'maxlength'=>500)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'logon_korkeus'); ?>
		<?php echo $form->textField($model,'logon_korkeus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'johtaja'); ?>
		<?php echo $form->textField($model,'johtaja',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->