$(document).ready(function(){



  $('#luoAsiakas').click(function(){

        $.ajax({
           url: url+'/adminkalut?dom='+domain,
	   type:'POST',
 	   data: { "luolomakke" : true },
           success: function(data){
		data = JSON.parse(data);
alert(data)
		$('#eriTyokalut').html(data);

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	   }
        });
  });


});
