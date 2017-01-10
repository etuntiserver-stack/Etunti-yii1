$(document).ready(function(){


  if(localStorage.getItem('loginOK'))
  {

	var sendData = loginArr;
	sendData['hakuHeader'] = "true";
	sendData['asiakasID'] = asiakasID;

	//console.log(sendData);

        $.ajax({
           url: url+'/toteutuneet?domain='+domain,
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

	//console.log(sendDataPost);

        $.ajax({
           url: url+'/toteutuneet?domain='+domain,
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
