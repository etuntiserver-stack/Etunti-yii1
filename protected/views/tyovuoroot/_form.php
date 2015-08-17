<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */

	$model->pvm = date("Y-m-d",strtotime($model->pvm));
?>

<div class="row form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
	'enableAjaxValidation'=>false,

)); ?>


	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'id',array('id'=>$model->id)); ?>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->dateField($model,'pvm',array('size'=>20,'maxlength'=>20,'class'=>'form-control'));//,'readonly'=>'yes' ?>
		<?php echo $form->error($model,'pvm'); ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'alku'); ?>
		<input type="time" name="Tyovuoroot[alku]" class="form-control" value="<?php echo $model->alku; ?>">
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<input type="time" name="Tyovuoroot[loppu]" class="form-control" value="<?php echo $model->loppu; ?>">
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pituus'); ?>
		<?php echo $form->textField($model,'pituus',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pituus'); ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php
        	$list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'id');
        	echo $form->dropDownList($model, 'id', $list,array('class'=>'form-control'));
        	?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php
        	$list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'id');
        	echo $form->dropDownList($model, 'id', $list,array('class'=>'form-control'));
        	?>
  </div>
  <div class="col-sm-3">

  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
        	$list = CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite')), 'id', 'osoite');
        	echo $form->dropDownList($model, 'kohde', $list,array('class'=>'form-control'));
        	?>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textarea($model,'tietoja',array('rows'=>6,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
  </div>
</div>

</div>
	<div class="modal-footer">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary submitThis')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->



<?php $this->endWidget(); ?>



<!--

	<div class="row">
		<?php echo $form->labelEx($model,'ruokatauko'); ?>
		<?php echo $form->textField($model,'ruokatauko',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'ruokatauko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'alku_r'); ?>
		<?php echo $form->textField($model,'alku_r',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'alku_r'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kesto'); ?>
		<?php echo $form->textField($model,'kesto',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kesto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoiteOnline'); ?>
		<?php echo $form->textField($model,'osoiteOnline',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'osoiteOnline'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php echo $form->textField($model,'kohde',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'kohde'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

-->
