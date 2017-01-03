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
		<?php 
		   	$site = Yii::app()->createController('Site');
			$list = $site[0]->tyontekiatArrayList(1);
			echo $form->dropDownList($model,'tid', $list, 
			array('empty'=>'Valitse työntekijä','class'=>'form-control')) 
		?>
		<?php echo $form->error($model,'tid'); ?>
	</div>
                    </li>
                    <li class="list-group-item">
	<div class="row">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('value'=>date("Y-m-d"),'size'=>30,'maxlength'=>30,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'pvm'); ?>
	</div>
                    </li>
		    <li class="list-group-item">
	<div class="row">
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



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


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

