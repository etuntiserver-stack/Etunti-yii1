<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyontekijat-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'imei'); ?>
		<?php echo $form->textField($model,'imei',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'imei'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'laiten_puh'); ?>
		<?php echo $form->textField($model,'laiten_puh',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'laiten_puh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tekijan_henkilotunnus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tekijan_puh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_lanka_puh'); ?>
		<?php echo $form->textField($model,'tekijan_lanka_puh',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tekijan_lanka_puh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tekijan_katuosoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'tekijan_pnumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_ptoimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoryhma'); ?>
		<?php echo $form->textField($model,'tyoryhma',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoehtosopimus'); ?>
		<?php echo $form->textField($model,'tyoehtosopimus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tyoehtosopimus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_kulunvalvonta'); ?>
		<?php echo $form->textField($model,'tekijan_kulunvalvonta',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_kulunvalvonta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_pankkitili'); ?>
		<?php echo $form->textField($model,'tekijan_pankkitili',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tekijan_pankkitili'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_konttori'); ?>
		<?php echo $form->textField($model,'tekijan_konttori',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_konttori'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php echo $form->textField($model,'aktiivinen',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_tietoja'); ?>
		<?php echo $form->textArea($model,'tekijan_tietoja',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tekijan_tietoja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_muisti'); ?>
		<?php echo $form->textArea($model,'tekijan_muisti',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tekijan_muisti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'salasana'); ?>
		<?php echo $form->textField($model,'salasana',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'salasana'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'online_varauksen_valmina'); ?>
		<?php echo $form->textField($model,'online_varauksen_valmina'); ?>
		<?php echo $form->error($model,'online_varauksen_valmina'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kortit'); ?>
		<?php echo $form->textArea($model,'kortit',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'kortit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ayjasenyys'); ?>
		<?php echo $form->textField($model,'ayjasenyys',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'ayjasenyys'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->