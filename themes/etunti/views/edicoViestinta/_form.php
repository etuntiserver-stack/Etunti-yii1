<?php
/* @var $this EdicoViestintaController */
/* @var $model EdicoViestinta */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'edico-viestinta-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>


	<?php echo $form->errorSummary($model); ?>

<div class="row">
  <div class="col-sm-4">
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
		<?php echo $form->dropDownList($model, 'asiakas_id', CHtml::listData(Asiakkaat::model()->findAll(), 'id', 'fullname'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakas_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'otsikko'); ?>
		<?php echo $form->textField($model,'otsikko', array('size'=>60, 'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'otsikko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'teksti'); ?>
		<?php echo $form->textarea($model,'teksti', array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'teksti'); ?>
	</div>
 </div>
</div>
	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Lähetä') : Yii::t('main', 'Tallenna'),array('class'=>'btn btn-primary myBgColors luoTallennaAsiakas')); ?>
	</div>

<?php $this->endWidget(); ?>


