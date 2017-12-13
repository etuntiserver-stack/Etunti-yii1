$(document).ready(function(){

/* Alertin esimerkki IOS varten
setTimeout(function() {
    // do your thing here!
}, 0);
*/


  $("#tallennaKieli").click(function(){
	var selected = $("#kieliValiko option:selected").val();
	var selectedFontti = $("#fonttikokoValiko option:selected").val();

	localStorage.setItem('etunti_language', selected);
	localStorage.setItem('etunti_fonttikoko', selectedFontti);
	location.reload(true);
  });

    if(localStorage.getItem('etunti_language'))
	$("#kieliValiko option[value=" + localStorage.getItem('etunti_language') + "]").prop("selected",true);

    if(localStorage.getItem('etunti_fonttikoko'))
    {
	$("#fonttikokoValiko option[value=" + localStorage.getItem('etunti_fonttikoko') + "]").prop("selected",true);
        $('body').css({'font-size': localStorage.getItem('etunti_fonttikoko')+'px'});
    }

});

    //localStorage.clear();

    var domain = '';
    var email = '';
    var salasana = '';
    var developer = false;


    if(localStorage.getItem('domain'))
	  domain=localStorage.getItem('domain');
    if(localStorage.getItem('email'))
	  email=localStorage.getItem('email');
    if(localStorage.getItem('salasana'))
	  salasana=localStorage.getItem('salasana');


    var my_location = '';
    var platform = '';
    var tag = '000000';
    var etunti_language = 'fi';


    if(localStorage.getItem('etunti_language'))
    etunti_language = localStorage.getItem('etunti_language');

    // <-- Palvelin
    if(localStorage.getItem('server'))
    {
	if( localStorage.getItem('server') == 'staging.etunti.fi' )
    		var server = 'https://staging.etunti.fi';
	else if( localStorage.getItem('server') == 'dev.etunti.fi' )
    		var server = 'https://dev.etunti.fi';
	else
    		var server = 'https://'+localStorage.getItem('server');
    } else {
    	var server = 'https://app.etunti.fi';
    }

    localStorage.removeItem('platform');
    // <-- On device Ready
    document.addEventListener("deviceready", onServerReady1, false);
    function onServerReady1() {


	// <-- Test Alert
	/*
	function alertDismissed() {
	    // do something
	}
	navigator.notification.alert(
	    'Kuva lähetetty onnistuneesti',
	    alertDismissed,
	    'Kiitos!',
	    'OK'
	);
	*/
	// Test Alert -->

	if(device.platform == 'iOS'){

	    if(localStorage.getItem('server'))
	    	server = 'https://'+localStorage.getItem('server');
	    else
	    	server = 'https://app.etunti.fi';

	}

	// <-- Tallenna platform jos ei ole tallessa
        if(!localStorage.getItem('platform'))
		localStorage.setItem('platform', device.platform);
	// Tallenna platform -->

    }
    // On device Ready -->

    if(developer == true){
    	server = '../../..';
    }

    var url = server+"/index.php/api/mob";
    var puh_nro = "";
    var versio = "";
    // Palvelin -->





    // <-- Send my location
    function sendMyLocation(my_location){

	if((my_location !=='') && (email !=='') && (salasana !=='') && (url !=='') && (domain !==''))
	{
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "sendLocation", my_location : my_location, email : email, salasana : salasana },
           success: function(data){
        	//console.log("Send Location: " + data);
		$("#resultLahetysta").val(data).show();

    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	//console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	}
        });
	}

    }
    // Send my location -->



    // <-- On device Reay
    document.addEventListener("deviceready", onServerReady2, false);
    function onServerReady2() {


	if(device.platform == 'Android'){
	// <-- GET FCM Token
	FCMPlugin.getToken(
	  function(token){

        	$.ajax({
	           url: url+'/paivita_tiedot?dom='+domain,
		   type:'POST',
	 	   data: { email : email, salasana : salasana, token : token },
	           success: function(data){
			var d = JSON.parse(data);
			//alert(d)
	    	},
	    		error:function (xhr, ajaxOptions, thrownError){
	        	console.log(xhr.responseText);
	    	}
	        });

	  },
	  function(err){
	    console.log('error retrieving token: ' + err);
	  }
	);
	//    GET FCM Token -->


	// <-- GET FCM message
	FCMPlugin.onNotification(
	  function(data){
	    if(data.wasTapped){
	      //Notification was received on device tray and tapped by the user.
	        //alert( JSON.stringify(data) );
		//data = JSON.stringify(data);

		function alertDismissed() {
		    // do something
		}
		navigator.notification.alert(
		    data['Viesti']+' \nOlen katsonut',
		    alertDismissed,
		    'Viesti',
		    'OK'
		);

	    }else{
	      //Notification was received in foreground. Maybe the user needs to be notified.
		//data = JSON.stringify(data);
		function alertDismissed() {
		    // do something
		}
		navigator.notification.alert(
		    data['Viesti'],
		    alertDismissed,
		    'Viesti',
		    'OK'
		);
	    }
	  },
	  function(msg){
	    console.log('onNotification callback successfully registered: ' + msg);
	  },
	  function(err){
	    console.log('Error registering onNotification callback: ' + err);
	  }
	);
	//    GET FCM message -->
	}


	function showAppVersion() {
	    cordova.getAppVersion(function(version) {
	     if(device.platform !== 'iOS'){
		  document.getElementById('versioBlock').style.display="block";
		  document.getElementById('version').innerHTML = version;
		  versio = version;
	     }
	    });
	}
	showAppVersion();

	//alert(device.platform)
	if(device.platform == 'Android')
	document.getElementById('exitPainike').innerHTML = '<h2 class="glyphicon glyphicon-new-window"></h2>';


        // <-- Geolocation
	//alert("navigator.geolocation works well");
	navigator.geolocation.getCurrentPosition(onSuccessLocation, onErrorLocation);

	var options;
	options = {
	    //maximumAge: 60000,
	    timeout: 30000,
	    enableHighAccuracy: true
	};
	var watchID = navigator.geolocation.watchPosition(onSuccessWatch, onErrorWatch, options);

    }
    // On device Reay -->


	function onSuccessLocation(position) {
	        document.getElementById('location').value = position.coords.latitude + '/' + position.coords.longitude;
	        my_location = position.coords.latitude + '/' + position.coords.longitude;
		//alert(my_location);
	}
	function onErrorLocation(error) {
	
	    var ilmoitus = 'code: '    + error.code    + '\n' +
	          		'message: ' + error.message + '. GPS location ongelma \n';

	    	//document.getElementById('result').style.display="block";
	    	//document.getElementById('result').value = ilmoitus;
	}

	function onSuccessWatch(position) {
	    	document.getElementById('location').value = position.coords.latitude + '/' + position.coords.longitude;
	        my_location = position.coords.latitude + '/' + position.coords.longitude;
		sendMyLocation(my_location);
		//alert(my_location);
	}

	function onErrorWatch(error) {
	
	    var ilmoitus = 'code: '    + error.code    + '\n' +
	          		'message: ' + error.message + '. GPS watch ongelma \n';

	    	//document.getElementById('result').style.display="block";
	    	//document.getElementById('result').value = ilmoitus;
	}






  function exitFromApp()
  {
       navigator.app.exitApp();
  }





$(document).ready(function(){

	if(server == 'https://staging.etunti.fi'){
		$('#server').html('<h1 class="text-danger">STAGING</h1>').show();
	}

 	var lang = [];


        $.ajax({
	   async: false,
           url: url+'/lang?dom='+domain,
	   type:'POST',
 	   data: { lang : etunti_language },
           success: function(data){
		var d = JSON.parse(data);
		//var d = data;
		//console.log(d);
		localStorage.setItem('lang', JSON.stringify(d));

    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	}
        });



 	lang = JSON.parse(localStorage.getItem('lang'));

	//console.log(lang)


$("body").ready(function(){

  $("#dm").html('<label>'+ lang['domain'] +'</label>' +
		'<input type="text" class="form-control" id="domain" value="'+domain+'">' +
		'<label>'+ lang['tyontekijan_sahkoposti'] +'</label>' +
		'<input type="text" class="form-control" id="email" value="'+email+'">' +
		'<label>'+ lang['tyontekijan_salasana'] +'</label>' +
		'<input type="password" class="form-control" id="salasana" value="'+salasana+'">' +
		'<br>' +
		'<button class="btn btn-success btn-group-justified aloita" type="button">' +
		'<i class="glyphicon glyphicon-warning-sign"> '+ lang['tallenna'] +'</i></button>');

  $(".aloita").click(function(){
	tallennaTunnukset();
  });

  $("#tallennaServer").click(function(){
	localStorage.setItem('server', $("#palvelin").val());
	window.location.href='index.html';
  });

});



  /* Index */
  $('#butTyo').text(lang['TYO']);
  $('#butMatka').text(lang['MATKA']);
  $('#butLounas').text(lang['LOUNAS']);
  $('#os').attr('placeholder', lang['osoite']);
  $('#lyhytviesti').attr('placeholder', lang['lyhyt_viesti']);

  /* Viestinta */
  $('#LangViestinta').text(lang['LangViestinta']);
  $('#olenEksynyt').text(lang['olenEksynyt']);
  $('#LangUusiViesti').text(lang['LangUusiViesti']);
  $('#LangViesti').text(lang['LangViesti']);
  $('.lahetaToimistoon').text(lang['lahetaToimistoon']);

  /* Asetukset */
  $('#Valitse_kieli').text(lang['Valitse_kieli']);
  $('#Valitse_fonttikoko').text(lang['Valitse_fonttikoko']);
  $('#paaAsetukset').text(lang['paaAsetukset']);
  $('#MuutAsetukset').text(lang['MuutAsetukset']);
  $('#tallennaKieli').text(lang['tallennaKieli']);

  /*  Kamera */
  $('#LangKuvienLahettaminen').text(lang['LangKuvienLahettaminen']);
  $('#fromCamera').text(lang['fromCamera']);
  $('#fromLibrary').text(lang['fromLibrary']);
  $('#fromAlbum').text(lang['fromAlbum']);


  function tallennaTunnukset(){

	localStorage.setItem('domain', $("#domain").val());
	localStorage.setItem('email', $("#email").val());
	localStorage.setItem('salasana', $("#salasana").val());

	window.location.href='index.html';
  }

  if(localStorage.getItem('server'))
  {
    $("body").ready(function(){
	$('#palvelin').val(localStorage.getItem('server'));
    });	
  }


   $("body").ready(function(){

       $("#footlinks").html(
	'<div class="row">'+
	'<footer id="footer">'+
	'<div class="navbar navbar-default navbar-fixed-bottom">' +
	'<div class="" id="footer-body">' +
	    '<center>' +
		'<a href="#" id="home"><h2 class="glyphicon glyphicon-home"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="viestintaURL"><h2 class="glyphicon glyphicon-envelope form-group"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="tvuoro"><h2 class="glyphicon glyphicon-time form-group"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="tehty"><h2 class="glyphicon glyphicon-chevron-down"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="asetukset"><h2 class="glyphicon glyphicon-cog"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="camera"><h2 class="glyphicon glyphicon-camera"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="exitPainike" onclick="exitFromApp()"></a>' +
	    '</center>' +
	'</div>' +
	'</div>' +
	'</footer>' +
	'</div>');

   });

	

	painikkeet();



function painikkeet(){

$("body").ready(function(){

  $("#home").click(function(){
	window.location.href='index.html';
  });

  $("#viestintaURL").click(function(){
	window.location.href='viestinta.html';
  });

  $("#tvuoro").click(function(){
	window.location.href='tvuoro.html';
  });

  $("#tehty").click(function(){
	window.location.href='tehty.html';
  });

  $("#camera").click(function(){
	window.location.href='camera.html';
  });

  $("#asetukset").click(function(){
	window.location.href='asetukset.html';
  });

});
}





});
