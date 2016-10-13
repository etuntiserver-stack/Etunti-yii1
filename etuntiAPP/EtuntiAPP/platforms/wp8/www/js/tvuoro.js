$(document).ready(function(){

    var domain = '';
    var email = '';
    var salasana = '';


    if(localStorage.getItem('domain'))
	  domain=localStorage.getItem('domain');
    if(localStorage.getItem('email'))
	  email=localStorage.getItem('email');
    if(localStorage.getItem('salasana'))
	  salasana=localStorage.getItem('salasana');


  $("#odotta").html("<img src='img/icon.png'>");

  //setTimeout(tiedot,3000);
  tiedot();

  function tiedot(){

	if(my_location == '') 
	my_location = $("#location").val();

	if((domain !== '') & (email !== '') & (salasana !== ''))
	{

		set();

	} else {
		$("#domainBlokki").show();
		return false;
 	}
	
  }


function set(){

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "tvuoro", my_location : my_location, email : email, salasana : salasana },
           success: function(data){
        	//console.log(data);
		var sp = data.split("//");
		if(sp[0] == 'eiLoytyTekija')
		{
		  $("#tekija").html("<div class='alert alert-danger'>"+sp[1]+"</div>").show();
		  $("#odotta").fadeOut(370);
		  return false;
		} 

		$("#odotta").hide('slow');
		$("#domainBlokki").hide();
		$("#tvuoroot").html(data);
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#tvuoroot").html(xhr.responseText);
    	}
        });

}

});


