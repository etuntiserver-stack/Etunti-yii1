<?php
/* @var $this TyosuhteenPaattaminenController */
/* @var $model TyosuhteenPaattaminen */
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
		<?php echo $form->label($model,'key'); ?>
		<?php echo $form->textField($model,'key'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'titteli'); ?>
		<?php echo $form->textField($model,'titteli',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kuuleminen'); ?>
		<?php echo $form->textArea($model,'kuuleminen',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyosuhteen_paattaminen'); ?>
		<?php echo $form->textArea($model,'tyosuhteen_paattaminen',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyonantaja'); ?>
		<?php echo $form->textField($model,'tyonantaja',array('size'=>60,'maxlength'=>70)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>70)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'teksti'); ?>
		<?php echo $form->textArea($model,'teksti',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Paivays'); ?>
		<?php echo $form->textField($model,'Paivays',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Paikka'); ?>
		<?php echo $form->textField($model,'Paikka',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TyonantajanEdustaja'); ?>
		<?php echo $form->textField($model,'TyonantajanEdustaja',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'NimikeTehtava'); ?>
		<?php echo $form->textField($model,'NimikeTehtava',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tiedosto'); ?>
		<?php echo $form->textField($model,'tiedosto',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alku_pvm'); ?>
		<?php echo $form->textField($model,'alku_pvm',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'loppu_pvm'); ?>
		<?php echo $form->textField($model,'loppu_pvm',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->