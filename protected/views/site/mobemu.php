<?php


?>



  <div class="row">
    <div class="col-lg-3 col-sm-offset-4 full">
     <br>

     <div id="tekija"></div>

     <hr>

	    <?php
	    $model=new Mobile;
	    $list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'imei', 'tekijan_nimi');
	
	    echo '<select class="form-control tekija">';
	    foreach($list as $key=>$val){
	    echo '<option value="'.$key.'">'.$val.'</option>';
	    }
	    echo '</select>';
	   ?>


	    <BR><BR>

	   <div id="odotta"></div>

	    <div id="osoite" style="display:none">
		<input type="text" class="form-control input-lg" id="os" placeholder="osoite">
		<div id="getListFromServer"></div>
		<input type="hidden" id="kohdenID" value="0">
	    </div>
	    <br>
	    <center>
     	      <div id="tyo" style="display:none">
		<h4>TYÖ</h4>
		<h4 id="tyo_kohde" style="display:none"></h4>
		<input type="checkbox" name="tyo" class="sw tyo">
	      </div>
     	      <div id="matka" style="display:none">
		<h4>MATKA</h4>
		<h4 id="matka_kohde" style="display:none"></h4>
		<input type="checkbox" name="matka" class="sw matka">
	      </div>
     	      <div id="lounas" style="display:none">
		<h4>LOUNASTAUKO</h4> 
		<h4 id="lounas_kohde" style="display:none"></h4>
		<input type="checkbox" name="lounas" class="sw lounas">
	      </div>
	    </center>

	    <BR><BR>



	    <!--<textarea cols="100" rows="6" class="form-control" id="result2" style="display:none"></textarea>-->
	    <div id="result2" style="display:none"></div>
    </div>
  </div>



	<input type="hidden" id="server" value="<?php echo Yii::app()->getBaseUrl(true); ?>">

 


<script type="text/javascript">
$(document).ready(function(){

  var domain = '<?php echo Yii::app()->user->domain; ?>';
  var imei = '';
  var my_location = '';
  var tag = '000000';

  var server = location.protocol + "//" + location.host + '/index.php/';
  var url = server+"api/mob";
  var puh_nro = "";
  var versio = "0.50";

  $(".tekija").change(function(){
	allHide();
	allTilasetHide();
     	imei = $(this).val();
     	set();
  });

  set();


$(".sw").bootstrapSwitch({
	size: "large",
	onColor: "warning",
	offColor: "success",
	onText: "Lopetus",
	offText: "Aloitus"
});


function stateFalse(){
   if($("#os").val() == ''){
	alert("Osoite puutuu!");
	$('.tyo').bootstrapSwitch('state', false, true);
  	return false;
   }
}

$('input[name="tyo"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("tyo_al",1);
  } else {
	row("tyo_lp",3);
  }
});

$('input[name="matka"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("matka_al",2);
  } else {
	row("matka_lp",2);
  }
});

$('input[name="lounas"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("lounas_al",10);
  } else {
	row("lounas_lp",10);
  }
});


function allHide(){
	$("#osoite").hide(370);
	$('#tyo').hide(370);
	$('#matka').hide(370);
	$('#lounas').hide(370);
}
function allShow(){
	$('#tyo').show(370);
	$('#matka').show(370);
	$('#lounas').show(370);
}
function allTilasetHide(){
	$("#tyo_kohde").hide();
	$("#matka_kohde").hide();
	$("#lounas_kohde").hide();
}


function curDateTime(){

  	var date = new Date();
	var year = date.getFullYear();
	var month = date.getMonth();
	month = month < 10 ? "0" + (month+1) : month+1;
	var day = date.getDate();
	day = day < 10 ? "0" + (day) : day;
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getSeconds();

	return (day + "." + month + "." + year + " " + hours + ":" + minutes + ":" + seconds);
}


function row(tilanne,st){

   allHide();
   allTilasetHide();

   $("#odotta").html("<h1>ODOTA</h1>").fadeIn(370);

   if((tilanne == 'tyo_al') & (st == 1))
   {
 	stateFalse();
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'tyo_lp') & (st == 3))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }
   if((tilanne == 'matka_al') & (st == 2))
   {
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'matka_lp') & (st == 2))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }
   if((tilanne == 'lounas_al') & (st == 10))
   {
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'lounas_lp') & (st == 10))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }


   	var postData = {
		domain: domain,
		imei: imei,
		asiakas_num: versio+"_"+tag,
		puh_numero: puh_nro,
		bluetooth_name: "0",
		sim_serial_number: "0",
		subscriber_id: "0",
		my_location: my_location,
		osoite: "0",
		kohde_kannasta: $("#os").val(),
		kohdenID: $("#kohdenID").val(),
		aloitan: al,
		loppui: lp,
		viesti: "xxx",
		etaisyys: "0",
		status: st,
		tietoja: "",
		hyvaksytty: "0",
	};


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: postData,
           success: function(data){
        	console.log(data);

		var sp = data.split("//");
		 if(sp[4] === 'tagnumerror')
		 {
		   $("#result2").html("<div class='alert alert-danger'><h3>VIRHE!!!</h3>Voit lopettaa osoitessa <b>"+sp[3]+"</b></div>").show();
		   //return false;
		 } else {
		   $("#result2").hide();
		 }

		set();
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	}
        });

}



   function set() {


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "testi", imei : imei, my_location : my_location, tag : tag },
           success: function(data){
        	console.log(data);
		//$("#result2").html(data).show();
		var sp = data.split("//");

		if(sp[0] == 'imeiError')
		{
		  //$("#result2").html("<h2>"+sp[1]+" "+sp[2]+"</h2>").show();
		  $("#footer").show(370);
		  return false;
		} 

		$('#tietoja').hide();
		$("#odotta").fadeOut(370);
		$("#footer").show(370);
		$("#result").append(sp+"\n");
		//$('.full').css({"opacity" : "1"});
		$("#domainBlokki").hide();
		$("#tekija").html("<h3>"+domain+", "+sp[5]+"</h3>");

		if((sp[0] == '3') || (sp[0] == '2') || (sp[0] == '10')){
		  $("#osoite").show(370);
		  allShow();
		  allTilasetHide();
		}
		if(sp[0] == '1')
		{
		  allHide();
		  $("#tyo").show(370);
		  $("#tyo_kohde").html(sp[1]).show(370);
		  $('.tyo').bootstrapSwitch('state', true, true);
		} 
		if(sp[0] == '2.1')
		{
		  allHide();
		  $("#matka").show(370);
		  //$("#matka_kohde").html(sp[1]).show(370);
		  $('.matka').bootstrapSwitch('state', true, true);
		}
		if(sp[0] == '10.1')
		{
		  allHide();
		  $("#lounas").show(370);
		  //$("#lounas_kohde").html(sp[1]).show(370);
		  $('.lounas').bootstrapSwitch('state', true, true);


		}
		$("#os").val(sp[4]);
		$("#kohdenID").val(sp[6]);

		$("#result").hide();
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);

		  if($("#domain").val() != '')
		     $("#odotta").html("<div class='alert alert-danger'>Domain: <b>" + $("#domain").val() + "</b> on virhellinen,  tai tietokantaa ei löydy</div>").show();
		  else
		     $("#odotta").hide();

		$("#domainBlokki").show();
		//$("#result2").val(xhr.responseText).show();
		//$("#domain").addClass("btn btn-danger");
    	}
        });
    }



$("#os").keyup(function(){

  var thisKey = $(this).val();
  var lengThis = thisKey.length;

  if(lengThis > 0)
  {
	$("#getListFromServer").show(370);
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "osoitevaihto", imei : imei, my_location : my_location, thisKey : thisKey },
           success: function(data){
        	console.log(data);
		//$("#result").val(data);
		$("#getListFromServer").html(data);

  		$("#list").change(function(){

			$("#getListFromServer").hide(370);
			$("#os").val($( "#list option:selected" ).text());
			$("#kohdenID").val($( "#list option:selected" ).val());
		});

		var listSize = $('#list option').size();

			$("#valitseOsoite").text("Löyty: "+(listSize-1)+" kohteita");

		if(listSize > 1)
		{
			$("#list").show();
		} else {
			$("#list").hide();
		}

		$("#result").hide();
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").val(xhr.responseText).show();
    	}
        });

  } else {
			$("#list").hide();
  }

});



});
</script>




</html>

