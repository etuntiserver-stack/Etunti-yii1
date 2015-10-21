$(document).ready(function(){

$("#Toteutuneet_aloitan").click(function(){
	$(this).select();
});
$("#Toteutuneet_loppui").click(function(){
	$(this).select();
});
$("#Mobile_aloitan").click(function(){
	$(this).select();
});
$("#Mobile_loppui").click(function(){
	$(this).select();
});

$("#Mobile_kohde_kannasta").change(function(){
	var kohdenID = $(this).val();
	$("#Mobile_kohdenID").val(kohdenID);
});



$(".uusirivi").click(function(){

      var forThis = $(this).attr("for");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/uusirivi',
           type: "POST",
	   data: { forThis : forThis },
           success: function(data){
		//console.log(data);
		$('#showres').modal().html(data);
           }
        });

});


$(".al").keyup(function(){
	lasketaanKesto();
});

$(".lp").blur(function(){
	lasketaanKesto();
});


function lasketaanKesto(){
	var hmaD = $(".al").val().split(" ");
	hmaD = hmaD[1].split(":");
	var hmaL = $(".lp").val().split(" ");
	hmaL = hmaL[1].split(":");

	var secondsD = (+hmaD[0]) * 60 * 60 + (+hmaD[1]);
	var secondsL = (+hmaL[0]) * 60 * 60 + (+hmaL[1]);
	var totalSec =  (secondsL - secondsD);

	var hours = parseInt( totalSec / 3600 ) % 24;
	var minutes = parseInt( totalSec / 60 ) % 60;
	var seconds = totalSec % 60;

	$("#kesto").html('<h1>'+(hours < 10 ? "0" + hours : hours) + ":" + (seconds  < 10 ? "0" + seconds : seconds)+'</h1>');
	return false;
}

$(".chckbxHyvaksynta").click(function(){
  $(this).each(function() {
      var label = $(this).prop("checked");
      var kuka = $(this).attr("kuka");
      var thisID = $(this).attr("id").split("_");
      if(label)
      {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/hyvaksy?id='+thisID[1],
           type: "POST",
	   data: { hyvaksy : "kylla", kuka : kuka },
           success: function(data){
		console.log(data);
           }
        });
      } else {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/hyvaksy?id='+thisID[1],
           type: "POST",
	   data: { hyvaksy : "ei" },
           success: function(data){
		console.log(data);
           }
        });
      }
  });
});

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

		return false;
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






$(document).ready(function(){

  $('.uusiRivi').click(function(){


    var Mobile_aloitan = $("#Mobile_aloitan").val();
    var Mobile_loppui = $("#Mobile_loppui").val();
    var Mobile_kohde_kannasta = $("#Mobile_kohde_kannasta").val();
    var Mobile_status = $("#Mobile_status").val();

    if (Mobile_aloitan  === '__:__') {
        $('#Mobile_aloitan').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (Mobile_loppui  === '__:__') {
        $('#Mobile_loppui').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (Mobile_kohde_kannasta  === '') {
        $('#Mobile_kohde_kannasta').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (Mobile_status  === '') {
        $('#Mobile_status').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

		$('#mobile-form').submit();
  });

  $('#mobile-form').on('submit',function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/mobile/uusirivi',
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
			//console.log(data);
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

