
  var domain = '';
  var email = '';
  var salasana = '';
  var my_location = '';
  var tag = '000000';

  var server = "http://etunti.fi";
  var url = server+"/index.php/api/mob";
  var puh_nro = "";
  var versio = "1.70";




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



/*
  var fordm =	'<label>Domain</label>' +
		'<input type="text" class="form-control" id="domain">' +
		'<label>Työntekijän sähköposti</label>' +
		'<input type="text" class="form-control" id="email">' +
		'<label>Työntekijän salasana</label>' +
		'<input type="password" class="form-control" id="salasana"><br>' +
		'<button class="btn btn-primary btn-group-justified aloita" type="button">' +
		'<i class="glyphicon glyphicon-warning-sign"> Tallenna</i></button>';
  dm.innerHTML += fordm;
*/

	   		 var spFile = evt.target.result.split("//");
		
			 document.getElementById('domain').value=spFile[0];
			 document.getElementById('email').value=spFile[1];
			 document.getElementById('salasana').value=spFile[2];

		
	        };
	        reader.readAsText(file);
	    }
	    function fail(evt) {
	        console.log(evt.target.error.code);
	    }
  }

  function exitFromApp()
  {
       navigator.app.exitApp();
  }




$(document).ready(function(){


$("#dm").html('<label>Domain</label>' +
		'<input type="text" class="form-control" id="domain">' +
		'<label>Työntekijän sähköposti</label>' +
		'<input type="text" class="form-control" id="email">' +
		'<label>Työntekijän salasana</label>' +
		'<input type="password" class="form-control" id="salasana"><br>' +
		'<button class="btn btn-success btn-group-justified aloita" type="button">' +
		'<i class="glyphicon glyphicon-warning-sign"> Tallenna</i></button>');

  $(".aloita").click(function(){
	saveFile();
  });


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
