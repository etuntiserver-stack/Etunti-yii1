<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */
/* @var $form CActiveForm */

  $admin = '';
$ad = Administrators::model()->findbypk(Yii::app()->user->adminID);
if(isset($ad->adm_nimi))
  $admin = $ad->adm_nimi;
?>

<div class="row form">
  <div class="col-sm-4">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'viestinta-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->hiddenField($model,'pvm',array('value'=>date("Y-m-d H:i:s"))); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin',array('value'=>$admin,'class'=>'form-control','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekija'); ?>
		<?php
        	$list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi');
        	echo $form->dropDownList($model, 'tekija', $list,array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'tekija'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textArea($model,'viesti',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Tallenna' : 'Luo',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
  </div>
</div><!-- form -->
