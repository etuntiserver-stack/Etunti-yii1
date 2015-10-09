$(document).ready(function(){



  $("#home").click(function(){
	window.location.href='index.html';
  });

  $("#viestintaURL").click(function(){
	window.location.href='viestinta.html';
  });

  $("#tehty").click(function(){
	window.location.href='tehty.html';
  });


  $("#odotta").html("<h1>ODOTA</h1>");
  setTimeout(tiedot,1000);

  function tiedot(){

	domain	= $("#domain").val();
	email = $("#email").val();
	salasana = $("#salasana").val();

	if(my_location == '') my_location = $("#location").val();
	if((domain != '') & (email !='') & (salasana != ''))
	{
		$("#domainBlokki").hide();
		set();
		$("#odotta").hide();

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
		$("#tvuoroot").html(data);
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#tvuoroot").html(xhr.responseText);
    	}
        });

}

});


