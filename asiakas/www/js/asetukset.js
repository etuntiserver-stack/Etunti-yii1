$(document).ready(function(){


	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;

	//console.log(sendData);

        $.ajax({
           url: url+'/omat?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		//console.log(data);
		if(d['content'])
		{
			$('#omatAsetukset').html(d['content']);
		}

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });


    $(document).delegate('.getExcel', 'click', function() {

	sendData['asiakasID'] = asiakasID;
	sendData['getExcel'] = asiakasID;

        $.ajax({
           url: url+'/omat?domain='+domain,
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

    $(document).delegate('.getPDF', 'click', function() {

	sendData['asiakasID'] = asiakasID;
	sendData['getPDF'] = asiakasID;

        $.ajax({
           url: url+'/omat?domain='+domain,
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
			openThisFile(fileSystem.root.toURL() + filename, ext)
		    },
		    function(error) {
		        console.log("download error source " + error.source);
		        console.log("download error target " + error.target);
		        alert("upload error code" + error.code);
		    },
		    false,
		    {
		        headers: {
		            "Authorization": "Basic dGVzdHVzZXJuYW1lOnRlc3RwYXNzd29yZA=="
		        }
		    }
		);

	});

	function openThisFile(fileForOpener, ext){

		document.addEventListener('deviceready', function () {
		if( ext == 'pdf' ){
			var application = 'application/pdf';
		}
		if( ext == 'xls' ){
			var application = 'application/xls';
		}
		cordova.plugins.fileOpener2.open(
		    fileForOpener,
		    application, 
		    { 
		        error : function(e) { 
			    if( ext == 'xls' ){

				//return false;
			    }
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
	//not ready ei toimi window.location.href=link;
   }

}
/* file opener */



});
