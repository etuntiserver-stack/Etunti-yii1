<?php
/* @var $this MobileController */
/* @var $model Mobile */
/* @var $form CActiveForm */
  $perusTiedot = array('0'=>$model->kohde_kannasta,'1'=>$model->aloitan,'2'=>$model->loppui);
if(isset($model->tietoja))
{
  preg_match("/<perus>(.*?)<\/perus>/",$model->tietoja, $match);
  if(isset($match['0']))
  $perusTiedot = explode("//",$match['0']);
  $model->tietoja = preg_replace("/<perus>(.*?)<\/perus>/", "", $model->tietoja);
}
  $kohde_kannasta = $model->kohde_kannasta;
  $aloitan = $model->aloitan;
  $loppui = $model->loppui;

  $tot = Toteutuneet::model()->find(" kid = '".$model->id."' ");
  if(isset($tot->id)){
  $kohde_kannasta = $tot->kohde_kannasta;
  $aloitan = $tot->aloitan;
  $loppui = $tot->loppui;
  }


?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mobile-form',
	'enableAjaxValidation'=>false,
	'clientOptions' => array(
                    'validateOnSubmit' => false,
                ),
)); ?>



	<?php echo $form->errorSummary($model); ?>

<style>
.minheight{
	min-height: 65px;
}
</style>

<div class="row">

        <div class="col-md-4 col-md-offset-2">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'Perustiedot'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong><?php echo $model->tekijan_nimi; ?></strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item minheight">
                        <?php echo $form->labelEx($model,'kohde_kannasta'); ?>
                        <strong><?php echo $perusTiedot['0']; ?></strong>
                    </li>
                    <li class="list-group-item minheight">
                        <?php echo $form->labelEx($model,'aloitan'); ?>
                        <strong><?php echo $perusTiedot['1']; ?></strong>
                    </li>
                    <li class="list-group-item minheight">
                        <?php echo $form->labelEx($model,'loppui'); ?>
                        <strong><?php echo $perusTiedot['2']; ?></strong>
                    </li>
                    <li class="list-group-item"  style="height:172px">
                        <?php echo $form->labelEx($model,'viesti'); ?>
                        <strong><?php echo $model->viesti; ?></strong>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'Toteutuneet tiedot'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong><?php echo $model->tekijan_nimi; ?></strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item minheight">
                        <?php echo $form->labelEx($model,'kohde_kannasta'); ?>
                        <strong><?php echo $kohde_kannasta; ?></strong>
                    </li>
                    <li class="list-group-item minheight">
                        <?php echo $form->labelEx($model,'aloitan'); ?>
                        <strong><?php echo $aloitan; ?></strong>
                    </li>
                    <li class="list-group-item minheight">
                        <?php echo $form->labelEx($model,'loppui'); ?>
                        <strong><?php echo $loppui; ?></strong>
                    </li>
                    <li class="list-group-item"  style="height:172px">
                        <?php echo $form->labelEx($model,'viesti'); ?>
                        <strong><?php echo $model->viesti; ?></strong>
                    </li>
                </ul>
            </div>
        </div>

</div>


<div class="row">
        <div class="col-md-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo $form->labelEx($model,'tietoja'); ?></h4>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo $form->textArea($model,'tietoja',array('rows'=>20, 'cols'=>50,'class'=>'form-control')) //,'readonly'=>'yes'; ?>
                    </li>
                </ul>
            </div>
        </div>
</div>




  <?php if(Yii::app()->user->adminStatus == 1) : ?>
  <div class="row">
    <div class="col-sm-4">

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


	<div class="buttons">
        <p class="text-danger">Luettuja tunteja ei voi muokata. Tee muokkaukset tuntien hyväksyntä sivulla.</p>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary disabled')); ?>
	</div>
    </div>
  </div>
  <?php endif; ?>




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



<?php $this->endWidget(); ?>

</div><!-- form -->
