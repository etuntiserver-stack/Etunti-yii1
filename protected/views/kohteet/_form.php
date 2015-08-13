<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kohteet-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tag_id'); ?>
		<?php echo $form->textField($model,'tag_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tag_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gps_sijainti'); ?>
		<?php echo $form->textField($model,'gps_sijainti',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'gps_sijainti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lyhenne'); ?>
		<?php echo $form->textField($model,'lyhenne',array('size'=>46,'maxlength'=>46)); ?>
		<?php echo $form->error($model,'lyhenne'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'katuosoite'); ?>
		<?php echo $form->textField($model,'katuosoite',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'katuosoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'toimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pnumero'); ?>
		<?php echo $form->textField($model,'pnumero',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'pnumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>72)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aikataulu'); ?>
		<?php echo $form->textArea($model,'aikataulu',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'aikataulu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hinnoittelu'); ?>
		<?php echo $form->textArea($model,'hinnoittelu',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'hinnoittelu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'muut'); ?>
		<?php echo $form->textArea($model,'muut',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'muut'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'toimenpiteet'); ?>
		<?php echo $form->textArea($model,'toimenpiteet',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'toimenpiteet'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoryhma'); ?>
		<?php echo $form->textField($model,'tyoryhma',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ryhma'); ?>
		<?php echo $form->textField($model,'ryhma',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'ryhma'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php echo $form->textField($model,'aktiivinen'); ?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'avain'); ?>
		<?php echo $form->textField($model,'avain',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'avain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kenella_on_avain'); ?>
		<?php echo $form->textField($model,'kenella_on_avain',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'kenella_on_avain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'puh_nro'); ?>
		<?php echo $form->textField($model,'puh_nro',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'puh_nro'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'siivous'); ?>
		<?php echo $form->textField($model,'siivous',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'siivous'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'etu_suku_nimet'); ?>
		<?php echo $form->textField($model,'etu_suku_nimet',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'etu_suku_nimet'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maksuehto_paiva'); ?>
		<?php echo $form->textField($model,'maksuehto_paiva'); ?>
		<?php echo $form->error($model,'maksuehto_paiva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->textField($model,'viivastyskorko',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lasku_tiedot'); ?>
		<?php echo $form->textField($model,'lasku_tiedot',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'lasku_tiedot'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->