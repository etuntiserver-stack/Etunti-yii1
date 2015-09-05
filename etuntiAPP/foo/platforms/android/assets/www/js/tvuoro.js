$(document).ready(function(){

  var imei = '';
  var my_location = '';
  var domain = '';
  var tag = 'notag';

  var server = "http://etuntifw.azurewebsites.net";
  var url = server+"/index.php/api/mob";
  var puh_nro = "0449304851";
  var versio = "0.47";

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

  $("#viestintaURL").click(function(){
	window.location.href='viestinta.html?domain='+domain+'&imei='+imei+'&location='+my_location+'&tag='+tag;
  });

  $("#tehty").click(function(){
	window.location.href='tehty.html?domain='+domain+'&imei='+imei+'&location='+my_location+'&tag='+tag;
  });


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "tvuoro", imei : imei, my_location : my_location },
           success: function(data){
        	//console.log(data);
		$("#tvuoroot").html(data);
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#tvuoroot").html(xhr.responseText);
    	}
        });

});
