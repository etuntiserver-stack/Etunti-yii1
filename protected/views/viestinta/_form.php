<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */
/* @var $form CActiveForm */

  $admin = '';
$ad = Administrators::model()->findbypk(Yii::app()->user->adminID);
if(isset($ad->adm_nimi))
  $admin = $ad->id.",".$ad->adm_nimi;


?>

<div class="row form">
  <div class="col-sm-4">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'viestinta-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php 
	if(empty($ad->adm_email)){
	echo CHtml::link('Sähköposti puutuu vastauksen varten','/index.php/administrators/update?id='.$ad->id,array('class'=>'btn btn-danger'));
	} else {
	?>

	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->hiddenField($model,'pvm',array('value'=>date("Y-m-d H:i:s"))); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin',array('value'=>$admin,'class'=>'form-control input-sm','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>

	<div class="row">
		<?php
		if(isset($_GET['tid']))
		{

		  $tt = Tyontekijat::model()->findbypk($_GET['tid']);
		  if(isset($tt->tekijan_nimi))
		  echo '<label>'.Yii::t('main','Saaja: ').' '.$tt->tekijan_nimi.'</label>';

		  echo $form->hiddenField($model,'tekija',array('value'=>$_GET['tid'],'class'=>'form-control input-sm','readonly'=>'yes'));
		} else {

		  echo $form->labelEx($model,'tekija');
        	  $list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi');
        	  echo $form->dropDownList($model, 'tekija', $list,array('class'=>'form-control input-sm'));
		}
        	?>
		<?php echo $form->error($model,'tekija'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textArea($model,'viesti',array('rows'=>6, 'cols'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'viesti'); ?>
	</div>

	<?php if(!empty($ad->adm_email)): ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'LÄHETÄ' : 'Luo',array('class'=>'btn btn-sm btn-success')); ?>
	</div>
	<?php endif; 
	} 
	?>

<?php $this->endWidget(); ?>
  </div>
</div><!-- form -->
