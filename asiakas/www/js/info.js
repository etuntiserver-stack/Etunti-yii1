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

	var id = '';
	if(getUrlVars()["id"])
		id = getUrlVars()["id"];

	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;
	sendData['showlist'] = true;

	if(id !== '')
	sendData['id'] = id;

	//console.log(sendData);

        $.ajax({
           url: url+'/info?domain='+domain,
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


  } /* localStorage.getItem('loginOK') */

});
