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
            <div class="panel">
                <div class="panel-heading myBgColors">
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
			<br>
                        <strong><?php echo str_replace("\n","<br>",$model->viesti); ?></strong>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel">
                <div class="panel-heading myBgColors">
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
			<br>
                        <strong><?php echo str_replace("\n","<br>",$model->viesti); ?></strong>
                    </li>
                </ul>
            </div>
        </div>

</div>

<?php /*
<div class="row">
            <div class="panel">
                <div class="panel-heading myBgColors">
                    <h4 class="text-center"><?php echo $form->labelEx($model,'tietoja'); ?></h4>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo $form->textArea($model,'tietoja',array('rows'=>20, 'cols'=>50,'class'=>'form-control','readonly'=>'yes')) //,'readonly'=>'yes'; ?>
                    </li>
                </ul>
            </div>
</div>
*/ ?>




  <?php if(Yii::app()->user->adminStatus == 1) : ?>
  <div class="row">
    <div class="col-sm-4">

	<div class="section">
		<?php echo $form->labelEx($model,'gps_enabled'); ?>
		<?php echo $form->textField($model,'gps_enabled',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'gps_enabled'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'app_aloitus_destination_checker'); ?>
		<?php echo $form->textField($model,'app_aloitus_destination_checker',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'app_aloitus_destination_checker'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'app_lopetus_destination_checker'); ?>
		<?php echo $form->textField($model,'app_lopetus_destination_checker',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'app_lopetus_destination_checker'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'app_platform'); ?>
		<?php echo $form->textField($model,'app_platform',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'app_platform'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php
			$site = Yii::app()->createController('Site');
			$list = $site[0]->tyontekiatArrayList(1); 
			echo $form->dropDownList($model, 'tid', $list, array('class'=>'form-control')); ?>
		<?php echo $form->hiddenField($model,'tekijan_nimi',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->dropDownList($model, 'kohdenID', CHtml::listData(Kohteet::model()->findAll(array('order'=>'osoite')), 'id', 'osoite'), array('empty'=>'Valitse', 'class'=>'form-control')); ?>
		<?php echo $form->hiddenField($model,'kohde_kannasta',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('class'=>'form-control'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>
	<div class="section">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>

	<?php if( isset($model->id) ): ?>
	<div class="section">
		<?php echo $form->labelEx($model,'tv_id'); ?>
		<?php
       		$criteria = new CDbCriteria();
       		$criteria->condition = " 
			DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') 
			BETWEEN '".date("Y-m-d", strtotime($model->aloitan))."' AND '".date("Y-m-d", strtotime($model->aloitan))."'
		";
		?>
		<?php echo $form->dropDownList($model, 'tv_id', CHtml::listData(Tyovuoroot::model()->findAll($criteria), 'id', 'osoiteAndAika'), array('empty'=>'Valitse', 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tv_id'); ?>
	</div>
	<?php endif; ?>
<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna', array('class'=>'btn btn-primary')); ?>
	</div>
    </div>
  </div>
  <?php endif; ?>




<?php $this->endWidget(); ?>

</div><!-- form -->




<script type="text/javascript">
$(document).ready(function(){

  $("#Mobile_tid").change(function(){
	$("#Mobile_tekijan_nimi").val( $("#Mobile_tid option:selected").text() );
  });
  $("#Mobile_kohdenID").change(function(){
	$("#Mobile_kohde_kannasta").val( $("#Mobile_kohdenID option:selected").text() );
  });

});
</script>

