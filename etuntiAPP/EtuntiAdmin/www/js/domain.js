$(document).ready(function(){

  $("#tallennaKieli").click(function(){
	var selected = $("#kieliValiko option:selected").val();

	localStorage.removeItem('etunti_language');
	localStorage.setItem('etunti_language', selected);
	location.reload(true);
  });

    if(localStorage.getItem('etunti_language'))
	$("#kieliValiko option[value=" + localStorage.getItem('etunti_language') + "]").prop("selected",true);


});

    var domain = '';
    var tunnus = '';
    var salasana = '';
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




  document.addEventListener('deviceready', this.readFile, true);
  function readFile() {
        window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, gotFS, fail);
	    function gotFS(fileSystem) {
	        fileSystem.root.getFile("etunti_admin.cfg", null, gotFileEntry, fail);
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
			 document.getElementById('tunnus').value=spFile[1];
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



$("#dm").html('<label>Domain</label>' +
		'<input type="text" class="form-control" id="domain">' +
		'<label>'+ lang['admin_login'] +'</label>' +
		'<input type="text" class="form-control" id="tunnus">' +
		'<label>'+ lang['admin_password'] +'</label>' +
		'<input type="password" class="form-control" id="salasana"><br>' +
		'<button class="btn btn-success btn-group-justified aloita" type="button">' +
		'<i class="glyphicon glyphicon-warning-sign"> '+ lang['tallenna'] +'</i></button>');



  /* Asetukset */
  $('#Valitse_kieli').text(lang['Valitse_kieli']);
  $('#Asetukset').text(lang['Asetukset']);
  $('#tallennaKieli').text(lang['tallennaKieli']);

  /*  Kamera */
  $('#LangKuvienLahettaminen').text(lang['LangKuvienLahettaminen']);
  $('#fromCamera').text(lang['fromCamera']);
  $('#fromLibrary').text(lang['fromLibrary']);
  $('#fromAlbum').text(lang['fromAlbum']);


  $(".aloita").click(function(){
	saveFile();
  });

  function saveFile(){

    document.addEventListener("deviceready", onDeviceReadyFileSave, false);

    function onDeviceReadyFileSave() {
        window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, gotFS, fail);
    }

    function gotFS(fileSystem) {
        fileSystem.root.getFile("etunti_admin.cfg", {create: true, exclusive: false}, gotFileEntry, fail);
    }

    function gotFileEntry(fileEntry) {
        fileEntry.createWriter(gotFileWriter, fail);
    }

    function gotFileWriter(writer) {
        writer.write($("#domain").val() +"//"+$("#tunnus").val() +"//"+$("#salasana").val());
	  window.location.href='index.html';
       	  //set();
 	  //checkviesti(domain);
    }

    function fail(error) {
        console.log(error.code);
    }
  }







});
