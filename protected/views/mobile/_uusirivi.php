<?php
/* @var $this MobileController */
/* @var $model Mobile */
/* @var $form CActiveForm */
if(!isset($_POST['forThis']))
{
	exit;
} else {
	$ex = explode("_",$_POST['forThis']);
	$pvm = $ex[0];
}
$t = Tyontekijat::model()->findbypk($ex[1]);
?>


	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Uuden rivin lisääminen'); ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">


<div class="row form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mobile-form',
	'enableAjaxValidation'=>false,
	'clientOptions' => array(
                    'validateOnSubmit' => false,
                ),
)); ?>

	<?php echo $form->errorSummary($model); ?>


  <?php if(Yii::app()->user->adminStatus == 1) : ?>
  <input type="hidden" name="pvm" value="<?php echo $pvm; ?>">
  <div class="row">
    <div class="col-sm-12">
    <legend>
	<b><?php echo Yii::t('main','Päivämäärä').' '.date("d.m.Y",strtotime($pvm)); ?></b>
    </legend>
    </div>

    <div class="col-sm-3">

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid',array('value'=>$t->id,'class'=>'form-control','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('value'=>$t->tekijan_nimi,'class'=>'form-control','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

    </div><div class="col-sm-3">

	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('size'=>20,'maxlength'=>20,'class'=>'form-control timepicker')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('size'=>20,'maxlength'=>20,'class'=>'form-control timepicker')); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>

    </div><div class="col-sm-3">

	<div class="row">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->dropDownList($model,'kohde_kannasta', 
			CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite ASC')), 'id', 'osoite'), 
			array('empty'=>Yii::t('main','Valitse kohde'),'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('empty'=>Yii::t('main','Valitse tilanne'),'class'=>'form-control'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>

    </div><div class="col-sm-3">

	<div class="row">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID',array('size'=>20,'maxlength'=>20,'class'=>'form-control','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>

    </div>
  </div>
  <?php endif; ?>

<?php $this->endWidget(); ?>


	<div class="modal-footer">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php echo CHtml::Button('Tallenna',array('class'=>'btn btn-primary uusiRivi')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>

<!--
	<div class="row">
		<?php echo $form->labelEx($model,'etaisyys'); ?>
		<?php echo $form->textField($model,'etaisyys',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'etaisyys'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'hyvaksytty'); ?>
		<?php echo $form->textField($model,'hyvaksytty',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'hyvaksytty'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'asiakas_num'); ?>
		<?php echo $form->textField($model,'asiakas_num',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakas_num'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'imei'); ?>
		<?php echo $form->textField($model,'imei',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'imei'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'bluetooth_name'); ?>
		<?php echo $form->textField($model,'bluetooth_name',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'bluetooth_name'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'my_location'); ?>
		<?php echo $form->textField($model,'my_location',array('size'=>60,'maxlength'=>1000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'my_location'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>
<div class="row">
  <div class="col-sm-4">
	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>
  </div>
</div>
-->





</div><!-- form -->
