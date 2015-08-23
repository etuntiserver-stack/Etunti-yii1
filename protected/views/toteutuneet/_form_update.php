<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */
/* @var $form CActiveForm */

  $at[$model->id] = date("H:i",strtotime($model->aloitan));
  $apvm[$model->id] = date("Y-m-d",strtotime($model->aloitan));

  $lt[$model->id] = date("H:i",strtotime($model->loppui));
  $lpvm[$model->id] = date("Y-m-d",strtotime($model->loppui));
?>


	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Toteuman muutos'); ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">


<div class="row form">
  <div class="col-sm-4">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'toteutuneet-form-upd',
	'enableAjaxValidation'=>false,
)); ?>

		<?php echo $form->textField($model,'id'); ?>
		<?php echo $form->hiddenField($model,'kid'); ?>
		<?php echo $form->hiddenField($model,'tid'); ?>
		<?php echo $form->hiddenField($model,'kohdenID',array('id'=>'kohdenID')); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->dropDownList($model,'kohde_kannasta', 
			CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite ASC')), 'osoite', 'osoite'), 
			array('class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<input type="datetime-local" name="Toteutuneet[aloitan]" value="<?php echo $apvm[$model->id].'T'.$at[$model->id]; ?>" class="form-control" id="aloitan">
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<input type="datetime-local" name="Toteutuneet[loppui]" value="<?php echo $lpvm[$model->id].'T'.$lt[$model->id]; ?>" class="form-control" id="loppui">
	</div>


<?php $this->endWidget(); ?>

  </div>
</div><!-- form -->


	<div class="modal-footer">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary updTot')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>
