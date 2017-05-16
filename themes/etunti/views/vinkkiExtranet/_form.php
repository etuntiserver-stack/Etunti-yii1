<?php
/* @var $this VinkkiExtranetController */
/* @var $model VinkkiExtranet */
/* @var $form CActiveForm */
?>

<div class="row">
 <div class="col-sm-4">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'vinkki-extranet-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->hiddenField($model,'asiakas_id',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'nimi'); ?>
		<?php echo $form->textField($model,'nimi',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'teksti'); ?>
		<?php echo $form->textArea($model,'teksti',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'teksti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tila'); ?>
		<?php
		$list = array(
			0=>Yii::t('main', 'Avoinna'),
			1=>Yii::t('main', 'Hoidettu'),
			2=>Yii::t('main', 'Asiakas')
		);
        	echo $form->dropDownList($model, 'tila', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'tila'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Lähetä' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
		<?php echo CHtml::link('Luo asiakas', array('//asiakkaat/create', 'vinkki_id'=>$model->id), array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>

 </div>
</div><!-- form -->
