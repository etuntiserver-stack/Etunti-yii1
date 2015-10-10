$(document).ready(function(){



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
 	   data: { check : "viestinta", my_location : my_location, email : email, salasana : salasana },
           success: function(data){
        	//console.log(data);

		var sp = data.split("//");
		if(sp[0] == 'eiLoytyTekija')
		{
		  $("#tekija").html("<div class='alert alert-danger'>"+sp[1]+"</div>").show();
		  $("#odotta").fadeOut(370);
		  return false;
		} 


		$("#viestit").html(data);

		/* vastaus */
  		$(".viesti").click(function(){
		    	var thisID = $(this).attr("id");
		    	var vastaus = $("#vastaus_"+thisID).val();

		    if (vastaus  === '') {
		        $("#vastaus_"+thisID).css({"border" : "2px #f14010 solid"}).focus();
		        return false;
		    }


	          $.ajax({
	           url: url+'/imei?dom='+domain,
		   type:'POST',
	 	   data: { check : "vastaus", my_location : my_location, email : email, salasana : salasana, viestinID : thisID, vastText : vastaus },
	           success: function(data){
	        	console.log(data);
			$("#text_"+thisID).html(data.replace(/\n/g, "<br />"));
	    	  },
	    		error:function (xhr, ajaxOptions, thrownError){
	        	console.log(xhr.responseText);
			$("#viestit").html(xhr.responseText);
	    	  }
		  });

		});
		/* vastaus */
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#viestit").html(xhr.responseText);
    	}
        });

}

$(".lahetaToimistoon").click(function(){

  var viesti = $("#toimistoon").val();
  if (viesti  === '') {
        $('#toimistoon').css({"border" : "2px #f14010 solid"}).focus();
        return false;
  }

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "uusiviesti", viesti : viesti, my_location : my_location, email : email, salasana : salasana },
           success: function(data){
        	//console.log(data);
		$(".lahetaToimistoon").hide('slow');
		$("#result2").html('<h2 class="text-danger">'+data+'</h2>').show('slow');
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText);
    	}
        });
});



});
