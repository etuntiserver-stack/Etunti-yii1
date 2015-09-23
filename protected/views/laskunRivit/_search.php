<?php
/* @var $this LaskunRivitController */
/* @var $model LaskunRivit */
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
		<?php echo $form->label($model,'lid'); ?>
		<?php echo $form->textField($model,'lid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rivi'); ?>
		<?php echo $form->textField($model,'rivi'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tkoodi'); ?>
		<?php echo $form->textField($model,'tkoodi'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kpl'); ?>
		<?php echo $form->textField($model,'kpl'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'yksikko'); ?>
		<?php echo $form->textField($model,'yksikko',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hinta'); ?>
		<?php echo $form->textField($model,'hinta',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alv'); ?>
		<?php echo $form->textField($model,'alv',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hinta_alv'); ?>
		<?php echo $form->textField($model,'hinta_alv',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ale'); ?>
		<?php echo $form->textField($model,'ale',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'veroton'); ?>
		<?php echo $form->textField($model,'veroton',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'yhteensa_alv'); ?>
		<?php echo $form->textField($model,'yhteensa_alv',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->