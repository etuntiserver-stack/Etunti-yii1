<?php
/* @var $this KirjallinenVaroitusController */
/* @var $model KirjallinenVaroitus */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'irtisanomisilmoitukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->hiddenField($model,'tid',array('size'=>60,'maxlength'=>70, 'class'=>'form-control')); ?>


<div class="row">
  <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyonantaja'); ?>
		<?php echo $form->textField($model,'tyonantaja',array('size'=>60,'maxlength'=>70, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyonantaja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>7,'maxlength'=>7, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'TyonantajanEdustaja'); ?>
		<?php echo $form->textField($model,'TyonantajanEdustaja',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'TyonantajanEdustaja'); ?>
	</div>


  </div><div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_email'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>70, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_katuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_pnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_ptoimipaikka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_puh'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_henkilotunnus'); ?>
	</div>

  </div><div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'teksti'); ?>
		<?php echo $form->textArea($model,'teksti',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'teksti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Paivays'); ?>
		<?php echo $form->textField($model,'Paivays',array('size'=>50,'maxlength'=>50, 'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'Paivays'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Paikka'); ?>
		<?php echo $form->textField($model,'Paikka',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Paikka'); ?>
	</div>


  </div>
</div><!-- form -->

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>


