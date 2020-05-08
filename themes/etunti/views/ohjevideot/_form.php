<?php
/* @var $this OhjevideotController */
/* @var $model Ohjevideot */
/* @var $form CActiveForm */
?>

<div class="form row">
 <div class="col-sm-4">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ohjevideot-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ohjevideo_ryhma'); ?>
	   <div class="input-group">
		<?php
	       	$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = "select_type='ohjevideo_ryhma'";
		$listData = Valikkoot::model()->findAll($criteria);
		if(count($listData) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "ohjevideo_ryhma";
			$new_val->value = "Testi ryhmä";
			if($new_val->save())
	      			$listData = Valikkoot::model()->findAll(" select_type='ohjevideo_ryhma' ",array('order' => "select_type"));
			else
				var_dump($new_val->getErrors());
		}
		?>
		<?php echo $form->dropDownList($model, 'ohjevideo_ryhma', CHtml::listData($listData, 'value', 'value'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="ohjevideo_ryhma"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>
		<?php echo $form->error($model,'ohjevideo_ryhma'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'otsiko'); ?>
		<?php echo $form->textField($model,'otsiko',array('size'=>60,'maxlength'=>500, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'otsiko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kuvaus'); ?>
		<?php echo $form->textArea($model,'kuvaus',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kuvaus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tiedoston_nimi'); ?>
		<?php echo $form->FileField($model,'tiedoston_nimi', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'tiedoston_nimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sort'); ?>
		<?php echo $form->numberField($model,'sort',array('size'=>60,'maxlength'=>3, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sort'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
 </div>
</div><!-- form -->


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select_valiko.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>
