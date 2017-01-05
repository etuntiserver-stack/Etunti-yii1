$(document).ready(function(){

    var loginArr = [];

    if(localStorage.getItem('loginOK'))
    {
	loginArr = JSON.parse(localStorage.getItem('loginOK')).loginOK;
	$('#laatikot').show(370);

    } else {
	$('#loginLomake').show(370);
    }
    
});



    //localStorage.clear();

    var domain = '';
    var tunnus = '';
    var salasana = '';


    if(localStorage.getItem('domain'))
	  domain=localStorage.getItem('domain');
    if(localStorage.getItem('tunnus'))
	  email=localStorage.getItem('tunnus');
    if(localStorage.getItem('salasana'))
	  salasana=localStorage.getItem('salasana');



    // <-- Palvelin
    var server = 'https://etunti.fi/';
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
           url: url+'/login?domain='+domain,
	   type:'POST',
 	   data: $(this).serialize(),
           success: function(data){
		var d = JSON.parse(data);
		
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


   $("body").ready(function(){

       $("#footlinks").html(
	'<div class="row">'+
	'<footer id="footer">'+
	'<div class="navbar navbar-default navbar-fixed-bottom">' +
	'<div class="" id="footer-body">' +
	    '<center>' +
		'<a href="#" id="home"><h2 class="glyphicon glyphicon-home"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="asetukset"><h2 class="glyphicon glyphicon-cog"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="vaihdaTunnus"><h2 class="glyphicon glyphicon-user"></h2></a>&nbsp;&nbsp;&nbsp;' +
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

  $("#asetukset").click(function(){
	window.location.href='asetukset.html';
  });

  $("#vaihdaTunnus").click(function(){
 	localStorage.removeItem('loginOK');
	window.location.href='index.html';
  });

  $("#toteutuneetTunnit").click(function(){
	window.location.href='toteutuneet.html';
  });

});
}



});
