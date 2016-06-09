$(document).ready(function(){


var lang = [];

        $.ajax({
	   async: false,
           url: url+'/lang?dom='+domain,
	   type:'POST',
 	   data: { lang : etunti_language },
           success: function(data){
		var d = JSON.parse(data);

		$.each(d, function( index, value ) {
		  lang[index] = value;
		});

    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	}
        });




  $("#odotta").html("<img src='img/icon.png'>");

  setTimeout(tiedot,3000); 


	    document.addEventListener("deviceready", onDeviceReady, false);
	    function onDeviceReady() {
		/*
		cordova.plugins.notification.local.cancelAll(function() {
		    alert("done");
		}, this);
		*/

	        navigator.geolocation.getCurrentPosition(onSuccess, onError);
		navigator.geolocation.watchPosition(onSuccessWatch, onErrorWatch, { timeout: 30000, enableHighAccuracy: false });

		function showAppVersion() {
		  cordova.getAppVersion(function(version) {
		  document.getElementById('version').innerHTML = 'versio: ' +version;
		  versio = version;
		  });
		}
		showAppVersion();

	    }
	    function onSuccess(position) {
	        document.getElementById('location').value = position.coords.latitude + '/' + position.coords.longitude;
	        my_location = position.coords.latitude + '/' + position.coords.longitude;
		sendLocation(my_location);
	    }
	    function onError(error) {
	        alert('code: '    + error.code    + '\n' +
	              'message: ' + error.message + '\n');
	    }


	    function onSuccessWatch(position) {
	        my_location = position.coords.latitude + '/' + position.coords.longitude;
	        document.getElementById('location').value = position.coords.latitude + '/' + position.coords.longitude;
		sendLocation(my_location);
	    }
	    function onErrorWatch(error) {
		/*
	        alert('code: '    + error.code    + '\n' +
	              'message: ' + error.message + '\n');
		*/
	    }



  function checkTAG(){

	 if($("#tagginro").val() != '')
	    tag = $("#tagginro").val();
	 else
	    tag = '000000';

	return tag;
  }

  function tiedot(){


	domain	= $("#domain").val();
	email = $("#email").val();
	salasana = $("#salasana").val();

	if(my_location == '') my_location = $("#location").val();

	if((domain != '') & (email !='') & (salasana != ''))
	{
		checkTAG();
		set();
	 	checkviesti(domain);

/*
var now             = new Date().getTime(),
    _5_sec_from_now = new Date(now + 10*1000);

    cordova.plugins.notification.local.schedule({
      id: 7,
      text: "Muistakaa lopettaa kohde",
      at: _5_sec_from_now,
      led: "FF0000",
      sound: isAndroid ? 'file://sounds/blinblin.mp3' : 'file://sounds/beep.caf'
    });
*/

	} else {
		//setTimeout(function(){document.location.href = "asetukset.html";},500);
		$("#domainBlokki").show();
		return false;
 	}
	
  }


function stateFalse(){
   if($("#os").val() == ''){

	function alertDismissed() {
	    $("#os").addClass("alert alert-danger").focus();
	}
	navigator.notification.alert(
	    'Osoite puuttuu',            // title
	    alertDismissed,         // callback
	    'Huomio!',  // message
	    'OK'                  // buttonName
	);


  	return false;
   } else {
  	return true;
   }

}


	buttons();

function buttons()
{
  $("#butTyo").click(function(){
     var tilanne = $(this).attr("tilanne");
     if(tilanne == 'aloitusTyo')
     {
	     if(stateFalse())
	     {
	  	row("tyo_al",1);
	     }
	     $("#kesto").html('');
     }
     if(tilanne == 'lopetusTyo')
     {
	     row("tyo_lp",3);
     }

  });


  $("#butMatka").click(function(){
     var tilanne = $(this).attr("tilanne");
     if(tilanne == 'aloitusMatka')
     {
	     row("matka_al",2);
	     $("#kesto").html('');
     }
     if(tilanne == 'lopetusMatka')
     {
	     row("matka_lp",2);
     }

  });

  $("#butLounas").click(function(){
     var tilanne = $(this).attr("tilanne");
     if(tilanne == 'aloitusLounas')
     {
	     row("lounas_al",10);
	     $("#kesto").html('');
     }
     if(tilanne == 'lopetusLounas')
     {
	     row("lounas_lp",10);
     }

  });

}


function allHide(){
	$('#tyo').hide(370);
	$('#matka').hide(370);
	$('#lounas').hide(370);
	$("#getTyovuorotToday").hide(370);
}
function allShow(){
	$('#tyo').show(370);
	$('#matka').show(370);
	$('#lounas').show(370);
	$("#alLop").html('<h2>'+ lang['ALOITA'] +'</h2>');
	$("#getTyovuorotToday").show(370);
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

   checkTAG();
   allHide();
   allTilasetHide();


   $("#odotta").html("<h1>ODOTA</h1>").fadeIn(370);

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

   if($("#lyhytviesti").val() !== '')
   viesti = $("#lyhytviesti").val();
   else
   viesti = "xxx";

   	var postData = {
		email : email,
		salasana : salasana,
		domain: domain,
		imei: "ei ole",
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
		viesti: viesti,
		etaisyys: "0",
		status: st,
		tietoja: "",
		hyvaksytty: "0",
		gcm_reg_id : $("#regId").text(),
	};


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: postData,
           success: function(data){
        	console.log(data);

		var sp = data.split("//");
		 if((sp[4] == 'tagnumerror') & (sp[6] == 'update'))
		 {
		   $("#result2").html("<div class='alert alert-danger'><h3>VIRHE!!!</h3>Voit lopettaa osoitessa <b>"+sp[3]+"</b></div>").show();
		   //return false;
		 } else {
		   $("#result2").hide();
		   //$("#result2").html("data: <br>" + data).show();
		 }

		 if((sp[5] !== '') & (sp[6] == 'update'))
		 {

            	   var kestoBlock = '<br><div class="row">' +
		  		  '<div class="col-sm-12">' +
				  '  <div class="alert alert-success">' +
				  '    <div class="card-content black-text">' +
				  '      <center><b>Kesto: ' + sp[5] + '</b></center>' +
				  '    </div>' +
				  '  </div>' +
				  ' </div>' +
				  '</div>';

		   $("#kesto").html(kestoBlock);


setTimeout(function() {
    $('#kesto').fadeOut('slow');
}, 3000);

		 }

		 if(sp[6] == 'update')
		 {
			cordova.plugins.notification.local.cancel(1, function() {
			    //alert("done");
			});
		 }

		$("#muistaLopetta").text('');
		if((sp[4] !== '') & (sp[2] == 'new'))
		{
			setTimer(sp[4],sp[5]);
		}

		// reset 
		$("#tagginro").val('');
		$('#lyhytviesti').val('');
		$('#os').val('');
		$('#kohdenID').val(0);
		$("#os").removeClass("alert alert-danger");
		$('#getListFromServer').val('').hide();

		set();

    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	}
        });

}




// Checker

    function set() {

	getTyovuorotToday(domain);

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "testi", my_location : my_location, tag : tag, email : email, salasana : salasana },
           success: function(data){
        	//console.log(data);
		//$("#result2").html(data).show();
		var sp = data.split("//");

		if(sp[0] == 'eiLoytyTekija')
		{
		  $("#tekija").html("<div class='alert alert-danger'>"+sp[1]+"</div>").show();
		  $("#odotta").fadeOut(370);
		  $("#all").hide();
		  return false;
		} 

		  //$('#tietoja').fadeOut(370);
		  $("#odotta").hide('slow');
		  $("#domainBlokki").hide();
		  $("#result").append(sp+"\n");
		  $("#tekija").html(domain.toUpperCase() + '<br>' + sp[5]);


		if((sp[0] == '3') || (sp[0] == '2') || (sp[0] == '10')){
		  allShow();
		  allTilasetHide();
		} else {

		}


		if(sp[0] == '3'){
		  $("#butTyo").removeClass("btn-warning").addClass("btn-success").attr("tilanne","aloitusTyo").text(lang['TYO']);
		}
		if(sp[0] == '2'){
		  $("#butMatka").removeClass("btn-warning").addClass("btn-success").attr("tilanne","aloitusMatka").text(lang['MATKA']);
		}
		if(sp[0] == '10'){
		  $("#butLounas").removeClass("btn-warning").addClass("btn-success").attr("tilanne","aloitusLounas").text(lang['LOUNAS']);
		}


		if(sp[0] == '1')
		{
		  allHide();
		  $("#tyo").show(370);
		  $("#tyo_kohde").html(sp[1]).show(370);
		  $("#butTyo").removeClass("btn-success").addClass("btn-warning").attr("tilanne","lopetusTyo").text(lang['LOPETA']);
		  $("#alLop").html('<h2>'+lang['TYO']+'</h2>');
		} 
		if(sp[0] == '2.1')
		{
		  allHide();
		  $("#matka").show(370);
		  $("#butMatka").removeClass("btn-success").addClass("btn-warning").attr("tilanne","lopetusMatka").text(lang['LOPETA']);
		  $("#alLop").html('<h2>'+lang['MATKA']+'</h2>');
		}
		if(sp[0] == '10.1')
		{
		  allHide();
		  $("#lounas").show(370);
		  $("#butLounas").removeClass("btn-success").addClass("btn-warning").attr("tilanne","lopetusLounas").text(lang['LOPETA']);
		  $("#alLop").html('<h2>'+lang['LOUNAS']+'</h2>');
		}


		//$("#os").val(sp[4]);
		//$("#kohdenID").val(sp[6]);

		  $("#result").hide();
		  $("#osoite").show(370);

    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);

		  if($("#domain").val() != '')
		     $("#odotta").html("<div class='alert alert-danger'>Domain: <b>" + $("#domain").val() + "</b> on virhellinen,  tai tietokantaa ei löydy</div>").show();
		  else
		     $("#odotta").hide();

		$("#domainBlokki").show();
		$("#result2").html(xhr.responseText).show();
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
	$(this).removeClass("alert alert-danger");

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "osoitevaihto", my_location : my_location, thisKey : thisKey, email : email, salasana : salasana },
           success: function(data){
        	console.log(data);
		//$("#result").val(data);
		$("#getListFromServer").html(
			"<p><div class='row'>" +
			"<div class='col-sm-12'>" +
				 data + 
			"</div>" +
			"</div></p>"
		);

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




/* check messages */
function checkviesti(domain){

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "checkviesti", my_location : my_location, email : email, salasana : salasana },
           success: function(data){
        	//console.log(data);
		//$("#result2").html(data).show();
		var sp = data.split('//');
		if((parseInt(sp[0]) > 0) && (sp[1] !== ''))
		   $("#josOnViesti").html(sp[1]);
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	//console.log(xhr.responseText);
		//$("#result2").html(xhr.responseText).show();
    	}
        });
  
}




/* check tyovuorot */
function getTyovuorotToday(domain){

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "getTyovuorotToday", my_location : my_location, email : email, salasana : salasana },
           success: function(data){
        	//console.log(data);

		$("#getTyovuorotToday").html(
			"<p><div class='row'>" +
			"<div class='col-sm-12'>" +
				 data + 
			"</div>" +
			"</div></p>"
		);

  		$("#list").change(function(){

			$("#os").val($( "#list option:selected" ).text());
			$("#kohdenID").val($( "#list option:selected" ).val());
			$("#getTyovuorotToday").hide();
			return false;
		});
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	//console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	}
        });
  
}



    function isAndroid(){
        return navigator.userAgent.indexOf("Android") > 0;
    }


 function setTimer(val,sekTo){

  if(sekTo > 0)
  {
    var now  = new Date().getTime(),
    _SEK_from_now = new Date(now + sekTo*1000);

    cordova.plugins.notification.local.schedule({
      id: 1,
      text: "Muistakaa lopettaa kohde",
      at: _SEK_from_now,
      led: "FF0000",
      sound: isAndroid ? 'file://sounds/blinblin.mp3' : 'file://sounds/beep.caf'
    });

	var kloSplit = val.split(" ");
	if(kloSplit[1])
	$("#muistaLopetta").html("<br><div class='alert alert-danger'>Muistakaa lopettaa sen <br><h2>klo: " + kloSplit[1] + "</h2></div>");
  }

 }






 function sendLocation(my_location){

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "sendLocation", my_location : my_location, email : email, salasana : salasana },
           success: function(data){
        	console.log("Send Location: " + data);
		//$("#result2").html(data).show();

    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	//console.log(xhr.responseText);
		//$("#result2").html(xhr.responseText).show();
    	}
        });

 }


 function testo(){
	my_location = $("#location").val();
	if(my_location !== '')
	sendLocation(my_location);
 }
 setInterval(testo, "30000");



});
