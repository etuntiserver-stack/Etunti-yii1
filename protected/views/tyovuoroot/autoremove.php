<?php

    $tt = '';
    $model=new Tyontekijat;
    $list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'id', 'tekijan_nimi');
    $tt .= '<select name="tekija" id="tekija" class="form-control">';
    $tt .= '<option>'.Yii::t('main', 'Valitse työntekijä').'</option>';
    foreach($list as $key=>$val){
    $tt .= '<option value="'.$key.'">'.$val.'</option>';
    }
    $tt .= '</select>';


    $k = '';
    $model=new Kohteet;
    $list = CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite')), 'id', 'osoite');
    $k .= '<select name="kohde" id="kohde" class="form-control">';
    $k .= '<option>'.Yii::t('main', 'Valitse kohde').'</option>';
    foreach($list as $key=>$val){
    $k .= '<option value="'.$key.'">'.$val.'</option>';
    }
    $k .= '</select>';
?>
<style>
.big-checkbox {width: 30px; height: 30px;}
</style>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	<h2 class="modal-title text-danger"><?php echo Yii::t('main', 'Työvuorojen poistaminen'); ?></h2>
	</div>

<div class="modal-body">
<div class="dialogTable clearfix modal-osio">

<div id="lomake">
<form id="autoinsForm" method="POST">
<input type="hidden" name="asenna">
<input type="hidden" name="valmis" id="valmis" value="false">

<div class="row">
  <div class="col-sm-6">
	<label><?php echo Yii::t('main', 'Alkaen'); ?></label>
	<input type="text" class="form-control datepicker" name="pfrom" value="<?php echo date('Y-m-d'); ?>">
  </div>
  <div class="col-sm-6">
	<label><?php echo Yii::t('main', 'Loppuun'); ?></label>
	<input type="text" class="form-control datepicker" name="pto" id="pto">
  </div>
</div>

<br>
<div class="row">
  <div class="col-sm-6"><?php echo $tt; ?></div>
  <div class="col-sm-6"><?php echo $k; ?></div>
</div>
<br>


<div class="row">
  <div class="col-sm-12 col-sm-offset-1">
  <label><?php echo Yii::t('main', 'Ma'); ?></label>
  <input type="checkbox" class="sw" name="P[1]" id="ma" value="1">

  <label><?php echo Yii::t('main', 'Ti'); ?></label>
  <input type="checkbox" class="sw" name="P[2]" id="ti" value="2">

  <label><?php echo Yii::t('main', 'Ke'); ?></label>
  <input type="checkbox" class="sw" name="P[3]" id="ke" value="3">

  <label><?php echo Yii::t('main', 'To'); ?></label>
  <input type="checkbox" class="sw" name="P[4]" id="to" value="4">

  <label><?php echo Yii::t('main', 'Pe'); ?></label>
  <input type="checkbox" class="sw" name="P[5]" id="pe" value="5">

  <label><?php echo Yii::t('main', 'La'); ?></label>
  <input type="checkbox" class="sw" name="P[6]" id="la" value="6">

  <label><?php echo Yii::t('main', 'Su'); ?></label>
  <input type="checkbox" class="sw" name="P[0]" id="su" value="0">

  </div>
</div>

<div class="row">
  <div class="col-sm-6">
	<label><?php echo Yii::t('main', 'Viikkoja'); ?></label>
	<select class="form-control" name="viikkoja">
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	</select>
  </div>
</div>

</form>
</div><!--lomake-->

<div id="tarkistaLista"></div>
<br>

<div id="row">
    <div class="pull-right" id="supersubmit"></div>
    <div id="oldBut"><button class="btn btn-danger doit"><?php echo Yii::t('main', 'Tarkista valitsemäsi päiviä'); ?></button></div> 
    <div id="newBut"></div>
</div>


</div>
</div> <!-- end modal-body -->


  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>

<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	size: "mini",
	onColor: "danger",
	offColor: "success",
	onText: "Ei",
	offText: "Kyllä"
  });

  var tekijaVal = '';
$("#tekija").change(function(){
  tekijaVal = $( "#tekija option:selected" ).val();
});

  var kohdeVal = '';
$("#kohde").change(function(){
  kohdeVal = $( "#kohde option:selected" ).val();
});


$(".doit").click(function(){
	$("#autoinsForm").submit();
});


// Send form by ajax
$('#autoinsForm').on('submit',function(e) {

  var pfrom = $("#pfrom").val();
  var pto = $("#pto").val();
  var tekija = $("#tekija").find('selected').val();
  var kohde = $("#kohde").find('selected').val();


    if (pfrom  === '') {
        $('#pfrom').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (pto  === '') {
        $('#pto').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (!tekijaVal) {
        $('#tekija').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (!kohdeVal) {
        $('#kohde').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }



  $.ajax({
  url:'autoremove',
  data:$(this).serialize(),
  type:'POST',
  success:function(data){

  	console.log(data);
	palaTakais();

function palaTakais(){

	   $('#oldBut').html('');
	   $('#newBut').html("<button class='btn btn-warning takaisin'><?php echo Yii::t('main', 'Palaa takaisin'); ?></button>");
	   $('#supersubmit').html("<button class='btn btn-danger submit'><?php echo Yii::t('main', 'POISTA TYÖVUOROSTA'); ?></button>");
	   $('#tarkistaLista').html("<textarea class='form-control' rows='14'>"+data+"</textarea>").show('hide');
	   $('#lomake').hide('slow');
	   $('#valmis').val("true");

	$('.submit').click(function(){
	   $("#autoinsForm").submit();
	   setTimeout(function(){document.location.href = "index";},500);
	});

	$('#newBut').click(function(){
	   $(this).html('');
	   $('#oldBut').html("<button class='btn btn-danger doit'><?php echo Yii::t('main', 'Tarkista valitsemäsi päiviä'); ?></button>");
	   $('#supersubmit').html("");
	   $('#tarkistaLista').hide('slow');
	   $('#lomake').show('slow');
	   $('#valmis').val("false");


	$('.doit').click(function(){
	   $("#autoinsForm").submit();
	});

	});

}



	//return false;
  },
  error:function (xhr, ajaxOptions, thrownError){
        //console.log(xhr.responseText);
  }
  });

  e.preventDefault(); 
});



});
</script>


















