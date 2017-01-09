$(document).ready(function(){


  if(localStorage.getItem('loginOK'))
  {
	loginArr['asiakasID'] = asiakasID;
	//console.log(loginArr);

        $.ajax({
           url: url+'/toteutuneet?domain='+domain,
	   type:'POST',
 	   data: loginArr,
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
