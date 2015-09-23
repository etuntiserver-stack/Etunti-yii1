<?php
/* @var $this LaskutusController */
/* @var $model Laskutus */
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
		<?php echo $form->label($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'trtd'); ?>
		<?php echo $form->textArea($model,'trtd',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'trtd7'); ?>
		<?php echo $form->textArea($model,'trtd7',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mista'); ?>
		<?php echo $form->textField($model,'mista',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mihin'); ?>
		<?php echo $form->textField($model,'mihin',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'yhteensa'); ?>
		<?php echo $form->textField($model,'yhteensa',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'muoto'); ?>
		<?php echo $form->textField($model,'muoto',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'asiakkaan_koodi'); ?>
		<?php echo $form->textField($model,'asiakkaan_koodi',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->