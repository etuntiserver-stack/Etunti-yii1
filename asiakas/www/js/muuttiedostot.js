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
           url: url+'/muuttiedostot?domain='+domain,
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

	$(this).find('.bg-warning').removeClass('bg-warning').addClass('bg-success');
	var liite = $(this).attr('liite');
	var ext = $(this).attr('ext');
	sendData['liite'] = liite;
	sendData['ext'] = ext;

        $.ajax({
           url: url+'/muuttiedostot?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);
		if(d['link']){
			//$('#myiframe').attr('src', d['link']).show();
			saveFileToStorage(d['link'], d['filename'], d['ext']);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

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
		var open = cordova.plugins.disusered.open;

		function success() {
		  console.log('Success');
		}

		function error(code) {
		  if (code === 1) {
		    console.log('No file handler found');
		  } else {
		    alert('Undefined error: '+ fileForOpener);
		  }
		}

		open(fileForOpener, success, error);
	}

   }

   function notReady(){
	if(ext == 'pdf'){
		$('#myembed').attr('src', link).show();
	} else {
		$('#myiframe').attr('src', link).show();
	}
   }

}
/* file opener */


  }
    


});
