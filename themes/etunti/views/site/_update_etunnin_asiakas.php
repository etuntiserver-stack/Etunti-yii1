<?php
/* @var $this DomainitController */
/* @var $model Domainit */
/* @var $form CActiveForm */
$model->time = date("d.m.Y", strtotime($model->time));
?>

<div class="row">
<div class="col-md-4">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'domainit-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'domain'); ?>
		<?php echo $form->textField($model,'domain',array('size'=>60,'maxlength'=>100,'class'=>'form-control', 'readonly' => 'yes')); ?>
		<?php echo $form->error($model,'domain'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time',array('size'=>60,'maxlength'=>100,'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="section fill mb5">
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

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yritys'); ?>
		<?php echo $form->textField($model,'yritys',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'yritys'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palveluhinta_persiivoja'); ?>
		<?php echo $form->numberField($model,'palveluhinta_persiivoja',array('class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'palveluhinta_persiivoja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyovuorohinta_persiivoja'); ?>
		<?php echo $form->numberField($model,'tyovuorohinta_persiivoja',array('class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'tyovuorohinta_persiivoja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'muut_tyokaluhinta'); ?>
		<?php echo $form->numberField($model,'muut_tyokaluhinta',array('class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'muut_tyokaluhinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'huoltokatko'); ?>
		<?php
		$list = array(0=>'Ei',1=>'Kyllä');
        	echo $form->dropDownList($model, 'huoltokatko', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'huoltokatko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(0=>'Ei',1=>'Kyllä');
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'maksullinen'); ?>
		<?php
		$list = array(0=>'Ei',1=>'Kyllä');
        	echo $form->dropDownList($model, 'maksullinen', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'maksullinen'); ?>
	</div>

</div>
</div><!-- form -->

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

