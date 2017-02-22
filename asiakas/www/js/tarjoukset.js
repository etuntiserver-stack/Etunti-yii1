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
	sendData['liite'] = liite;

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

		if((d['liite'] !== '') && (d['nimike'] !== ''))
		{

			var myBase64 = d['liite'];
			var contentType = "application/pdf";
			// if cordova.file is not available use instead :
			// var folderpath = "file:///storage/emulated/0/";
			var folderpath = cordova.file.externalRootDirectory;
			var filename = "etunti_" + d['nimike'] + ".pdf";
			savebase64AsPDF(folderpath,filename,myBase64,contentType);

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





function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

      var blob = new Blob(byteArrays, {type: contentType});
      return blob;
}

function savebase64AsPDF(folderpath,filename,content,contentType){
    // Convert the base64 string in a Blob
    var DataBlob = b64toBlob(content,contentType);
    
    console.log("Starting to write the file :3");
    
    window.resolveLocalFileSystemURL(folderpath, function(dir) {
        console.log("Access to the directory granted succesfully");
		dir.getFile(filename, {create:true}, function(file) {
            console.log("File created succesfully.");
            file.createWriter(function(fileWriter) {
                console.log("Writing content to file");
                fileWriter.write(DataBlob);

		//alert(folderpath + filename)
        	//window.open(folderpath + filename, '_blank');



		cordova.plugins.fileOpener2.open(
		    '/sdcard/'+ filename,
		    'application/pdf', 
		    { 
		        error : function(e) { 
		            console.log('Error status: ' + e.status + ' - Error message: ' + e.message);
		        },
		        success : function () {
		            console.log('file opened successfully');    
            

				/*// <-- remove 
				window.resolveLocalFileSystemURL(folderpath, function(dir) {
					dir.getFile(filename, {create:false}, function(fileEntry) {
				              fileEntry.remove(function(){
				                  // The file has been removed succesfully
				              },function(error){
			                  // Error deleting the file
				              },function(){
				                 // The file doesn't exist
				              });
					});
				});
				//     remove -->*/

		        }
		    }
		);







            }, function(){
                alert('Unable to save file in path '+ folderpath);
            });
		});
    });
}



  }
    


});
