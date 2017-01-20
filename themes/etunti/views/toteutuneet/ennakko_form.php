<?php
/* @var $this EnnakkoController */
/* @var $model Ennakko */
/* @var $form CActiveForm */
?>

<div class="form row">
 <div class="col-sm-6 col-sm-offset-3">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ennakko-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>


		<?php echo $form->hiddenField($model,'tid',array('value'=>$tid, 'class'=>'form-control')); ?>
		<?php echo $form->hiddenField($model,'pvm',array('value'=>$pvm, 'class'=>'form-control')); ?>

	<div class="section">
		<?php echo $form->labelEx($model,'syy'); ?>
		<?php echo $form->textField($model,'syy',array('size'=>30,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syy'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'ennakko'); ?>
		<?php echo $form->textField($model,'ennakko',array('size'=>30,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'ennakko'); ?>
	</div>
	<br>
	<div class="buttons section">
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
     success:function(data){
    	console.log(data);
	//$('#showres').modal('hide');
	window.location.href=location.protocol + "//" + location.host + '/index.php/toteutuneet/index'
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

