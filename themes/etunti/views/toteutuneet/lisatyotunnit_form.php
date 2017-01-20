<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */
/* @var $form CActiveForm */
?>

<div class="form row">
 <div class="col-sm-6 col-sm-offset-3">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lisatyotunnit-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>


		<?php echo $form->hiddenField($model,'tid',array('value'=>$tid, 'class'=>'form-control')); ?>
		<?php echo $form->hiddenField($model,'pvm',array('value'=>$pvm, 'class'=>'form-control')); ?>

	<div class="section">
		<?php echo $form->labelEx($model,'syy'); ?>

	   <div class="form-inline">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='YLITYÖTUNNIT' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->value] = $v->value;

        	echo $form->dropDownList($model, 'syy', $list,
		array('empty'=>'','class'=>'form-control'));
        	?>
		<span class="btn btn-primary myBgColors muokaValiko" for="YLITYÖTUNNIT"><i class="fa fa-pencil-square-o"></i></span>
	   </div>

		<?php echo $form->error($model,'syy'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'prosentti'); ?>
		<?php echo $form->textField($model,'prosentti',array('size'=>30,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'prosentti'); ?>
	</div>

	<div class="section">
		<?php echo $form->labelEx($model,'tunnimaara'); ?>
		<?php echo $form->textField($model,'tunnimaara',array('size'=>30,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tunnimaara'); ?>
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

/* valikot */
$(".muokaValiko").click(function() {
    var thisFor = $(this).attr("for");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko",
	   type:'POST',
	   data: { "select_type" : thisFor },
           success: function(data){
		//console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */

  $(".sw").bootstrapSwitch({
	size: "small",
	onColor: "success",
	offColor: "warning",
	onText: "Kyllä",
	offText: "Ei"
  });

});
</script>


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

