$(document).ready(function(){


  if(localStorage.getItem('loginOK'))
  {

	console.log(loginArr);
        $.ajax({
           url: url+'/toteutuneet?domain='+domain,
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
