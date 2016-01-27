$(document).ready(function(){


  $("#odotta").html("<img src='img/icon.png'>");

  setTimeout(tiedot,1000);

  function tiedot(){

	domain	= $("#domain").val();
	email = $("#email").val();
	salasana = $("#salasana").val();

	if(my_location == '') my_location = $("#location").val();
	if((domain != '') & (email !='') & (salasana != ''))
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


