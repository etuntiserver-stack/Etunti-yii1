<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */
/* @var $form CActiveForm */

if(isset($_POST['forid'])){
  $s= Mobile::model()->findbypk($_POST['forid']);

  $at[$s->id] = date("H:i",strtotime($s->aloitan));
  $apvm[$s->id] = date("Y-m-d",strtotime($s->aloitan));

  $lt[$s->id] = date("H:i",strtotime($s->loppui));
  $lpvm[$s->id] = date("Y-m-d",strtotime($s->loppui));

  $model->kohde_kannasta = $s->kohde_kannasta;
}
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
	'id'=>'toteutuneet-form',
	'enableAjaxValidation'=>false,
)); ?>



		<?php echo $form->hiddenField($model,'kid',array('value'=>$s->id)); ?>
		<?php echo $form->hiddenField($model,'tid',array('value'=>$s->tid)); ?>
		<?php echo $form->hiddenField($model,'status',array('value'=>$s->status)); ?>
		<?php echo $form->hiddenField($model,'tekijan_nimi',array('value'=>$s->tekijan_nimi)); ?>
		<?php echo $form->hiddenField($model,'kohdenID',array('value'=>$s->kohdenID,'id'=>'kohdenID')); ?>


	<div class="row">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->dropDownList($model,'kohde_kannasta', 
			CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite ASC')), 'osoite', 'osoite'), 
			array('class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<input type="datetime-local" name="Toteutuneet[aloitan]" value="<?php echo $apvm[$s->id].'T'.$at[$s->id]; ?>" class="form-control" id="aloitan">
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<input type="datetime-local" name="Toteutuneet[loppui]" value="<?php echo $lpvm[$s->id].'T'.$lt[$s->id]; ?>" class="form-control" id="loppui">
	</div>


<?php $this->endWidget(); ?>


  </div>
</div><!-- form -->

	<div class="modal-footer">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php echo CHtml::Button('Tallenna',array('class'=>'btn btn-primary uusiTot')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->




	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>



<?php
/*
<!--
	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<?php echo $form->textField($model,'tyoajanlaatu',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoajanlaatu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php echo $form->textField($model,'tyoajanmerkinta',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoajanmerkinta'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50,'value'=>$s->tietoja,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>
-->
*/
?>
