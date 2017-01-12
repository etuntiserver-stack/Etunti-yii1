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

    var domain = '';
    var tunnus = '';
    var salasana = '';


    if(localStorage.getItem('loginOK'))
    {
	var loginArr = JSON.parse(localStorage.getItem('loginOK')).loginOK[0];
	var asiakasID = JSON.parse(localStorage.getItem('loginOK')).loginOK['asiakasID'];
	
	domain = loginArr['domain'];
    }

    // <-- Palvelin
    var server = 'https://staging.etunti.fi/';
    //var server = '../../';
    var url = server+"index.php/dico/asiakkaat";
    var versio = "";
    // Palvelin -->



    // <-- On device Ready
    document.addEventListener("deviceready", onServerReady1, false);
    function onServerReady1() {


    }
    // On device Ready -->

    function exitFromApp()
    {
       navigator.app.exitApp();
    }





$(document).ready(function(){

  // <-- Login
  $("#form-signin").on('submit', function(e){

        $.ajax({
           url: url+'/login?domain='+$('#domain').val(),
	   type:'POST',
 	   data: $(this).serialize(),
           success: function(data){
		var d = JSON.parse(data);
		//console.log(d)
		//return false;
		if(d['loginOK'])
		{
			localStorage.setItem('loginOK', JSON.stringify(d));
			window.location.href="index.html";
			console.log(data);
		} else {
			$('#yllaIlmoitus').html('<h3 class="alert alert-danger">Kirjautuminen ei onnistunut. Tarkasta yritystunnus, sähköposti ja salasana</h3>');
		}
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	}
        });
	e.preventDefault();
   });
  //  Login -->



	

painikkeet();



function painikkeet(){

$("body").ready(function(){

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
}



});
