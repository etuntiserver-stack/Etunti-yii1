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
		console.log(data);
		if(d['content'])
		{
			$('#omatAsetukset').html(d['content']);
		}

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

});
