$(document).ready(function(){

$(".totRivi").click(function(){

	var thisVal = $(this).attr("id").split("_");
	var mod = $(this).attr("mod");

	if( mod == 'update' )
	{
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/update?id='+thisVal[1],
           type: "GET",
           success: function(html){
		$('#showres').modal().html(html);
           }
        });
	}

	if( mod == 'create' )
	{
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/create',
           type: "POST",
	   data: { forid : thisVal[1] },
           success: function(html){
		$('#showres').modal().html(html);
           }
        });
	}

});


});

