<?php
/* @var $this FirmanTiedotController */
/* @var $model FirmanTiedot */
/* @var $form CActiveForm */
?>

<div class="row">
<div class="col-sm-3">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'firman-tiedot-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="section">
		<?php echo $form->labelEx($model,'tyonantaja'); ?>
		<?php echo $form->textField($model,'tyonantaja',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tyonantaja'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>
<!--
	<div class="section">
		<?php echo $form->labelEx($model,'tilinumero'); ?>
		<?php echo $form->textField($model,'tilinumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tilinumero'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'iban'); ?>
		<?php echo $form->textField($model,'iban',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'iban'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'bic'); ?>
		<?php echo $form->textField($model,'bic',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'bic'); ?>
	</div>
-->
	<div class="section">
		<?php echo $form->labelEx($model,'johtaja'); ?>
		<?php echo $form->textField($model,'johtaja',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'johtaja'); ?>
	</div>

<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
 </div>
</div><!-- form -->
