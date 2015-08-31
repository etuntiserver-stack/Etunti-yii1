$(document).ready(function(){


  var query = location.href.substring((location.href.indexOf('?')+1), location.href.length);
  if(location.href.indexOf('?') < 0) query = '';
      querysplit = query.split('&');
      query = new Array();
  for(var i = 0; i < querysplit.length; i++)
  {
        var namevalue = querysplit[i].split('=');
        namevalue[1] = namevalue[1].replace(/\+/g, ' ');
        query[namevalue[0]] = unescape(namevalue[1]);
  }

  var domain = query['domain'];
  var DeviceIMEI = query['imei'];
  var my_location = query['location'];

  var server = "http://etuntifw.azurewebsites.net";
  var url = server+"/index.php/api/mob";
  var puh_nro = "0449304851";
  var versio = "0.47";
  var tag = "36073245411209220";
  var imei = DeviceIMEI;



  $("#home").click(function(){
	window.location.href='index.html?domain='+domain+'&imei='+DeviceIMEI+'&location='+my_location;
  });

  $("#viestintaURL").click(function(){
	window.location.href='viestinta.html?domain='+domain+'&imei='+DeviceIMEI+'&location='+my_location;
  });


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "tvuoro", imei : imei, my_location : my_location },
           success: function(data){
        	console.log(data);
		$("#tvuoroot").html(data);
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#tvuoroot").html(xhr.responseText);
    	}
        });

});
