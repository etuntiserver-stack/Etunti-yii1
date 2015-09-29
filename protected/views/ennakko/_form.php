<?php
/* @var $this EnnakkoController */
/* @var $model Ennakko */
/* @var $form CActiveForm */
?>

        <div class="form col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="text-center"><b class="glyphicon glyphicon-plus"></b> <?php echo Yii::t('main', 'ENNAKKO'); ?></h4>
                </div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ennakko-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->dropDownList($model,'tid', 
			CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi'), 
			array('empty'=>'Valitse työntekijä','class'=>'form-control')) ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('value'=>date("d.m.Y"),'size'=>30,'maxlength'=>20,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'pvm'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'syy'); ?>
		<?php echo $form->textField($model,'syy',array('size'=>30,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syy'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'ennakko'); ?>
		<?php echo $form->textField($model,'ennakko',array('size'=>30,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'ennakko'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
	<label></label><br><br><br>
	</div>
                    </li>

                </ul>

	<div class="panel-footer">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-lg btn-block btn-info')); ?>
	</div>

<?php $this->endWidget(); ?>


            </div>
        </div>



<script type="text/javascript">
$(document).ready(function(){

$('#ennakko-form').on('submit',function(e) {

  var Ennakko_tid = $("#Ennakko_tid").val();
  var Ennakko_pvm = $("#Ennakko_pvm").val();
  var Ennakko_syy = $("#Ennakko_syy").val();
  var Ennakko_ennakko = $("#Ennakko_ennakko").val();

    if (Ennakko_tid  === '') {
        $('#Ennakko_tid').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Ennakko_pvm  === '') {
        $('#Ennakko_pvm').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Ennakko_syy  === '') {
        $('#Ennakko_syy').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Ennakko_ennakko  === '') {
        $('#Ennakko_ennakko').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }

    $("#haku").hide('slow');

  $.ajax({
     url: location.protocol + "//" + location.host + '/index.php/ennakko/create',
     data:$(this).serialize(),
     type:'POST',
     info:function(data){
    	console.log(data);
	setTimeout(function(){document.location.href = location.protocol + "//" + location.host + '/index.php/mobile/palkkataulukko';},500);
	return false;
     },
     error:function(data){
	onsole.log(data); 
     }
  });

  e.preventDefault(); 

});


});
</script>

