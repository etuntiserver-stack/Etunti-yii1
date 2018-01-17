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
        	//console.log('data: '+ data);
		var d = JSON.parse(data);
		if(d['alennuskoodit'])
		{
			$('#a_koodit').replaceWith('' +
				'<select id="alennuskoodi_valiko" class="form-control">' +
				d['alennuskoodit'] +
				'</select>'
			);
		}
		if(d['kohteet'])
		{
			$('#kohteet').replaceWith(d['kohteet']);
		}
		if(d['viesti'])
		{
			$('#viesti').replaceWith(d['viesti']);
		}
		if(d['aikaa'])
		{
			$('#aikaa').replaceWith(d['aikaa']);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });


	$(document).delegate('.valiko', 'click', function(e) {
	   if( $( this ).hasClass( "btn-default" ) ){
		$( this ).removeClass('btn-default').addClass('btn-success');
		$( this ).find('i').addClass('fa-check');
		return false;
	   }
	   if( $( this ).hasClass( "btn-success" ) ){
		$( this ).removeClass('btn-success').addClass('btn-default');
		$( this ).find('i').removeClass('fa-check');
		return false;
	   }
	});

	$(document).delegate('.to-tilaus', 'click', function(e) {
	   e.preventDefault();

	   var check_tr = false;
	   var tuotteet = [];
	   $( ".tr_rivi" ).find('.fa-check').each(function( index ) {
		check_tr = true;
		var tuote_id = $( this ).closest('tr').attr('tuote_id');
		var hinnasto_id = $( this ).closest('tr').attr('hinnasto_id');
		var nimike = $( this ).closest('tr').find('.nimike').text();
		var hinta_alv_sis = $( this ).closest('tr').find('.hinta_alv_sis').attr('hinta');
		var yksikko = $( this ).closest('tr').find('.yksikko').text();
		tuotteet.push({ 'tuote_id' : tuote_id, 'hinnasto_id' : hinnasto_id, 'nimike' : nimike, 'hinta_alv_sis' : hinta_alv_sis, 'yksikko' : yksikko });
	   });

	   if( $("#Tilaus_kohde_id option:selected").val() === '' ){
	 	 alert('Valitse kohde.'); 
		return false;
	   }
	   if( $("#Tilaus_toivottu_pvm").val() === '' ){
	 	 alert('Valitse toivottu päivämäärä.'); 
		return false;
	   }
	   if( $("#Tilaus_toivottu_aloitus").val() === '' ){
	 	 alert('Valitse toivottu aloitus aikaa.'); 
		return false;
	   }
	   if( $("#Tilaus_toivottu_lopetus").val() === '' ){
	 	 alert('Valitse toivottu lopetus aikaa.'); 
		return false;
	   }
	   if(!check_tr){ 
		alert('Valitse tuote.');
		return false;
	   }

	   var sendDataPost = $("#laheta_tilaus").serializeArray();
	   $.each(loginArr, function( index, value ) {
		sendDataPost.push({name: index, value: value});
	   });
	   sendDataPost.push({name: 'Tilaus[tuotteet]', value: JSON.stringify(tuotteet) });

           $.ajax({
              url: url+'/tilaus?domain='+domain,
	      type:'POST',
 	      data: sendDataPost,
              success: function(data){
        	console.log('data: '+ data);
		var d = JSON.parse(data);
		if(d['lahetyksen_tulos'] == 'ok')
		{
			$('#tilaus_lomake').html(d['kiitos_lause']);
		}
    	      },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	      }
           });

	});

	$(document).delegate('#Tilaus_kohde_id', 'change', function(e) {

	sendData['kohdeID'] = $('#Tilaus_kohde_id option:selected').val();

        $.ajax({
           url: url+'/tilaus?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
        	//console.log('data: '+ data);
		var d = JSON.parse(data);
		if(d['tp_kontenti'])
		{
			$('#tuotteetPalvelut').html(d['tp_kontenti']);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

	});


  }
    


});
