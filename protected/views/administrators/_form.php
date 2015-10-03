<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */
/* @var $form CActiveForm */
?>

<div class="row form">
  <div class="col-sm-3">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'administrators-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'adm_login'); ?>
		<?php echo $form->textField($model,'adm_login',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_login'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'adm_salasana'); ?>
		<?php echo $form->textField($model,'adm_salasana',array('value'=>'','size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_salasana'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'adm_email'); ?>
		<?php echo $form->textField($model,'adm_email',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'adm_nimi'); ?>
		<?php echo $form->textField($model,'adm_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'adm_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		$a = Valikkoot::model()->findAll(" select_type='admin status' ");
        	$tal = '';
		foreach($a as $v){
		$exV = explode("/",$v->value);
		   $tal[$exV[1]] = $exV[0];
		}

		echo $form->dropDownList($model,'status', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
  </div>
</div><!-- form -->
