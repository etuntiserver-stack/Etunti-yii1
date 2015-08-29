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


function allEnable(){
	$('.tyo').bootstrapSwitch('toggleEnabled');
	$('.matka').bootstrapSwitch('toggleEnabled');
	$('.lounas').bootstrapSwitch('toggleEnabled');
	return false;
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
$("#result").append("NFC TAG : " + nfc + "\n");


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
		kohde_kannasta: "Testti Osoite",
		kohdenID: "0",
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
		$("#result").val(data);
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

		if((sp[0] == '3') || (sp[0] == '2') || (sp[0] == '10')){
		//allEnable();
		}
		if(sp[0] == '1')
		{
		  $('.tyo').bootstrapSwitch('state', true, true);
		  $('.matka').bootstrapSwitch('toggleDisabled');
		  $('.lounas').bootstrapSwitch('toggleDisabled');
		} 
		if(sp[0] == '2.1')
		{
		  $('.tyo').bootstrapSwitch('toggleDisabled');
		  $('.matka').bootstrapSwitch('state', true, true);
		  $('.lounas').bootstrapSwitch('toggleDisabled');
		}
		if(sp[0] == '10.1')
		{
		  $('.tyo').bootstrapSwitch('toggleDisabled');
		  $('.matka').bootstrapSwitch('toggleDisabled');
		  $('.lounas').bootstrapSwitch('state', true, true);
		}

		
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	//console.log(xhr.responseText);
		$("#result").val(xhr.responseText);
    	}
        });
    }





/*
    updateChecker();

    function updateChecker() {
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "testi", imei : imei },
           success: function(data){
        	//console.log(data);
		$("#result").val(data);
		
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	//console.log(xhr.responseText);
		$("#result").val(xhr.responseText);
    	}
        });
    }
    	    setInterval(updateChecker, "10000");
*/



});
