$(document).ready(function(){



  $("#odotta").html("<img src='img/icon.png'>");


	    document.addEventListener("deviceready", onDeviceReady, false);
	    function onDeviceReady() {
	        navigator.geolocation.getCurrentPosition(onSuccess, onError);
	    }
	    function onSuccess(position) {
		document.getElementById('olenEksynyt').style.display="block";
	        document.getElementById('location').value = position.coords.latitude + '/' + position.coords.longitude;
	        my_location = position.coords.latitude + '/' + position.coords.longitude;
	    }
	    function onError(error) {
	        alert('code: '    + error.code    + '\n' +
	              'message: ' + error.message + '\n');
	    }





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
 	   data: { check : "viestinta", my_location : my_location, email : email, salasana : salasana, lang : etunti_language },
           success: function(data){
        	//console.log(data);

		var sp = data.split("//");
		if(sp[0] == 'eiLoytyTekija')
		{
		  $("#tekija").html("<div class='alert alert-danger'>"+sp[1]+"</div>").show();
		  $("#odotta").hide('slow');
		  return false;
		} 

		$("#odotta").hide('slow');
		$("#domainBlokki").hide();
		$("#viestit").html(data);

		$("#fullViesti").show('slow');

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
			$("#vastaus_"+thisID).val('');
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




  $("#olenEksynyt").click(function(){

	var r = confirm("Lähetä GPS tiedot?");
	if (r == true)
	    lahetaEksynyt();

  });


 function lahetaEksynyt(){

	my_location = $("#location").val();

	if(my_location !== '')
	{

        var viesti = "OLEN EKSYNYT";

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "oleneksynyt", viesti : viesti, my_location : my_location, email : email, salasana : salasana },
           success: function(data){
        	//console.log(data);

	navigator.notification.alert(
	    data,
	    "",
	    'Vastaus',  
	    'OK'
	);


    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText);
    	}
        });
	} else {

	navigator.notification.alert(
	    "ei löydy GPS sijainti",
	    "",
	    'Vastaus',  
	    'OK'
	);

	}

 }

});
