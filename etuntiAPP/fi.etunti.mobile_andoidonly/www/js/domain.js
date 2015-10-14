
  var domain = '';
  var email = '';
  var salasana = '';
  var my_location = '';
  var tag = '000000';

  var server = "http://etunti.fi";
  var url = server+"/index.php/api/mob";
  var puh_nro = "";
  var versio = "0.50";




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

		    document.getElementById('domain').value = spFile[0];
		    document.getElementById('email').value = spFile[1];
		    document.getElementById('salasana').value = spFile[2];

		
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


  $("#dm").html('<label>Domaini</label><input type="text" class="form-control" id="domain" name="srch-term" id="srch-term"><label>Työntekijän sähköposti</label><input type="text" class="form-control" id="email" name="srch-term" id="srch-term"><label>Työntekijän salasana</label><input type="password" class="form-control" id="salasana" name="srch-term" id="srch-term"><br><button class="btn btn-primary btn-group-justified aloita" type="button"><i class="glyphicon glyphicon-warning-sign"> Tallenna</i></button>');

  $("#footlinks").html('<footer id="footer"><div class="navbar navbar-default navbar-fixed-bottom"><div class="container"><div class="" id="footer-body">	<center><a href="#" id="home"><h1 class="glyphicon glyphicon-home"></h1></a>&nbsp;&nbsp;&nbsp;<a href="#" id="viestintaURL"><h1 class="glyphicon glyphicon-envelope form-group"></h1></a>&nbsp;&nbsp;&nbsp;<a href="#" id="tvuoro"><h1 class="glyphicon glyphicon-time form-group"></h1></a>&nbsp;&nbsp;&nbsp;<a href="#" id="tehty"><h1 class="glyphicon glyphicon-chevron-down"></h1></a>&nbsp;&nbsp;&nbsp;<a href="#" id="asetukset"><h1 class="glyphicon glyphicon-cog"></h1></a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="exitFromApp()"><h1 class="glyphicon glyphicon-new-window"></h1></a>    </center></div></div></div></footer>');



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

  $("#asetukset").click(function(){
	window.location.href='asetukset.html';
  });

});
