<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

if(isset($model->id))
$model->hinta = str_replace(",",".",$model->hinta);
?>
<div class="row">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'mobile-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php 
		$site = Yii::app()->createController('Site');
		$list = $site[0]->tyontekiatArrayList(1);
		echo $form->dropDownList($model, 'tid', $list, 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo CHtml::label("Asiakas", "asiakas-id"); ?>
		<?php echo CHtml::dropDownList("asiakas", [],
			CHtml::listData(
				array_merge(["id" => 0, "Fullname" => "Valitse"],
				Asiakkaat::model()
					->findAll("aktiivinen=1")), "id", "Fullname"),
			["class" => "form-control", "id" => "asiakas-id"]
		); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kohdenID'); ?>
		<?php echo $form->dropDownList($model, 'kohdenID', CHtml::listData(Kohteet::model()->findAll(array('order'=>'osoite')), 'id', 'osoite'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'kohdenID'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
		      $list = array(3=>Yii::t('main','TYÖ'),2=>Yii::t('main','MATKA'),10=>Yii::t('main','LOUNASTAUKO'));
		      //array_unshift($list, $list[$s->status]);
		      echo $form->dropDownList($model,'status', 
			 	$list, 
				array('options' => array(3=>array('selected'=>true)),'class'=>'form-control'));
		?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aloitan'); ?>
		<?php echo $form->textField($model,'aloitan',array('class'=>'form-control datetimepicker')); ?>
		<?php echo $form->error($model,'aloitan'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'loppui'); ?>
		<?php echo $form->textField($model,'loppui',array('class'=>'form-control datetimepicker')); ?>
		<?php echo $form->error($model,'loppui'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viesti'); ?>
		<?php echo $form->textarea($model,'viesti',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viesti'); ?>

	</div>

		<?php echo $form->hiddenField($model,'kohde_kannasta',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->hiddenField($model,'tekijan_nimi',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>

  </div>
</div><!-- form -->

<br>

	<div class="section">
		<div class="alert bg-warning">Tämä kirjaus mene suoraan Tuntien hyväksyntä  sivuille</div>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors luoTallennaKohde')); ?>
	</div>

<?php $this->endWidget(); ?>



<script type="text/javascript">
$(document).ready(function(){


$("#asiakas-id").change(function() {
	const clientId = $(this).val();
	fetch("/index.php/mobile/ensisijainen_kohde?clientId=" + clientId)
		.then(r => r.json())
		.then(primary => {
			$("#Mobile_kohdenID").val(primary.primary).change();
		});
});

$('#Mobile_tid').change(function(){

   var thisVal = $('#Mobile_tid option:selected').text();
   $('#Mobile_tekijan_nimi').val(thisVal);

});

$('#Mobile_kohdenID').change(function(){

   var thisVal = $('#Mobile_kohdenID option:selected').text();
   $('#Mobile_kohde_kannasta').val(thisVal);

});


  $('.luoTallennaKohde').click(function(){


	// <-- tarkista , aloitus ja lopetus
	var lomake  = [{
		aloitan 	: $("#Mobile_aloitan").val(),
		loppui 		: $("#Mobile_loppui").val(),
		tid 		: $("#Mobile_tid").val(),
	}];

	var isLine = '';
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/on_olemassa',
           type: "POST",
	   async: false,
	   data: lomake[0],
           success: function(data){
		data = JSON.parse(data);
		console.log(data);
		if(data !== '')
		isLine = data;
           }
        });

	if(isLine !== '')
	{
		alert(isLine);
		return false;
	}
	// tarkista , aloitus ja lopetus -->


	var lomake  = $('#mobile-form').serialize();
	var isLine = false;
        $.ajax({
           url: 'create',
           type: "POST",
	   async: false,
	   data: lomake,
           success: function(data){
		console.log(data);
		if(data == 1)
		isLine = true;
           }
        });

	if(!isLine)
	{
		$('#mobile-form').submit();
	} else {
		alert('Tämä on jo olemassa.');
		return false;
	}

  });

  $('#mobile-form').on('submit', function(e){


	window.location.href="index";
	e.preventDefault();
  });

});
</script>


