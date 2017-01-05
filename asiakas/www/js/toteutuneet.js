$(document).ready(function(){


  if(localStorage.getItem('loginOK'))
  {
	var loginArr = JSON.parse(localStorage.getItem('loginOK')).loginOK[0];
	var asiakasID = JSON.parse(localStorage.getItem('loginOK')).loginOK['asiakasID'];
	console.log(asiakasID);


        $.ajax({
           url: url+'/toteutuneet?domain='+loginArr['domain'],
	   type:'POST',
 	   data: {loginArr, asiakasID : asiakasID},
           success: function(data){
		var d = JSON.parse(data);
		if(d)
		{
			$('#resultLaatiko').html(d);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });


  }
    
});
