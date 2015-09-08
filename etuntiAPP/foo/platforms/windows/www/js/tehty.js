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

  $("#viestintaURL").click(function(){
	window.location.href='viestinta.html?domain='+domain+'&imei='+imei+'&location='+my_location+'&tag='+tag;
  });



        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "tehty", imei : imei, my_location : my_location },
           success: function(data){
        	//console.log(data);
		$("#viestit").html(data);
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#viestit").html(xhr.responseText);
    	}
        });




});
