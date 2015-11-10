<?php
/* @var $this AsetuksetController */
/* @var $model Asetukset */
/* @var $form CActiveForm */


     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);
?>

<div class="row form">
  <div class="col-sm-6">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asetukset-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>


		<?php echo $form->hiddenField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'logon_polkku'); ?>
		<?php echo $form->textField($model,'logon_polkku',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'logon_polkku'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'logon_korkeus'); ?>
		<?php echo $form->textField($model,'logon_korkeus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'logon_korkeus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'johtaja'); ?>
		<?php echo $form->textField($model,'johtaja',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'johtaja'); ?>
	</div>

  </div>
</div><!-- form -->


<?php if(in_array('3',$tas)) : ?>
<hr>
  <div class="row form">
    <div class="col-sm-6">
    <legend><h2><?php echo Yii::t('main','Laskutuksen asetukset'); ?></h2></legend>
	<div class="row">
		<?php echo $form->labelEx($model,'tilinumero'); ?>
		<?php echo $form->textField($model,'tilinumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tilinumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iban'); ?>
		<?php echo $form->textField($model,'iban',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'iban'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bic'); ?>
		<?php echo $form->textField($model,'bic',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'bic'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->textField($model,'viivastyskorko',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

    </div><div class="col-sm-6">
    <legend><h2><?php echo Yii::t('main','POSTITA tunnukset'); ?></h2></legend>

	<div class="row">
		<?php echo $form->labelEx($model,'postita_username'); ?>
		<?php echo $form->textField($model,'postita_username',array('size'=>20,'maxlength'=>100,'class'=>'form-control input-sm')); ?>

		<?php echo $form->error($model,'postita_username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postita_password'); ?>
		<?php echo $form->textField($model,'postita_password',array('size'=>20,'maxlength'=>100,'class'=>'form-control input-sm')); ?>

		<?php echo $form->error($model,'postita_password'); ?>
	</div>

    </div>
  </div>
<?php endif; ?>


	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>


<?php
/*
	<div class="row">
		<?php echo $form->labelEx($model,'syntyrin_emails'); ?>
		<?php echo $form->textArea($model,'syntyrin_emails',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syntyrin_emails'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'paivan_uutinen'); ?>
		<?php echo $form->textField($model,'paivan_uutinen',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'paivan_uutinen'); ?>
	</div>
*/
?>
