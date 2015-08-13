<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
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
		<?php echo $form->label($model,'tag_id'); ?>
		<?php echo $form->textField($model,'tag_id',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gps_sijainti'); ?>
		<?php echo $form->textField($model,'gps_sijainti',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lyhenne'); ?>
		<?php echo $form->textField($model,'lyhenne',array('size'=>46,'maxlength'=>46)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'katuosoite'); ?>
		<?php echo $form->textField($model,'katuosoite',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pnumero'); ?>
		<?php echo $form->textField($model,'pnumero',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>72)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aikataulu'); ?>
		<?php echo $form->textArea($model,'aikataulu',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hinnoittelu'); ?>
		<?php echo $form->textArea($model,'hinnoittelu',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'muut'); ?>
		<?php echo $form->textArea($model,'muut',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'toimenpiteet'); ?>
		<?php echo $form->textArea($model,'toimenpiteet',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyoryhma'); ?>
		<?php echo $form->textField($model,'tyoryhma',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ryhma'); ?>
		<?php echo $form->textField($model,'ryhma',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'aktiivinen'); ?>
		<?php echo $form->textField($model,'aktiivinen'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'avain'); ?>
		<?php echo $form->textField($model,'avain',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kenella_on_avain'); ?>
		<?php echo $form->textField($model,'kenella_on_avain',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'puh_nro'); ?>
		<?php echo $form->textField($model,'puh_nro',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'siivous'); ?>
		<?php echo $form->textField($model,'siivous',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'etu_suku_nimet'); ?>
		<?php echo $form->textField($model,'etu_suku_nimet',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'maksuehto_paiva'); ?>
		<?php echo $form->textField($model,'maksuehto_paiva'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'viivastyskorko'); ?>
		<?php echo $form->textField($model,'viivastyskorko',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lasku_tiedot'); ?>
		<?php echo $form->textField($model,'lasku_tiedot',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->