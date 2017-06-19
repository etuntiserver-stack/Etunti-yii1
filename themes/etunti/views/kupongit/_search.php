<?php
/* @var $this KupongitController */
/* @var $model Kupongit */
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
		<?php echo $form->label($model,'kupongin_id'); ?>
		<?php echo $form->textField($model,'kupongin_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'voimassa'); ?>
		<?php echo $form->textField($model,'voimassa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'euro_maara'); ?>
		<?php echo $form->textField($model,'euro_maara'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'prosentti_maara'); ?>
		<?php echo $form->textField($model,'prosentti_maara'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'maara_tyyppi'); ?>
		<?php echo $form->textField($model,'maara_tyyppi',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jatkuva'); ?>
		<?php echo $form->textField($model,'jatkuva'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->