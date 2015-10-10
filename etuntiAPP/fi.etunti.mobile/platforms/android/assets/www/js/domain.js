
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
