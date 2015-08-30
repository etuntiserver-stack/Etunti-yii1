$(document).ready(function(){


$(".sw").bootstrapSwitch({
	size: "large",
	onColor: "info",
	onText: "Lopetus",
	offText: "Aloitus"
});

$('input[name="tyo"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("tyo_al",1);
  } else {
	row("tyo_lp",3);
  }
	$('.full').css({"opacity" : "0.3"});
});

$('input[name="matka"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("matka_al",2);
  } else {
	row("matka_lp",2);
  }
	$('.full').css({"opacity" : "0.3"});
});

$('input[name="lounas"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("lounas_al",10);
  } else {
	row("lounas_lp",10);
  }
	$('.full').css({"opacity" : "0.3"});
});


function allHide(){
	$("#osoite").hide('slow');
	$('#tyo').hide('slow');
	$('#matka').hide('slow');
	$('#lounas').hide('slow');
}
function allShow(){
	$('#tyo').show('slow');
	$('#matka').show('slow');
	$('#lounas').show('slow');
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



var query = location.href.substring((location.href.indexOf('?')+1), location.href.length);
  if(location.href.indexOf('?') < 0) query = '';
      querysplit = query.split('&');
      query = new Array();
  for(var i = 0; i < querysplit.length; i++)
  {
        var namevalue = querysplit[i].split('=');
        namevalue[1] = namevalue[1].replace(/\+/g, ' ');
        query[namevalue[0]] = unescape(namevalue[1]);
  }

  var DeviceIMEI = query['imei'];
  var my_location = query['location'];
  var nfc = query['nfc'];

  $("#result").append("DeviceIMEI : " + DeviceIMEI + "\n");
  $("#result").append("Location : " + my_location + "\n");



  var domain = "sivex";
  var url = $("#server").val()+"/index.php/api/mob";
  var puh_nro = "0449304851";
  var versio = "0.47";
  var tag = "36073245411209220";
  var imei = DeviceIMEI;



function row(tilanne,st){

   if((tilanne == 'tyo_al') & (st == 1))
   {
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
		tekijan_nimi: "Roman Sizov",
		tid: "38",
		etaisyys: "0",
		status: st,
		tietoja: "testi",
		hyvaksytty: "0",
	};


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: postData,
           success: function(data){
        	console.log(data);
		//var spNew = data.split("//");
		$("#result").append(data+"\n");
		set();
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result").val(xhr.responseText);
    	}
        });

}




// Checker
    set();

    function set() {


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "testi", imei : imei, my_location : my_location },
           success: function(data){
        	//console.log(data);
		var sp = data.split("//");
		$("#result").append(sp+"\n");
		$('.full').css({"opacity" : "1"});

		if((sp[0] == '3') || (sp[0] == '2') || (sp[0] == '10')){
		  $("#osoite").show('slow');
		  allShow();
		  allTilasetHide();
		}
		if(sp[0] == '1')
		{
		  allHide();
		  $("#tyo").show('slow');
		  $("#tyo_kohde").html(sp[1]).show('slow');
		  $('.tyo').bootstrapSwitch('state', true, true);
		} 
		if(sp[0] == '2.1')
		{
		  allHide();
		  $("#matka").show('slow');
		  $("#matka_kohde").html(sp[1]).show('slow');
		  $('.matka').bootstrapSwitch('state', true, true);
		}
		if(sp[0] == '10.1')
		{
		  allHide();
		  $("#lounas").show('slow');
		  $("#lounas_kohde").html(sp[1]).show('slow');
		  $('.lounas').bootstrapSwitch('state', true, true);
		}
		$("#os").val(sp[4]);
		
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	//console.log(xhr.responseText);
		$("#result").val(xhr.responseText);
    	}
        });
    }



$("#os").keyup(function(){

  var thisKey = $(this).val();
  var lengThis = thisKey.length;

  if(lengThis > 2)
  {
	$("#getListFromServer").show('slow');
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "osoitevaihto", imei : imei, my_location : my_location, thisKey : thisKey },
           success: function(data){
        	//console.log(data);
		//$("#result").val(data);
		$("#getListFromServer").html(data);

  		$("#list").change(function(){
			$("#getListFromServer").hide('slow');
			$("#os").val($( "#list option:selected" ).text());
			$("#kohdenID").val($( "#list option:selected" ).val());
		});

		var listSize = $('#list option').size();
		if(listSize > 1)
		{
			$("#list").show();
		} else {
			$("#list").hide();
		}
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	//console.log(xhr.responseText);
		$("#result").val(xhr.responseText);
    	}
        });

  } else {
			$("#list").hide();
  }

});







});
