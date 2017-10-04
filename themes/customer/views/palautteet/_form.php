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
   @media screen and (min-width: 480px) {
	.img-thumbnail{ width: 100px }
	.col-xs-6{ width: 100px }
   }

	.img-thumbnail{ background: none; border: none }

.emoji_passive{ opacity:0.5 }
.emoji_active { /*border: 2px #37bc9b solid;*/ }
</style>
<legend><?=Yii::t('main', 'Aloita palauteen antaminen valitsemalla hymynaama.')?></legend>
<div class="row" id="emoji">
 <div class="col-xs-6">
	<img src="img/emoji/1.png" class="img-thumbnail emoji_passive" tila="1">
 </div>
 <div class="col-xs-6">
	<img src="img/emoji/3.png" class="img-thumbnail emoji_passive" tila="3">
 </div>
</div>

<br>

<div class="row collapse" id="lomake">
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
		<?php echo $form->labelEx($model,'kategoria'); ?>

		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='kategoria' ",array('order' => "select_type"));
		if(count($l) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "kategoria";
			$new_val->value = "Testi kategoria";
			if($new_val->save())
	      			$l = Valikkoot::model()->findAll(" select_type='kategoria' ",array('order' => "select_type"));
			else
				var_dump($new_val->getErrors());
		}
		foreach($l as $val)
			$list[$val->id] = $val->value;

        		echo $form->dropDownList($model, 'kategoria', $list,
			array('empty'=>'Valitse', 'class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'kategoria'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viimeinen_tyo'); ?>
		<?php
		$a = Asiakkaat::model()->findByPk($model->asiakas_id);
		$asiakkaat = Yii::app()->createController('Asiakkaat');
		$dataArray = $asiakkaat[0]->toteutuneetTunnitArray($a);
		$list = array();
		foreach($dataArray as $item)
		{
			$txt = $item['pvm'].' '.$item['kohde_kannasta'].' klo: '.$item['aloitus'].'-'.$item['lopetus'];
			$list[$txt] = $txt;
		}
        	echo $form->dropDownList($model, 'viimeinen_tyo', $list,
		array('empty'=>'Valitse','class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'viimeinen_tyo'); ?>
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
		$('.collapse').addClass('in');
	});
});
</script>
