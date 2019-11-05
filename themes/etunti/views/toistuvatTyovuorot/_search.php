<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $model ToistuvatTyovuorot */
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
		<?php echo $form->label($model,'pfrom'); ?>
		<?php echo $form->textField($model,'pfrom',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pto'); ?>
		<?php echo $form->textField($model,'pto',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'viikkoja'); ?>
		<?php echo $form->textField($model,'viikkoja'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'viikko_paivat'); ?>
		<?php echo $form->textArea($model,'viikko_paivat',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kohde'); ?>
		<?php echo $form->textField($model,'kohde'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>50,'maxlength'=>50)); ?>
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
		<?php echo $form->label($model,'kesto'); ?>
		<?php echo $form->textField($model,'kesto',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyoajanmerkinta'); ?>
		<?php echo $form->textField($model,'tyoajanmerkinta',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyopaari'); ?>
		<?php echo $form->textArea($model,'tyopaari',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->