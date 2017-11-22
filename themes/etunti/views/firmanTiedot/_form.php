<?php
/* @var $this FirmanTiedotController */
/* @var $model FirmanTiedot */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'firman-tiedot-form',
	'enableAjaxValidation'=>false,
)); ?>

<div class="row">
 <div class="col-sm-3">
	<legend><?=Yii::t('main', 'Yrityksen tiedot')?></legend>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyonantaja'); ?>
		<?php echo $form->textField($model,'tyonantaja',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyonantaja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

<?php /*
laskutus_kanava, laskutus_osoite, laskutus_postinumero, laskutus_postitoimipaikka, sahkopostilaskuosoite, laskutus_ovt_tunnus, verkkolaskuosoite, operaattorin_valittajan_tunnus
*/ ?>

 </div><div class="col-sm-3">
	<legend><?=Yii::t('main', 'Laskutusosoite')?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus_osoite'); ?>
		<?php echo $form->textField($model,'laskutus_osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'laskutus_osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus_postinumero'); ?>
		<?php echo $form->textField($model,'laskutus_postinumero',array('class'=>'form-control','maxlength'=>5)); ?>
		<?php echo $form->error($model,'laskutus_postinumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus_postitoimipaikka'); ?>
		<?php echo $form->textField($model,'laskutus_postitoimipaikka',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'laskutus_postitoimipaikka'); ?>
	</div>

 </div><div class="col-sm-3">
	<legend><?php echo Yii::t('main', 'Laskutus tiedot'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus_kanava'); ?>
		<?php
		$list = array(	'posti'=>Yii::t('main','Posti'),
				'verkkolasku'=>Yii::t('main','Verkkolasku'),
				'sahkoposti'=>Yii::t('main','Sähköposti')
				);
        	echo $form->dropDownList($model, 'laskutus_kanava', $list,
		array('empty'=>'Valitse','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'laskutus_kanava'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sahkopostilaskuosoite'); ?>
		<?php echo $form->textField($model,'sahkopostilaskuosoite',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkopostilaskuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus_ovt_tunnus'); ?>
		<?php echo $form->textField($model,'laskutus_ovt_tunnus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'laskutus_ovt_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'verkkolaskuosoite'); ?>
		<?php echo $form->textField($model,'verkkolaskuosoite',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'verkkolaskuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'operaattorin_valittajan_tunnus'); ?>
		<?php echo $form->textField($model,'operaattorin_valittajan_tunnus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'operaattorin_valittajan_tunnus'); ?>
	</div>


 </div>
</div><!-- form -->

<br>

	<div class="section fill mb5">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

