$(document).ready(function(){

  var query = location.href.substring((location.href.indexOf('?')+1), location.href.length);
  if(location.href.indexOf('?') < 0) 
  {
     query = '';
  } else {
     querysplit = query.split('&');
     query = new Array();
     for(var i = 0; i < querysplit.length; i++)
     {
        var namevalue = querysplit[i].split('=');
        namevalue[1] = namevalue[1].replace(/\+/g, ' ');
        query[namevalue[0]] = unescape(namevalue[1]);
     }

     if(query['imei']) 		imei = query['imei'];
     if(query['location']) 	my_location = query['location'];
     if(query['domain']) 	domain = query['domain'];
     if(query['tag'])		tag = query['tag'];
  }


  $("#home").click(function(){
	window.location.href='index.html?domain='+domain+'&imei='+imei+'&location='+my_location+'&tag='+tag;
  });

  $("#tvuoro").click(function(){
	window.location.href='tvuoro.html?domain='+domain+'&imei='+imei+'&location='+my_location+'&tag='+tag;
  });

  $("#tehty").click(function(){
	window.location.href='tehty.html?domain='+domain+'&imei='+imei+'&location='+my_location+'&tag='+tag;
  });



        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "viestinta", imei : imei, my_location : my_location },
           success: function(data){
        	//console.log(data);
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
	 	   data: { check : "vastaus", imei : imei, my_location : my_location, viestinID : thisID, vastText : vastaus },
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


$(".lahetaToimistoon").click(function(){

  var viesti = $("#toimistoon").val();
  if (viesti  === '') {
        $('#toimistoon').css({"border" : "2px #f14010 solid"}).focus();
        return false;
  }

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "uusiviesti", viesti : viesti, imei : imei, my_location : my_location },
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
