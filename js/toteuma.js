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


$(".poistaTot").click(function(){

	var thisVal = $(this).attr("rivi");
	var divID = $(this).attr("for").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/deletebyajax',
           type: "POST",
	   data : { "id" : thisVal },
           success: function(data){
		console.log(data);


	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/toteutuneet/totpvmtid',
			type:'GET',
			data: { "pvm" : divID[0], "tid" : divID[1], "from" : "ajax" },
			  success:function(data){
			  console.log(data);

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
			  console.log(data);

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






$(document).ready(function(){

  $('.uusiTot').click(function(){
		$('#toteutuneet-form').submit();
  });

  $('#toteutuneet-form').on('submit',function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );

	  $.ajax({
		  url: 'create',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			console.log(data);
			var divID = data.split("_");

		if( divID ){

		blockUpdater(divID);

		}

		$('#showres').modal('hide');
		return false;
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });


	e.preventDefault(); 
  });


  $('.updTot').click(function(){
		$('#toteutuneet-form-upd').submit();
  });

  $('#toteutuneet-form-upd').on('submit',function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );

	//alert(e.target[0].value)
	  $.ajax({
		  url: 'update?id='+e.target[0].value,
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			console.log(data);
			var divID = data.split("_");

		if( divID ){

		blockUpdater(divID);

		}

		$('#showres').modal('hide');
		return false;
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });



	e.preventDefault(); 
  });



  $("#osoite").change(function(){

	var thisVal = $(this).val();
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/kohteet/osoite?osoite='+thisVal,
           type: "GET",
           success: function(data){
		console.log(data);
		$("#kohdenID").val(data);
           }
        });
  });



  function blockUpdater(divID){

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/toteutuneet/totpvmtid',
			type:'GET',
			data: { "pvm" : divID[0], "tid" : divID[1], "from" : "ajax" },
			  success:function(data){
			  console.log(data);

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
			  console.log(data);

			  $('#yht_'+divID[0]+'_'+divID[1]).html(data);
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});
  }

});

