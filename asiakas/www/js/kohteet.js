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
           url: url+'/kohteet?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		//console.log(data);
		if(d['kohteet_lista'])
		{
			$('#resultLaatiko').html(d['kohteet_lista']);
		}

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });


    $(document).delegate('.luoTallennaKohde', 'click', function() {
	$('#kohteet-form').submit();
    });

    // <-- on submit
    $(document).delegate('#kohteet-form', 'submit', function(e) {

	var sendDataPost = $(this).serializeArray();
	$.each(loginArr, function( index, value ) {
		sendDataPost.push({name: index, value: value});
	});
	sendDataPost['id'] = id;
	//console.log(sendDataPost);

        $.ajax({
           url: url+'/kohteet?domain='+domain,
	   type:'POST',
 	   data: sendDataPost,
           success: function(data){
		var d = JSON.parse(data);
		//console.log(data);
		if(d['kohteet_lista'] == 'tallennettu')
		{
			window.location.href='kohteet.html';
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

	e.preventDefault();
    });
    //     on submit -->

  } /* localStorage.getItem('loginOK') */

});
