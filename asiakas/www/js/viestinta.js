$(document).ready(function(){


  if(localStorage.getItem('loginOK'))
  {
	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;

	//console.log(sendData);

        $.ajax({
           url: url+'/viestinta?domain='+domain,
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

   }



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

});
