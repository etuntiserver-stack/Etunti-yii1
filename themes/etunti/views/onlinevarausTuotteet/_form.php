<?php
/* @var $this OnlinevarausTuotteetController */
/* @var $model OnlinevarausTuotteet */
/* @var $form CActiveForm */
?>

<div class="row">
 <div class="col-sm-4">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'onlinevaraus-tuotteet-form',
	'enableAjaxValidation'=>true,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'nimike'); ?>
		<?php echo $form->textField($model,'nimike',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nimike'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palvelu'); ?>
		<?php
		$list = array(0=>'Pääpalvelu',1=>'Lisäpalvelu');
        	echo $form->dropDownList($model, 'palvelu', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'palvelu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'selitysteksti'); ?>
		<?php echo $form->textArea($model,'selitysteksti',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'selitysteksti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->numberField($model,'hinta',array('size'=>20,'maxlength'=>20, 'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kotitalousvahennys'); ?>
		<?php echo $form->numberField($model,'kotitalousvahennys',array('size'=>20,'maxlength'=>20, 'class'=>'form-control', 'placeholder'=>'Esimerkiksi 45')); ?>
		<?php echo $form->error($model,'kotitalousvahennys'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'nelio'); ?>
		<?php
		$list = array(
			'0-50'=>'0-50',
			'50-80'=>'50-80',
			'80-120'=>'80-120',
			'120-160'=>'120-160',
			'160-200'=>'160-200',
			'200-250'=>'200-250',
		);
        	echo $form->dropDownList($model, 'nelio', $list,
		array('empty'=>'Valitse neliö', 'class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'nelio'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kesto'); ?>
		<?php echo $form->numberField($model,'kesto',array('size'=>20,'maxlength'=>20, 'class'=>'form-control', 'placeholder'=>'Esimerkiksi.. 0.5', 'step'=>'0.5')); ?>
		<?php echo $form->error($model,'kesto'); ?>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>
 </div>
</div><!-- form -->
