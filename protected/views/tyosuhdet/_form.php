<?php
/* @var $this TyosuhdetController */
/* @var $model Tyosuhdet */
/* @var $form CActiveForm */

echo $model->alku;
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyosuhdet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->hiddenField($model,'tid'); ?>

<div class="row form">
  <div class="col-sm-3">
  <legend>
    <h2><?php echo Yii::t('main', 'TYÖSUHTEET'); ?></h2>
  </legend>
	<div class="row">
		<?php echo $form->labelEx($model,'alku'); ?>
		<?php echo $form->textField($model,'alku',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'alku'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<?php echo $form->textField($model,'loppu',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'loppu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vktyoaika'); ?>
		<?php echo $form->textField($model,'vktyoaika',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'vktyoaika'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>40,'maxlength'=>40,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nimike'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'palkkausmuoto'); ?>
		<?php echo $form->textField($model,'palkkausmuoto',array('size'=>30,'maxlength'=>30,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'palkkausmuoto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tuntihinta'); ?>
		<?php echo $form->textField($model,'tuntihinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tuntihinta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'matka_thinta'); ?>
		<?php echo $form->textField($model,'matka_thinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'matka_thinta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lippu_kuumaks'); ?>
		<?php echo $form->textField($model,'lippu_kuumaks',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'lippu_kuumaks'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'koe_loppu'); ?>
		<?php echo $form->textField($model,'koe_loppu',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'koe_loppu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'koe_hinta'); ?>
		<?php echo $form->textField($model,'koe_hinta',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'koe_hinta'); ?>
	</div>

  </div><div class="col-sm-3">
  <legend>
    <h2><?php echo Yii::t('main', 'VEROPROSENTTI'); ?></h2>
  </legend>

	<div class="row">
		<?php echo $form->labelEx($model,'tuloraja_ajalle'); ?>
		<?php echo $form->textField($model,'tuloraja_ajalle',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tuloraja_ajalle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'perusprosentti'); ?>
		<?php echo $form->textField($model,'perusprosentti',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'perusprosentti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lisaprosentti'); ?>
		<?php echo $form->textField($model,'lisaprosentti',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'lisaprosentti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kuukaudessa'); ?>
		<?php echo $form->textField($model,'kuukaudessa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kuukaudessa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kahdessa_viikossa'); ?>
		<?php echo $form->textField($model,'kahdessa_viikossa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kahdessa_viikossa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viikossa'); ?>
		<?php echo $form->textField($model,'viikossa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viikossa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'paivassa'); ?>
		<?php echo $form->textField($model,'paivassa',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'paivassa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'atk_varten'); ?>
		<?php echo $form->textField($model,'atk_varten',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'atk_varten'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yksi_tuloraja'); ?>
		<?php echo $form->textField($model,'yksi_tuloraja',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yksi_tuloraja'); ?>
	</div>

  </div>
</div><!-- form -->

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>


