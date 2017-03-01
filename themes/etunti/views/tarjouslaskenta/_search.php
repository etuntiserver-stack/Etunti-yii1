<?php
/* @var $this TarjouslaskentaController */
/* @var $model Tarjouslaskenta */
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
		<?php echo $form->label($model,'yhteystiedot_id'); ?>
		<?php echo $form->textField($model,'yhteystiedot_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'asiakas_id'); ?>
		<?php echo $form->textField($model,'asiakas_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tuote_palvelu_id'); ?>
		<?php echo $form->textField($model,'tuote_palvelu_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hinta_tyyppi'); ?>
		<?php echo $form->textField($model,'hinta_tyyppi',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'neliot'); ?>
		<?php echo $form->textField($model,'neliot'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kayntikerrat'); ?>
		<?php echo $form->textField($model,'kayntikerrat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tuntien_maara'); ?>
		<?php echo $form->textField($model,'tuntien_maara'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'yhteensa'); ?>
		<?php echo $form->textField($model,'yhteensa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tavoite_myyntikate'); ?>
		<?php echo $form->textField($model,'tavoite_myyntikate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'palkkakustannus'); ?>
		<?php echo $form->textField($model,'palkkakustannus'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'matkat'); ?>
		<?php echo $form->textField($model,'matkat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iltalisa'); ?>
		<?php echo $form->textField($model,'iltalisa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'yolisa'); ?>
		<?php echo $form->textField($model,'yolisa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'muut_kulut'); ?>
		<?php echo $form->textArea($model,'muut_kulut',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->