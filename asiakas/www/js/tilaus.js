$(document).ready(function(){


  if(localStorage.getItem('loginOK'))
  {

	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;


	//console.log(sendData);

        $.ajax({
           url: url+'/tilaus?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		if(d['alennuskoodit'])
		{
			$('#a_koodit').replaceWith('' +
				'<select id="alennuskoodi_valiko" class="form-control">' +
				d['alennuskoodit'] +
				'</select>'
			);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

  }
    


});
