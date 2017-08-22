$(document).ready(function(){

    var loginArr = [];

    if(localStorage.getItem('loginOK'))
    {
	loginArr = JSON.parse(localStorage.getItem('loginOK')).loginOK;
	$('#laatikot').show(370);
	$('#kirjauduOikealla').show(370);
	$('#asiakasNimi').html(loginArr['asiakasNimi']);

    } else {
	$('#loginLomake').show(370);
    }
    
});



    //localStorage.clear();

    var server = 'https://etunti.fi/';
    var domain = '';
    var tunnus = '';
    var salasana = '';
    var loginArr = [];
    var paketti = [];


    if(localStorage.getItem('loginOK'))
    {
	loginArr = JSON.parse(localStorage.getItem('loginOK')).loginOK[0];

	var asiakasID = JSON.parse(localStorage.getItem('loginOK')).loginOK['asiakasID'];
	
	domain = loginArr['domain'];

	$(document).ready(function(){
		if(loginArr['palvelin']){
			$('#palvelin').val(loginArr['palvelin']);
		}
		$('#domain').val(loginArr['domain']);
		$('#inputEmail').val(loginArr['tunnus']);
		$('#inputPassword').val(loginArr['salasana']);
	});

	//console.log(loginArr);
	//console.log('Asiakas: '+asiakasID);

    }

    if(loginArr['palvelin']){
		server = 'https://'+loginArr['palvelin']+'/';
    }

    // <-- Palvelin

    //var server = '../../';
    var url = server+"index.php/dico/asiakkaat";
    var versio = "";
    // Palvelin -->



    // <-- On device Ready
    document.addEventListener("deviceready", onServerReady1, false);
    function onServerReady1() {

	if(device.platform == 'iOS'){
	  $(document).ready(function(){
	    $('.navbar-fixed-top').css({'margin-top':'20px'});
	    $('.powerButton').hide();
	  });
	}

    }
    // On device Ready -->

    function exitFromApp()
    {
       navigator.app.exitApp();
    }





$(document).ready(function(){

  // <-- Login
  $("#form-signin").on('submit', function(e){

	var values = $(this).serializeArray();
	console.log(values);
	var url = 'https://' + values[0]['value']+'/index.php/dico/asiakkaat';
	domain = values[1]['value'];

        $.ajax({
           url: url+'/login?domain='+domain,
	   type:'POST',
 	   data: $(this).serialize(),
           success: function(data){
		var d = JSON.parse(data);
		//return false;
		if(d['loginOK'])
		{
			localStorage.setItem('loginOK', JSON.stringify(d));
			localStorage.setItem('login_paketti', d['loginOK'].paketti);
			window.location.href="index.html";
			console.log(d);
		} else {
			$('#yllaIlmoitus').html('<h3 class="alert alert-danger">Kirjautuminen ei onnistunut. Tarkasta yritystunnus, sähköposti ja salasana</h3>');
		}
    	},
    		error: function (xhr, status, error){
        	console.log(url+'/login?domain='+domain);
    	}
        });
	e.preventDefault();
   });
  //  Login -->



	

$("body").ready(function(){



  $(".to-tilaus").click(function(e){
   if(asiakasID)
   {
	document.addEventListener("deviceready", onInAPPDeviceReady, false);
	event.preventDefault();
	function onInAPPDeviceReady() {
	    var ref = cordova.InAppBrowser.open(encodeURI(server+'index.php/onlinevaraus/index?domain='+domain+'&aid='+asiakasID), '_blank', 'location=yes');

		/*
		ref.addEventListener(
		    'loadstop',
		    function(event) {
		        ref.executeScript({
		            code: "document.getElementById('palvelu').onclick = function() {alert('button was clicked');}"
		        });
		    }
		);
		*/
	}
   }
  });

  $("#home").click(function(){
	window.location.href='index.html';
  });

  $("#asetukset").click(function(){
	window.location.href='asetukset.html';
  });

  $("#vaihdaTunnus").click(function(){
 	localStorage.removeItem('loginOK');
	//localStorage.clear();
	window.location.href='index.html';
  });

  $("#toteutuneetTunnit").click(function(){
	window.location.href='toteutuneet.html';
  });

});




});
