<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */
/* @var $form CActiveForm */
?>

<div class="row">
  <div class="col-sm-4">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="section">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'time'); ?>
		<?php echo $form->textField($model,'time',array('class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'yrityksen_nimi'); ?>
		<?php echo $form->textField($model,'yrityksen_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>60,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'ryhma'); ?>
		<?php echo $form->textField($model,'ryhma',array('class'=>'form-control input-sm')); ?>
	</div>

	<div class="section">
		<?php echo $form->label($model,'aktiivinen'); ?>
		<?php echo $form->textField($model,'aktiivinen',array('class'=>'form-control input-sm')); ?>
	</div>
<br>
	<div class="section">
		<?php echo CHtml::submitButton('Hae',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
  </div>
</div><!-- search-form -->
