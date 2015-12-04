$(document).ready(function(){

$("#showres").draggable();

$(".totRivi").click(function(){

	var thisVal = $(this).attr("id").split("_");
	var mod = $(this).attr("mod");

	if( mod == 'update' )
	{
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/update?id='+thisVal[1],
           type: "GET",
           success: function(html){
		console.log("update " + thisVal[1]);
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
		console.log("create " + thisVal[1]);
		$('#showres').modal().html(html);
           }
        });
	}

});




$(".poistaTot").click(function(){

	var thisVal = $(this).attr("rivi");
	var divID = $(this).attr("for").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/deletebyajax',
           type: "POST",
	   data : { "id" : thisVal },
           success: function(data){
		//console.log(data);


	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/toteutuneet/totpvmtid',
			type:'GET',
			data: { "pvm" : divID[0], "tid" : divID[1], "from" : "ajax" },
			  success:function(data){
			  //console.log(data);

			  $('#'+divID[0]+'_'+divID[1]).html(data);

			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/toteutuneet/yhteensapvm',
			type:'GET',
			data: { "pvm" : divID[0], "tid" : divID[1], "from" : "ajax" },
			  success:function(data){
			  //console.log(data);

			  $('#yht_'+divID[0]+'_'+divID[1]).html(data);
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});


           },
	        error:function(data){
		console.log(data);
	  }
        });


});


});
