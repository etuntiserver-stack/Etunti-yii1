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
		if(d['lista'])
		{
			//$('#resultLaatiko').html(d['lista']);
			//reloadSkin();
			//reloadDatepicker();
		}

		if((d['liite'] !== '') && (d['nimike'] !== ''))
		{
			console.log(d['liite']);
			document.addEventListener("deviceready", onInAPPDeviceReady, false);
			function onInAPPDeviceReady() {
				cordova.InAppBrowser.open(d['liite'], '_blank', 'location=no'); 
				//'https://docs.google.com/gview?embedded=true&url=' + 
			}

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



  }
    


});
