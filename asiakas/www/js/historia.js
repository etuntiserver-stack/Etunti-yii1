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
	var tyyppi = '';
	if(getUrlVars()["tyyppi"])
		tyyppi = getUrlVars()["tyyppi"];

	var id = '';
	if(getUrlVars()["id"])
		id = getUrlVars()["id"];

	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;
	sendData['tyyppi'] = tyyppi;
	sendData['id'] = id;

	//console.log(sendData);

        $.ajax({
           url: url+'/historia?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		if(d)
		{
			$('#resultLaatiko').html(d);
			reloadSkin();
			reloadDatepicker();
			$('.sisainen').remove();
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });


    // <-- on submit
    $(document).delegate('#mobForm', 'submit', function(e) {

	var sendDataPost = $(this).serializeArray();
	$.each(loginArr, function( index, value ) {
		sendDataPost.push({name: index, value: value});
	});
		sendDataPost.push({name: tyyppi, value: tyyppi});

	//console.log(sendDataPost);

	$('.haemob').hide();
	$('.tuloshakusta').text('Odota..');

        $.ajax({
           url: url+'/historia?domain='+domain,
	   type:'POST',
 	   data: sendDataPost,
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data)
		{
			$('#resultLaatiko').html(data);
			$('.sisainen').remove();
			reloadSkin();
			reloadDatepicker();
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

	e.preventDefault();
    });
    //     on submit -->


    // <-- on submit Vinkki
    $(document).delegate('#vinkki-extranet-form', 'submit', function(e) {

	$('.submitButton').hide();
	var sendDataPost = $(this).serializeArray();
	$.each(loginArr, function( index, value ) {
		sendDataPost.push({name: index, value: value});
	});

		sendDataPost.push({name: tyyppi, value: tyyppi});

	//console.log(sendDataPost);

        $.ajax({
           url: url+'/historia?domain='+domain,
	   type:'POST',
 	   data: sendDataPost,
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data['OK'])
		{
			$('#avaaVinkki').removeClass('in');
			$('#modalBody').html('<p><div class="alert alert-success"><h1>Kiitos</h1><h3>'+ data['OK'] +'</h3></div></p>');
			$('#myModal').modal('show'); 
			$('.closeModal').click(function(){ window.location.reload();  });
		}
		if(data['Error'])
		{
			alert( JSON.stringify(data['Error']) );
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

	e.preventDefault();
    });
    //     on submit Vinkki -->


    // <-- on submit Palaute
    $(document).delegate('#palautteet-form', 'submit', function(e) {


	var sendDataPost = $(this).serializeArray();
	$.each(loginArr, function( index, value ) {
		sendDataPost.push({name: index, value: value});
	});

		sendDataPost.push({name: tyyppi, value: tyyppi});

	//console.log(sendDataPost);

	$('.haemob').hide();
	$('.tuloshakusta').text('Odota..');

        $.ajax({
           url: url+'/historia?domain='+domain,
	   type:'POST',
 	   data: sendDataPost,
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data['OK'])
		{
			$('#avaaVinkki').removeClass('in');
			$('#modalBody').html('<p><div class="alert alert-success"><h1>Kiitos</h1><h3>'+ data['OK'] +'</h3></div></p>');
			$('#myModal').modal('show'); 
			$('.closeModal').click(function(){ window.location.reload();  });
		}
		if(data['Error'])
		{
			alert( JSON.stringify(data['Error']) );
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

	e.preventDefault();
    });
    //     on submit Palaute -->


    //  <-- on submit Palaute Vastaus
    $(document).delegate('.palautteet-form-vastaus', 'submit', function(e) {


	var sendDataPost = $(this).serializeArray();
	$.each(loginArr, function( index, value ) {
		sendDataPost.push({name: index, value: value});
	});

		sendDataPost.push({name: tyyppi, value: tyyppi});

	//console.log(sendDataPost);
        $.ajax({
           url: url+'/historia?domain='+domain,
	   type:'POST',
 	   data: sendDataPost,
           success: function(data){
		var data = JSON.parse(data);
		//console.log(data);
		if(data['OK'])
		{
			$('#modalBody').html('<p><div class="alert alert-success"><h1>Kiitos</h1><h3>'+ data['OK'] +'</h3></div></p>');
			$('#myModal').modal('show'); 
			$('.closeModal').click(function(){ window.location.reload();  });
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

	e.preventDefault();
    });
    //     on submit Palaute Vastaus -->


    //  <-- get Lasku PDF
    $(document).delegate('.getLaskuPDF', 'click', function() {

		$(this).val('Odota');
		var thisID = $(this).attr('id');
		var sendDataPost = $(this).serializeArray();
		$.each(loginArr, function( index, value ) {
		sendDataPost.push({name: index, value: value});
		});

		sendDataPost.push({name: tyyppi, value: tyyppi});
		sendDataPost.push({name: "id", value: thisID});

		$.ajax({
			url: url+'/getlaskupdf?domain='+domain+'&id=' + thisID,
			type:'POST',
			data: sendDataPost,
			success: function(data){
				//data = JSON.parse(data);
				console.log(data);
			}
		});
    });
    //     get Lasku PDF -->



    //  <-- Peruuttaa tyovuoroa
    $(document).delegate('.peruuttaa', 'click', function() {


	var thisID = $(this).attr('for');
	var peruutus_tilanne = parseInt($(this).attr('peruutus_tilanne'));
	var peruutusehdot = $("#peruutusehdot").text();

	var sendDataPost = $(this).serializeArray();
	$.each(loginArr, function( index, value ) {
		sendDataPost.push({name: index, value: value});
	});

		sendDataPost.push({name: tyyppi, value: tyyppi});
		sendDataPost.push({name: "id", value: thisID});
		sendDataPost.push({name: "peruuttaa_tyovuoroa", value: "true"});

	var confirm_teksti = 'Haluatko varmaasti peruuttaa?';
	if(peruutus_tilanne == 2)
	confirm_teksti = peruutusehdot + "\n\n" + confirm_teksti;

	confirm_teksti = confirm_teksti.replace(/\n/g, "<br />");


	alertify.confirm(confirm_teksti,
          function(e){
                if(e){

		        $.ajax({
		           url: url+'/historia?domain='+domain,
			   type:'POST',
			   data: sendDataPost,
		           success: function(data){
				data = JSON.parse(data);
				console.log(data);
				if(data['OK'])
				{
					alert("Tilaus on peruutettu.");
					window.location.reload();
				}
		           }
		        });

                    /*alertify.success('Ok');*/

                } else {
                    alertify.error('Cancel');
                }
          });


    });
    //     Peruuttaa tyovuoroa -->


    $(document).delegate('.submitButton', 'click', function() {
	$(this).hide();
    });

    function reloadSkin()
    {
	if( localStorage.getItem('headerSkin') ){
	$('.myBgColors').removeClass(localStorage.getItem('headerSkin'));
	$('.myBgColors').addClass(localStorage.getItem('headerSkin'));
	}
    }
    function reloadDatepicker()
    {
        $( ".datepickerFI" ).datetimepicker({
         format : 'DD.MM.YYYY',
	 locale: 'fi',
        });
    }



  }
    


});
