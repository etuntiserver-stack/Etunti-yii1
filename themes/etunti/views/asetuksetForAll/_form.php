<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asetukset-for-all-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

<div class="row">
  <div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'api_access_key'); ?>
		<?php echo $form->textField($model,'api_access_key',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'api_access_key'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'googlemaps_apikey'); ?>
		<?php echo $form->textField($model,'googlemaps_apikey',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'googlemaps_apikey'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viralliset_pyhapaivat'); ?>
		<?php echo $form->textarea($model,'viralliset_pyhapaivat',array('rows'=>8,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viralliset_pyhapaivat'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'erikoislauantai'); ?>
		<?php echo $form->textarea($model,'erikoislauantai',array('rows'=>8,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'erikoislauantai'); ?>
	</div>

  </div>
</div><!-- form -->



<div class="row">
  <div class="col-sm-12">
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_info_sivu'); ?>
		<?php echo $form->textarea($model,'app_info_sivu',array('rows'=>10,'maxlength'=>50000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'app_info_sivu'); ?>
	</div>
  </div>
</div>

	<br>
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>


