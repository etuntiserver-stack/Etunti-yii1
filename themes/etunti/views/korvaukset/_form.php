<?php
/* @var $this KorvauksetController */
/* @var $model Korvaukset */
/* @var $form CActiveForm */
?>

        <div class="form col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="text-center"><b class="glyphicon glyphicon-plus"></b> <?php echo Yii::t('main', 'KORVAUS'); ?></h4>
                </div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'korvaukset-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div id="error_info"></div>

                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php 
		   	$site = Yii::app()->createController('Site');
            $employeeList = $site[0]->workerListSelect2(
                "Korvaukset[tid]", // name
                "", // class
                "Korvaukset_tid", // ID
                "", // pre selected
                1, // actie or not
                false, //multiple or not
                [0 => "Valitse työntekijä"]
            );
            echo $employeeList;
		?>
		<?php echo $form->error($model,'tid'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('value'=>date("d.m.Y"),'size'=>30,'maxlength'=>20,'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'pvm'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'syy'); ?>
		<?php echo $form->textField($model,'syy',array('size'=>30,'maxlength'=>1000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syy'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'korvaus'); ?>
		<?php echo $form->textField($model,'korvaus',array('size'=>30,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'korvaus'); ?>
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


$(".select2").select2();

$('#korvaukset-form').on('submit',function(e) {

  var Korvaukset_tid = $("#Korvaukset_tid").val();
  var Korvaukset_pvm = $("#Korvaukset_pvm").val();
  var Korvaukset_syy = $("#Korvaukset_syy").val();
  var Korvaukset_korvaus = $("#Korvaukset_korvaus").val();

    if (Korvaukset_tid  === '') {
        $('#Korvaukset_tid').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Korvaukset_pvm  === '') {
        $('#Korvaukset_pvm').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Korvaukset_syy  === '') {
        $('#Korvaukset_syy').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }
    if (Korvaukset_korvaus  === '') {
        $('#Korvaukset_korvaus').css({"border" : "2px #f13010 solid"}).focus();
        return false;
    }

    $("#haku").hide('slow');

  $.ajax({
     url: location.protocol + "//" + location.host + '/index.php/korvaukset/create',
     data:$(this).serialize(),
     type:'POST',
     success:function(data){
    	console.log(data);
	if(data == 'ok'){
		setTimeout(function(){document.location.href = "palkkataulukko";},500);
	} else {
		$("#error_info").html(data);
	}
     },
     error:function(data){
	console.log(data); 
     }
  });

  e.preventDefault(); 

});


});
</script>

