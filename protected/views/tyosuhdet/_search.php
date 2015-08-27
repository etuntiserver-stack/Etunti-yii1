<?php
/* @var $this TyosuhdetController */
/* @var $model Tyosuhdet */
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
		<?php echo $form->label($model,'alku'); ?>
		<?php echo $form->textField($model,'alku',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'loppu'); ?>
		<?php echo $form->textField($model,'loppu',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'vktyoaika'); ?>
		<?php echo $form->textField($model,'vktyoaika',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>40,'maxlength'=>40)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'palkkausmuoto'); ?>
		<?php echo $form->textField($model,'palkkausmuoto',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tuntihinta'); ?>
		<?php echo $form->textField($model,'tuntihinta',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'matka_thinta'); ?>
		<?php echo $form->textField($model,'matka_thinta',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lippu_kuumaks'); ?>
		<?php echo $form->textField($model,'lippu_kuumaks',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'koe_loppu'); ?>
		<?php echo $form->textField($model,'koe_loppu',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'koe_hinta'); ?>
		<?php echo $form->textField($model,'koe_hinta',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tuloraja_ajalle'); ?>
		<?php echo $form->textField($model,'tuloraja_ajalle',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'perusprosentti'); ?>
		<?php echo $form->textField($model,'perusprosentti',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lisaprosentti'); ?>
		<?php echo $form->textField($model,'lisaprosentti',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kuukaudessa'); ?>
		<?php echo $form->textField($model,'kuukaudessa',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kahdessa_viikossa'); ?>
		<?php echo $form->textField($model,'kahdessa_viikossa',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'viikossa'); ?>
		<?php echo $form->textField($model,'viikossa',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'paivassa'); ?>
		<?php echo $form->textField($model,'paivassa',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'atk_varten'); ?>
		<?php echo $form->textField($model,'atk_varten',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'yksi_tuloraja'); ?>
		<?php echo $form->textField($model,'yksi_tuloraja',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->