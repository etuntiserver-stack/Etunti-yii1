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
        	console.log('data: '+ data);
		var d = JSON.parse(data);
		if(d['alennuskoodit'])
		{
			$('#a_koodit').replaceWith('' +
				'<select id="alennuskoodi_valiko" class="form-control">' +
				d['alennuskoodit'] +
				'</select>'
			);
		}
		if(d['tp_kontenti'])
		{
			$('#tuotteetPalvelut').replaceWith(d['tp_kontenti']);
		}
		if(d['kohteet'])
		{
			$('#kohteet').replaceWith(d['kohteet']);
		}
		if(d['viesti'])
		{
			$('#viesti').replaceWith(d['viesti']);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });


	$(document).delegate('.valiko', 'click', function(e) {
	   if( $( this ).hasClass( "btn-default" ) ){
		$( this ).removeClass('btn-default').addClass('btn-success');
		return false;
	   }
	   if( $( this ).hasClass( "btn-success" ) ){
		$( this ).removeClass('btn-success').addClass('btn-default');
		return false;
	   }
	});

	$(document).delegate('.to-tilaus', 'click', function(e) {
		alert()
	});


  }
    


});
