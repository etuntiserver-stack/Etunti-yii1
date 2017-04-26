<?php
/* @var $this PalautteetController */
/* @var $model Palautteet */
/* @var $form CActiveForm */
$model->asiakas_id = Yii::app()->user->asiakas;
?>


	   <h2 class="myBgColors p10 link" data-toggle="collapse" data-target="#avaaPalaute"> <i class="fa fa-paper-plane-o"></i> <?php echo Yii::t('main', 'Lähetä palaute'); ?> <i class="fa fa-caret-down" aria-hidden="true"></i> </h2>

            <div class="admin-form collapse in" id="avaaPalaute">
              <div class="panel heading-border">
                <div class="panel-body bg-light">


<style>
.emoji_passive{ opacity:0.5 }
.emoji_active { border: 2px #37bc9b solid; }
</style>

<div class="row" id="emoji">
 <div class="col-sm-2">
	<img src="img/emoji/1.png" class="img-thumbnail emoji_active" tila="1">
 </div>
 <div class="col-sm-2">
	<img src="img/emoji/2.png" class="img-thumbnail emoji_passive" tila="2">
 </div>
 <div class="col-sm-2">
	<img src="img/emoji/3.png" class="img-thumbnail emoji_passive" tila="3">
 </div>
</div>

<br>

<div class="row">
 <div class="col-sm-4">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'palautteet-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->hiddenField($model,'asiakas_id',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
	<?php echo $form->errorSummary($model); ?>


	<?php echo $form->hiddenField($model,'emoji_tila',array('value'=>1, 'size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
	<?php echo $form->hiddenField($model,'lahettaja',array('value'=>'asiakas', 'class'=>'form-control')); ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'otsikko'); ?>
		<?php echo $form->textField($model,'otsikko',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'otsikko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'teksti'); ?>
		<?php echo $form->textarea($model,'teksti', array('rows'=>4, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'teksti'); ?>
	</div>


	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Lähetä' : 'Tallenna',array('class'=>'btn btn-primary myBgColors submitButton')); ?>
	</div>

<?php $this->endWidget(); ?>
 </div>
</div><!-- form -->

                </div>
              </div>
            </div>


<script>
$(function() {

	$('#emoji img').click(function(){
		$('#emoji img').removeClass('emoji_active emoji_passive').css({"opacity":"0.5"});
		$(this).addClass('emoji_active').css({"opacity":"1"});
		$('#Palautteet_emoji_tila').val( $(this).attr('tila') );
	});
});
</script>
