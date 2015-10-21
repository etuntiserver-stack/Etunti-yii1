<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */
/* @var $form CActiveForm */
?>


        <div class="form col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="text-center"><b class="glyphicon glyphicon-plus"></b> <?php echo Yii::t('main', 'YLITYÖTUNNIT'); ?></h4>
                </div>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lisatyotunnit-form',
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
		<?php echo $form->textField($model,'pvm',array('value'=>date("d.m.Y"),'size'=>30,'maxlength'=>30,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'pvm'); ?>
	</div>
                    </li>
		    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'syy'); ?>
		<?php echo $form->textField($model,'syy',array('size'=>30,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syy'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'prosentti'); ?>
		<?php echo $form->textField($model,'prosentti',array('size'=>30,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'prosentti'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'tunnimaara'); ?>
		<?php echo $form->textField($model,'tunnimaara',array('size'=>30,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tunnimaara'); ?>
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

$('#lisatyotunnit-form').on('submit',function(e) {

  var Lisatyotunnit_tid = $("#Lisatyotunnit_tid").val();
  var Lisatyotunnit_pvm = $("#Lisatyotunnit_pvm").val();
  var Lisatyotunnit_syy = $("#Lisatyotunnit_syy").val();
  var Lisatyotunnit_prosentti = $("#Lisatyotunnit_prosentti").val();
  var Lisatyotunnit_tunnimaara = $("#Lisatyotunnit_tunnimaara").val();

    if (Lisatyotunnit_tid  === '') {
        $('#Lisatyotunnit_tid').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Lisatyotunnit_pvm  === '') {
        $('#Lisatyotunnit_pvm').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Lisatyotunnit_syy  === '') {
        $('#Lisatyotunnit_syy').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Lisatyotunnit_prosentti  === '') {
        $('#Lisatyotunnit_prosentti').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Lisatyotunnit_tunnimaara  === '') {
        $('#Lisatyotunnit_tunnimaara').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }

    $("#haku").hide('slow');

  $.ajax({
     url: location.protocol + "//" + location.host + '/index.php/lisatyotunnit/create',
     data:$(this).serialize(),
     type:'POST',
     success:function(data){
    	console.log(data);
	setTimeout(function(){document.location.href = "palkkataulukko";},500);
	return false;
     },
     error:function(data){
	console.log(data); 
     }
  });

  e.preventDefault(); 

});


});
</script>

