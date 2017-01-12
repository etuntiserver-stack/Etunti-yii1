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

	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;
	sendData['tyyppi'] = tyyppi;

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
			$('#forAlert').html('<p><div class="alert alert-success"><h1>Kiitos</h1><h3>Vinkki lähetetty.</h3></div></p>');
			setTimeout(function(){ window.location.reload(); }, 3000);
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
			$('#forAlert').html('<p><div class="alert alert-success"><h1>Kiitos</h1><h3>Palaute lähetetty.</h3></div></p>');
			setTimeout(function(){ window.location.reload(); }, 3000);
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
