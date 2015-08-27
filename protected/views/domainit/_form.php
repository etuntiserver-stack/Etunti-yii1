<?php
/* @var $this DomainitController */
/* @var $model Domainit */
/* @var $form CActiveForm */
?>

<div class="row form">
<div class="col-md-4">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'domainit-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'domain'); ?>
		<?php echo $form->textField($model,'domain',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'domain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'paketti'); ?>
		<?php 
		$a = Tasot::model()->findAll();
		$check = explode(",",$model->paketti);

		echo '<select name="tasot[]" class="selectpicker form-control" multiple title="Valitse">';
		  foreach($a as $val){
		    	$on = false;
		   foreach($check as $c)
		   {
		     if(trim($c) == trim($val->taso))
		     {
		    echo '<option value="'.$val->taso.'" selected>'.$val->nimetys.'</option>';
		    	$on = true;
		     }
		   }
		    if($on == false)
		    echo '<option value="'.$val->taso.'">'.$val->nimetys.'</option>';
		  }
		echo '</select>';
		?>
		<?php echo $form->error($model,'paketti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yritys'); ?>
		<?php echo $form->textField($model,'yritys',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'yritys'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>
</div><!-- form -->
