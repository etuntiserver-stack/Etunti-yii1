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
		if(d)
		{
			$('#resultLaatiko').html(d);
			reloadSkin();
			reloadDatepicker();
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
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



  }
    


});
