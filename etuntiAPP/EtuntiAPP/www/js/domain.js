$(document).ready(function(){

  $("#tallennaKieli").click(function(){
	var selected = $("#kieliValiko option:selected").val();
	var selectedFontti = $("#fonttikokoValiko option:selected").val();

	//localStorage.removeItem('etunti_language');

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



    var domain = '';
    var email = '';
    var salasana = '';


    if(localStorage.getItem('domain'))
	  domain=localStorage.getItem('domain');
    if(localStorage.getItem('email'))
	  email=localStorage.getItem('email');
    if(localStorage.getItem('salasana'))
	  salasana=localStorage.getItem('salasana');


    var my_location = '';
    var tag = '000000';
    var etunti_language = 'fi';


    if(localStorage.getItem('etunti_language'))
    etunti_language = localStorage.getItem('etunti_language');


    var server = 'http://etunti.fi';

    document.addEventListener("deviceready", onServerReady, false);
    function onServerReady() {

	if(device.platform == 'iOS'){
		server = 'https://etunti.fi';
	}

    }

  var url = server+"/index.php/api/mob";
  var puh_nro = "";
  var versio = "1.70";


//localStorage.clear();

/* <-- poistettu 24.08.2016 
if( !localStorage.getItem('domain') | !localStorage.getItem('email') | !localStorage.getItem('salasana')  )
{
  document.addEventListener('deviceready', this.readFile, true);
  function readFile() {
        window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, gotFS, fail);
	    function gotFS(fileSystem) {
	        fileSystem.root.getFile("etunti.cfg", null, gotFileEntry, fail);
	    }
	    function gotFileEntry(fileEntry) {
	        fileEntry.file(gotFile, fail);
	    }
	    function gotFile(file){
	        readAsText(file);
	    }	
	    function readAsText(file) {
	        var reader = new FileReader();
	        reader.onloadend = function(evt) {
	            console.log("Read as text");
	            console.log(evt.target.result);



	   		 var spFile = evt.target.result.split("//");
			 document.getElementById('domain').value=spFile[0];
			 document.getElementById('email').value=spFile[1];
			 document.getElementById('salasana').value=spFile[2];

			 localStorage.setItem('domain', spFile[0]);
			 localStorage.setItem('email', spFile[1]);
			 localStorage.setItem('salasana', spFile[2]);

			 window.location.href="index.html";
		
	        };
	        reader.readAsText(file);
	    }
	    function fail(evt) {
	        console.log(evt.target.error.code);
	    }
  }

}
*/




  function exitFromApp()
  {
       navigator.app.exitApp();
  }




$(document).ready(function(){


 	var lang = [];


        $.ajax({
	   async: false,
           url: url+'/lang?dom='+domain,
	   type:'POST',
 	   data: { lang : etunti_language },
           success: function(data){
		var d = JSON.parse(data);
		localStorage.setItem('lang', JSON.stringify(d));

    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	}
        });



 	lang = JSON.parse(localStorage.getItem('lang'));

console.log(lang)


$("#dm").html('<label>Domain</label>' +
		'<input type="text" class="form-control" id="domain" value="'+domain+'">' +
		'<label>'+ lang['tyontekijan_sahkoposti'] +'</label>' +
		'<input type="text" class="form-control" id="email" value="'+email+'">' +
		'<label>'+ lang['tyontekijan_salasana'] +'</label>' +
		'<input type="password" class="form-control" id="salasana" value="'+salasana+'"><br>' +
		'<button class="btn btn-success btn-group-justified aloita" type="button">' +
		'<i class="glyphicon glyphicon-warning-sign"> '+ lang['tallenna'] +'</i></button>');


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


  $(".aloita").click(function(){
	tallennaTunnukset();
  });

  function tallennaTunnukset(){

	localStorage.setItem('domain', $("#domain").val());
	localStorage.setItem('email', $("#email").val());
	localStorage.setItem('salasana', $("#salasana").val());

	window.location.href='index.html';
  }

/*
  function saveFile(){

    document.addEventListener("deviceready", onDeviceReadyFileSave, false);

    function onDeviceReadyFileSave() {
        window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, gotFS, fail);
    }

    function gotFS(fileSystem) {
        fileSystem.root.getFile("etunti.cfg", {create: true, exclusive: false}, gotFileEntry, fail);
    }

    function gotFileEntry(fileEntry) {
        fileEntry.createWriter(gotFileWriter, fail);
    }

    function gotFileWriter(writer) {
        writer.write($("#domain").val() +"//"+$("#email").val() +"//"+$("#salasana").val());
	  window.location.href='index.html';
       	  //set();
 	  //checkviesti(domain);
    }

    function fail(error) {
        console.log(error.code);
    }
  }
*/


    document.addEventListener("deviceready", onDeviceReady, false);
    function onDeviceReady() {


		function showAppVersion() {
		  cordova.getAppVersion(function(version) {
		  document.getElementById('version').innerHTML = version;
		  versio = version;
		  });
		}
		showAppVersion();



	if(device.platform == 'iOS'){

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
	    '</center>' +
	'</div>' +
	'</div>' +
	'</footer>' +
	'</div>');

	painikkeet();

	} else {

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
		'<a href="#" onclick="exitFromApp()"><h2 class="glyphicon glyphicon-new-window"></h2></a>' +
	    '</center>' +
	'</div>' +
	'</div>' +
	'</footer>' +
	'</div>');

	}

	painikkeet();
    }






function painikkeet(){

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
}




});
