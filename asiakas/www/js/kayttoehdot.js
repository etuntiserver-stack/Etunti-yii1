$(document).ready(function(){


  if(localStorage.getItem('loginOK'))
  {

	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;
	sendData['getkayttoehdot'] = true;

	//console.log(sendData);

        $.ajax({
           url: url+'/kayttoehdot?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		//console.log(data);
		if(d['ok'])
		{
			$('#resultLaatiko').html(d['ok']);
		}

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });


    $(document).delegate('#hyvaksyn_kayttoehdot', 'change', function(e) {
        if($(this).is(":checked")) {

	var sendData_hyvaksyn = loginArr;
	sendData_hyvaksyn['asiakasID'] = asiakasID;
	sendData_hyvaksyn['hyvaksyn'] = true;

	//console.log(sendData_hyvaksyn);
	//return false;

        $.ajax({
           url: url+'/kayttoehdot?domain='+domain,
	   type:'POST',
 	   data: sendData_hyvaksyn,
           success: function(data){
		var d = JSON.parse(data);
		console.log(data);
		if(d['hyvaksytty'])
		{
			window.location.href="index.html";
			//$('#resultLaatiko').html(d['hyvaksytty']);
		}

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });
        }
    });


  } /* localStorage.getItem('loginOK') */

});
