<?php
/* @var $this AvaimetController */
/* @var $model Avaimet */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'avaimet-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
<div class="row form">
 <div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'avainnumero'); ?>
		<?php echo $form->textField($model,'avainnumero',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'avainnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " aktiivinen=1 ";
	       	$criteria->order = " osoite ";
		$kohteet = Kohteet::model()->findAll($criteria);
        	echo $form->dropDownList($model, 'kohde', CHtml::listData($kohteet, 'id', 'osoite'),
		array('empty' => 'Valitse', 'class'=>'form-control'
		));
		?>
		<?php echo $form->error($model,'kohde'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " aktiivinen=1 ";
	       	$criteria->order = " tekijan_nimi ";
		$tt = Tyontekijat::model()->findAll($criteria);
        	echo $form->dropDownList($model, 'tid', CHtml::listData($tt, 'id', 'tekijan_nimi'),
		array('empty' => 'Valitse', 'class'=>'form-control'
		));
		?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sijainti'); ?>
		<?php echo $form->textField($model,'sijainti',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'sijainti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lisatiedot'); ?>
		<?php echo $form->textArea($model,'lisatiedot',array('rows'=>6, 'cols'=>50, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'lisatiedot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php
        	$l = array(0=>0, 1=>1, 2=>2, 3=>3);

        	echo $form->dropDownList($model, 'status', $l,
		array('class'=>'form-control'
		));
        	?>
		<?php echo $form->error($model,'status'); ?>
	</div>
 </div>
</div><!-- form -->

<br>

<div class="row form">
  <div class="col-sm-3">
	<div class="section fill mb5">
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-sm btn-primary myBgColors')); ?>
	</div>
	</div>
  </div>
</div>

<?php $this->endWidget(); ?>


