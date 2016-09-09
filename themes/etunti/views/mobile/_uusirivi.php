<?php
/* @var $this MobileController */
/* @var $model Mobile */
/* @var $form CActiveForm */

$pvm = '';
if(!isset($_POST['forThis']))
{
	exit;
} else {
	$ex = explode("_",$_POST['forThis']);
	$pvm = $ex[0];
}
$t = Tyontekijat::model()->findbypk($ex[1]);
?>


	<div class="modal-dialog modal-lg">
	    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		<h2 class="modal-title"><?php echo Yii::t('main', 'Uuden rivin lisääminen'); ?></h2>
	
		</div>
		<div class="modal-body">

	<div class="dialogTable clearfix modal-osio">


<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mobile-form',
	'enableAjaxValidation'=>false,
	'clientOptions' => array(
                    'validateOnSubmit' => false,
                ),
)); ?>

	<?php echo $form->errorSummary($model); ?>


  <?php if(Yii::app()->user->adminStatus == 1) : ?>
  <input type="hidden" name="pvm" value="<?php echo $pvm; ?>">
  <div class="row">
    <div class="col-sm-12">
    <legend>
	<b><?php echo Yii::t('main','Päivämäärä').' '.date("d.m.Y",strtotime($pvm)); ?></b>
    </legend>
    </div>

    <div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid',array('value'=>$t->id,'class'=>'form-control input-sm','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('value'=>$t->tekijan_nimi,'class'=>'form-control input-sm','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

    </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('value'=>date("d.m.Y",strtotime($pvm)).' 00:00', 'size'=>20,'maxlength'=>20,'class'=>'form-control input-sm al')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('value'=>date("d.m.Y",strtotime($pvm)).' 00:00','size'=>20,'maxlength'=>20,'class'=>'form-control input-sm lp')); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>

    </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohde_kannasta'); ?>
		<?php echo $form->dropDownList($model,'kohde_kannasta', 
			CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite ASC')), 'id', 'osoite'), 
			array('empty'=>Yii::t('main','Valitse kohde'),'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'kohde_kannasta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('empty'=>Yii::t('main','Valitse tilanne'),'class'=>'form-control input-sm'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>

    </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->textField($model,'kohdenID',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sairaus'); ?>
		<?php 
        	$tal = array(1=>'Palkaton',2=>'Palkallinen',3=>'Lapsen sairaus');
		echo $form->dropDownList($model,'sairaus', $tal, 
		array('empty'=>'Valitse','class'=>'form-control input-sm')) ?>
		<?php echo $form->error($model,'sairaus'); ?>
	</div>

    </div>
  </div>
  <?php endif; ?>

<?php $this->endWidget(); ?>

	<br>
	<div class="modal-footer">
		<span class="btn btn-default" data-dismiss="modal">Sulje</span>
		<?php echo CHtml::Button('Tallenna',array('class'=>'btn btn-primary uusiRiviSubmit')); ?>
	</div>		
		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>


<!--
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>

-->





</div><!-- form -->


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>

<script type="text/javascript">
$(document).ready(function(){

$("#Mobile_kohde_kannasta").change(function(){
	var kohdenID = $(this).val();
	$("#Mobile_kohdenID").val(kohdenID);
});


  $('.al').mask('00.00.0000 00:00',{
        placeholder: "__.__.____ __:__"
  });

  $('.lp').mask('00.00.0000 00:00',{
        placeholder: "__.__.____ __:__"
  });


$('.al').blur(function(){
	checkMaxValues($(this).val());
});

$('.lp').blur(function(){
	checkMaxValues($(this).val());
});

function checkMaxValues(val){
	var check = val.split(" ");
	var dat = check[0].split(".");
	var time = check[1].split(":");
	
	if(dat[0] > 31)
	alert('Päivät eivät voi olla yli 31.')
	if(dat[1] > 12)
	alert('Kuukaudet eivät voi olla yli 12.')

	if(time[0] > 23)
	alert('Tunnit eivät voi olla yli 23.')
	if(time[1] > 59)
	alert('Minutit eivät voi olla yli 59.')
}


/*
  $('.uusiRivi').click(function(){


    var Mobile_aloitan = $("#Mobile_aloitan").val();
    var Mobile_loppui = $("#Mobile_loppui").val();
    var Mobile_kohde_kannasta = $("#Mobile_kohde_kannasta").val();
    var Mobile_status = $("#Mobile_status").val();

    if (Mobile_aloitan  === '__:__') {
        $('#Mobile_aloitan').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (Mobile_loppui  === '__:__') {
        $('#Mobile_loppui').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (Mobile_kohde_kannasta  === '') {
        $('#Mobile_kohde_kannasta').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (Mobile_status  === '') {
        $('#Mobile_status').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

		$('#mobile-form').submit();
		return false;
  });

  $('#mobile-form').on('submit',function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/mobile/uusirivi',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			console.log(data);
		/*
			var divID = data.split("_");
			if( divID )
			blockUpdater(divID);
			$('#showres').modal('hide');
		*/
/*
		setTimeout(function(){document.location.href = "index";},500);
		return false;
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });


	e.preventDefault(); 
  });
*/
});
</script>
