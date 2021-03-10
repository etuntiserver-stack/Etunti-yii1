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

<div class="modal-dialog modal-lg">
    <div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	<h2 class="modal-title text-success"><?php echo Yii::t('main', 'Työvuorojen toistuvuus'); ?></h2>
	</div>

<div class="modal-body">
<div class="dialogTable clearfix modal-osio">

<div id="lomake">
<form id="autoinsForm" method="POST">
<input type="hidden" name="asenna">
<input type="hidden" name="valmis" id="valmis" value="false">

<div class="row">
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Alkaen'); ?></label>
	<input type="text" class="form-control datepicker" name="pfrom" value="<?php echo date('Y-m-d'); ?>">
  </div>
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Loppuen'); ?></label>
	<input type="text" class="form-control datepicker" name="pto" id="pto">
  </div>
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Työvuorojen viikkoväli'); ?></label>
	<select class="form-control" name="viikkoja">
	<option value="1">1</option>
	<option value="2">2</option>
	<option value="3">3</option>
	<option value="4">4</option>
	</select>
  </div>
</div>

<div class="row">
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Aloitusaika'); ?></label>
	<input type="text" class="form-control timeVuorot" name="tfrom" id="tfrom">
  </div>
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Lopetusaika'); ?></label>
	<input type="text" class="form-control timeVuorot" name="tto" id="tto">
  </div>
  <div class="col-sm-4">
	 	<label><?php echo Yii::t('main','Työajanmerkintä'); ?></label>
		<?php
        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanmerkinta' ", array('order' => 'select_type'));
		echo '<select name="tyoajanmerkinta" class="form-control">';

		 foreach($tal as $v)
		 {
		   $expl = explode("/",$v->value);
		   $color = (isset($expl[1])) ? $expl[1] : '';
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   echo '<option style="color:'.$color.'" value="'.$v->value.'">'.$value.'</option>';
		 }
		echo '</select>';
        	?>
  </div>
</div>
<br>
<div class="row">
  <div class="col-sm-4"><?php echo $tt; ?></div>

  <div class="col-sm-4">

		<?php 

		$criteria=new CDbCriteria;
		$criteria->order =" yrityksen_nimi!='' DESC,etunimi!='' DESC";
		$criteria->condition =" aktiivinen=1 ";

 		$as = Asiakkaat::model()->findAll($criteria);
		if(isset($as[0]))
		{
			echo '<select name="asiakas" id="asiakas" class="form-control">';
				echo '<option value="">'.Yii::t('main', 'Valitse asiakas').'</option>';
			foreach($as as $a)
			{
				$nm = $a->Fullname;

				echo '<option value="'.$a->id.'">'.$nm.'</option>';
			}

			echo '</select>';
		}
		?>

  </div>

  <div class="col-sm-4"><?php echo $k; ?></div>
</div>
<br>
  <!--
<br>
<div class="row">
  <div class="col-sm-4">

		<?php
        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanlaatu' ", array('order' => 'select_type'));
		echo '<select name="tyoajanlaatu" class="form-control">';

		 foreach($tal as $v)
		 {
		   $expl1 = explode("/",$v->value);
		   $color = (isset($expl1[1])) ? $expl1[1] : '';
		   $value1 = (isset($expl1[0])) ? $expl1[0] : '';
		   echo '<option style="color:'.$color.'" value="'.$v->value.'">'.$value1.'</option>';
		 }
		echo '</select>';
        	?>

  </div>
  <div class="col-sm-4">

  </div>
</div>
  -->

<div class="row">
  <div class="col-sm-6">
    <label><?php echo Yii::t('main', 'Toimenpiteet'); ?></label>
    <textarea class="form-control" rows="6" name="tietoja"></textarea>
  </div>
  <div class="col-sm-6">
    <label><?php echo Yii::t('main', 'Ohjet'); ?></label>

    <textarea class="form-control tietoja" rows="6" id="tietoja"></textarea>
  </div>
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

</form>
</div><!--lomake-->

<div id="tarkistaLista"></div>
<br>

<div id="row">
    <div class="pull-right" id="supersubmit"></div>
    <div  class="pull-right" id="oldBut"><button class="btn btn-success doit"><?php echo Yii::t('main', 'Tarkista tekemäsi työvuorot'); ?></button></div> 
    <div id="newBut"></div>
</div>


</div>
</div> <!-- end modal-body -->


  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>

<script type="text/javascript">
$(document).ready(function(){


  $('#asiakas').change(function(){
	var thisVal = $(this).val();

	  	 $.ajax({
			url: 'getKohdeByAsiakas',
			type:'GET',
			data: { "id" : thisVal },
			  success:function(data){
				data = JSON.parse(data);
			  	console.log(data);
				$('#kohde').html(data);

			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});
  });


  $('.timeVuorot').mask('00:00',{
        placeholder: "__:__"
  });

  $(".sw").bootstrapSwitch({
	size: "mini",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

  var tekijaVal = '';
$("#tekija").change(function(){
  tekijaVal = $( "#tekija option:selected" ).val();
});

  var kohdeVal = '';
$("#kohde").change(function(){
  kohdeVal = $( "#kohde option:selected" ).val();

  $.ajax({
  url:'autoinsert',
  data:{"checktietoja": true, kohdeVal : kohdeVal},
  type:'POST',
  success:function(data){
  	console.log(data);
	$("#tietoja").val(data);
  },
  error:function (xhr, ajaxOptions, thrownError){
        //console.log(xhr.responseText);
  }
  });

});



// Send form by ajax
$('#autoinsForm').on('submit',function(e) {

  var pfrom = $("#pfrom").val();
  var pto = $("#pto").val();
  var tfrom = $("#tfrom").val();
  var tto = $("#tto").val();
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
    if (tfrom  === '') {
        $('#tfrom').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (tto  === '') {
        $('#tto').css({"border" : "2px #f14010 solid"}).focus();
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
  url:'autoinsert',
  data:$(this).serialize(),
  type:'POST',
  success:function(data){

  	console.log(data);

	   if($('#valmis').val() === "true")
	   window.location.reload();

	   $('#oldBut').html('');
	   $('#newBut').html("<button class='btn btn-warning takaisin'><?php echo Yii::t('main', 'Palaa takaisin'); ?></button>");
	   $('#supersubmit').html("<button class='btn btn-primary submitLaheta'><?php echo Yii::t('main', 'LÄHETÄ TYÖVUOROON'); ?></button>");
	   $('#tarkistaLista').html("<textarea class='form-control' rows='14'>"+data+"</textarea>").show('hide');
	   $('#lomake').hide('slow');
	   $('#valmis').val("true");

  },
  error:function (xhr, ajaxOptions, thrownError){
        //console.log(xhr.responseText);
  }
  });

  e.preventDefault(); 
});






	$(document).delegate(".submitLaheta","click",function(){
	   $(this).html('Odota..');
	   $("#autoinsForm").submit();

	   //window.location.reload();
	   //setTimeout(function(){document.location.href = self.document.location;},500);

	});

	$(document).delegate("#newBut","click",function(){

	   $(this).html('');
	   $('#oldBut').html("<button class='btn btn-success doit'><?php echo Yii::t('main', 'Tarkista tekemäsi työvuoroot'); ?></button>");
	   $('#supersubmit').html("");
	   $('#tarkistaLista').hide('slow');
	   $('#lomake').show('slow');
	   $('#valmis').val("false");

	});


	$(document).delegate(".doit","click",function(){
	   $("#autoinsForm").submit();
	});


});
</script>


















