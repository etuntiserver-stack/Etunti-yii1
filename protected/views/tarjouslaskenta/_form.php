<?php
/* @var $this TarjouslaskentaController */
/* @var $model Tarjouslaskenta */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tarjouslaskenta-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yhteystiedot_id'); ?>
		<?php echo $form->textField($model,'yhteystiedot_id'); ?>
		<?php echo $form->error($model,'yhteystiedot_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php echo $form->textField($model,'asiakas_id'); ?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tuote_palvelu_id'); ?>
		<?php echo $form->textField($model,'tuote_palvelu_id'); ?>
		<?php echo $form->error($model,'tuote_palvelu_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hinta_tyyppi'); ?>
		<?php echo $form->textField($model,'hinta_tyyppi',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'hinta_tyyppi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'neliot'); ?>
		<?php echo $form->textField($model,'neliot'); ?>
		<?php echo $form->error($model,'neliot'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kayntikerrat'); ?>
		<?php echo $form->textField($model,'kayntikerrat'); ?>
		<?php echo $form->error($model,'kayntikerrat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tuntien_maara'); ?>
		<?php echo $form->textField($model,'tuntien_maara'); ?>
		<?php echo $form->error($model,'tuntien_maara'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yhteensa'); ?>
		<?php echo $form->textField($model,'yhteensa'); ?>
		<?php echo $form->error($model,'yhteensa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tavoite_myyntikate'); ?>
		<?php echo $form->textField($model,'tavoite_myyntikate'); ?>
		<?php echo $form->error($model,'tavoite_myyntikate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'palkkakustannus'); ?>
		<?php echo $form->textField($model,'palkkakustannus'); ?>
		<?php echo $form->error($model,'palkkakustannus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'matkat'); ?>
		<?php echo $form->textField($model,'matkat'); ?>
		<?php echo $form->error($model,'matkat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iltalisa'); ?>
		<?php echo $form->textField($model,'iltalisa'); ?>
		<?php echo $form->error($model,'iltalisa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yolisa'); ?>
		<?php echo $form->textField($model,'yolisa'); ?>
		<?php echo $form->error($model,'yolisa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'muut_kulut'); ?>
		<?php echo $form->textArea($model,'muut_kulut',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'muut_kulut'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->