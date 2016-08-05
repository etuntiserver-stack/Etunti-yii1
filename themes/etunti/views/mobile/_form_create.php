<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

if(isset($model->id))
$model->hinta = str_replace(",",".",$model->hinta);
?>
<div class="row">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mobile-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->dropDownList($model, 'tid', CHtml::listData(Tyontekijat::model()->findAll(), 'id', 'tekijan_nimi'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->dropDownList($model, 'kohdenID', CHtml::listData(Kohteet::model()->findAll(), 'id', 'osoite'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('options' => array(3=>array('selected'=>true)),'class'=>'form-control input-sm'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('rows'=>6, 'cols'=>50,'class'=>'form-control datetimepicker')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('rows'=>6, 'cols'=>50,'class'=>'form-control datetimepicker')); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>


		<?php echo $form->hiddenField($model,'kohde_kannasta',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->hiddenField($model,'tekijan_nimi',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>

  </div>
</div><!-- form -->

<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors luoTallennaKohde')); ?>
	</div>

<?php $this->endWidget(); ?>



<script type="text/javascript">
$(document).ready(function(){


$('#Mobile_tid').change(function(){

   var thisVal = $('#Mobile_tid option:selected').text();
   $('#Mobile_tekijan_nimi').val(thisVal);

});

$('#Mobile_kohdenID').change(function(){

   var thisVal = $('#Mobile_kohdenID option:selected').text();
   $('#Mobile_kohde_kannasta').val(thisVal);

});

});
</script>


