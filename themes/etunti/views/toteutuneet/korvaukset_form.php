<?php
/* @var $this KorvauksetController */
/* @var $model Korvaukset */
/* @var $form CActiveForm */
?>

<div class="form row">
 <div class="col-sm-6 col-sm-offset-3">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'korvaukset-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

		<?php echo $form->hiddenField($model,'tid',array('value'=>$tid, 'class'=>'form-control')); ?>
		<?php echo $form->hiddenField($model,'pvm',array('value'=>$pvm, 'class'=>'form-control')); ?>
 
	<div class="section">
		<?php echo $form->labelEx($model,'syy'); ?>
		<?php 
        	$l = $this->korvauksetArray();
		echo $form->dropDownList($model,'syy', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'syy'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'korvaus'); ?>
		<?php echo $form->numberField($model,'korvaus',array('size'=>30,'maxlength'=>20,'class'=>'form-control',"step"=>"any")); ?>
		<?php echo $form->error($model,'korvaus'); ?>
	</div>
	<br>
	<div class="section buttons">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-lg btn-block btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>
 </div>
</div>



<script type="text/javascript">
$(document).ready(function(){

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
     url: location.protocol + "//" + location.host + '/index.php/korvaukset/create_netvisor',
     data:$(this).serialize(),
     type:'POST',
     success:function(data){
	data = JSON.parse(data);
    	console.log(data);
	//$('#showres').modal('hide');
	if(data['OK'])
	{
		window.location.href=location.protocol + "//" + location.host + '/index.php/toteutuneet/index';
	}
	if(data['ERROR'])
	{
		alert(data['ERROR']);
	}
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

