$(document).ready(function(){

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }


  if(localStorage.getItem('loginOK'))
  {
	var tyyppi = '';
	if(getUrlVars()["tyyppi"])
		tyyppi = getUrlVars()["tyyppi"];

	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;
	sendData['tyyppi'] = tyyppi;

	//console.log(sendData);

        $.ajax({
           url: url+'/tarjoukset?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		if(d['lista'])
		{
			$('#resultLaatiko').html(d['lista']);
			reloadSkin();
			reloadDatepicker();
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });


    $(document).delegate('.avaaPDF', 'click', function() {

	var liite = $(this).attr('liite');
	var ext = $(this).attr('ext');
	sendData['liite'] = liite;
	sendData['ext'] = ext;

        $.ajax({
           url: url+'/tarjoukset?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);
		if(d['link']){
			//$('#myembed').attr('src', d['link']).show();
			saveFileToStorage(d['link'], d['filename'], d['ext']);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

    });

    $(document).delegate('.asia', 'click', function() {

	var asia = $(this).attr('asia');
	var id = $(this).attr('id');
	var code = $(this).attr('code');

	sendData['asia'] = asia;
	sendData['id'] = id;
	sendData['code'] = code;

	var confrm = '';
	if(asia == 'hyvaksy'){
		confrm = 'Haluatko varmaasti hyväksyttä tarjous?';
	}	
	if(asia == 'hylatty'){
		confrm = 'Haluatko varmaasti hylkää tarjous?';
	}

	if(confirm(confrm)){
        $.ajax({
           url: url+'/tarjoukset?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);
		if(d == true)
			window.location.reload();
		else
			alert('Virhe');
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });
	}

    });

    function reloadSkin()
    {
	if( localStorage.getItem('headerSkin') ){
	$('.myBgColors').removeClass(localStorage.getItem('headerSkin'));
	$('.myBgColors').addClass(localStorage.getItem('headerSkin'));
	}
    }
    function reloadDatepicker()
    {
        $( ".datepickerFI" ).datetimepicker({
         format : 'DD.MM.YYYY',
	 locale: 'fi',
        });
    }



/* file opener */
function saveFileToStorage(link, filename, ext){
   document.addEventListener("deviceready", onDeviceReady, notReady());
   function onDeviceReady() {

	var fileTransfer = new FileTransfer();
	var uri = encodeURI(link);

	window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, function (fileSystem) {

		fileTransfer.download(
		    uri,
		    fileSystem.root.toURL() + filename,
		    function(entry) {
		        console.log("download complete: " + entry.fullPath);
			openThisFile(fileSystem.root.toURL() + filename)
		    },
		    function(error) {
		        console.log("download error source " + error.source);
		        console.log("download error target " + error.target);
		        console.log("upload error code" + error.code);
		    },
		    false,
		    {
		        headers: {
		            "Authorization": "Basic dGVzdHVzZXJuYW1lOnRlc3RwYXNzd29yZA=="
		        }
		    }
		);

	});

	function openThisFile(fileForOpener){

		document.addEventListener('deviceready', function () {

		cordova.plugins.fileOpener2.open(
		    fileForOpener,
		    'application/pdf', 
		    { 
		        error : function(e) { 
		            console.log('Error status: ' + e.status + ' - Error message: ' + e.message);
		        },
		        success : function () {
		            console.log('file opened successfully'); 				
		        }
		    }
		);

		}, false);
	}

   }

   function notReady(){
	if(ext == 'pdf')
	$('#myembed').attr('src', link).show();
   }

}
/* file opener */



  }
    


});
