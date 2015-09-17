$(document).ready(function(){


$("#autoInsert").click(function(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/autoinsert',
           //type: "POST",
           //data: { "pvm" : pvm, "tid" : tid },
           success: function(html){
		$('#showres').modal().html(html);
           }
        });

});

$("#autoRemove").click(function(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/autoremove',
           //type: "POST",
           //data: { "pvm" : pvm, "tid" : tid },
           success: function(html){
		$('#showres').modal().html(html);
           }
        });

});


$(".tv_edit").click(function(){

	var thisVal = $(this).attr("id").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update?id='+thisVal[1],
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal().html(html);
           }
        });

});

/*
$('.laatikko').bind("contextmenu",function(e){
	var pvm = $(this).attr("pvm");
	var tid = $(this).attr("tid");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/create',
           type: "POST",
           data: { "pvm" : pvm, "tid" : tid },
           success: function(html){
		$('#showres').modal().html(html);
           }
        });
   	return false;
});
*/



  	var kaikkiIDs = '';
	var muistissa = '';

function clearKaikki(){

  $(".muistissa").each(function() {
     $(this).css({"opacity":"1"});
     $(this).removeClass("muistissa").addClass("muistin");
  });

	muistissa = '';
	kaikkiIDs = '';

	$('#totalForCut').val('');
	$('#trash').removeClass();
	$("#clear").removeClass();
	$(".mplus").removeClass().addClass("forCopy");
	$(".mcut").removeClass().addClass("forCut");
	//$('.fullRivi').css({"opacity":"1"});

}

$("#clear").click(function() {
	clearKaikki();
});


$(".muistin").click(function() {

	$(this).removeClass("muistin").addClass("muistissa");

	var thisID = $(this).attr("for");

	$(".forCopy").removeClass("forCopy").addClass("mplus glyphicon glyphicon-plus text-success");
	$(".forCut").removeClass("forCut").addClass("mcut glyphicon glyphicon-transfer text-success");

	//$('#totalForCut').val(thisID + '//' + $('#totalForCut').val());

	$("#trash").addClass("glyphicon glyphicon-trash btn btn-danger btn-group");
	$("#clear").addClass("glyphicon glyphicon-refresh btn btn-success btn-group");


  $(".muistissa").each(function() {
     muistissa += $(this).attr("for")+"//";
     $(this).css({"opacity":"0.4"});
     //$(this).removeClass("muistissa").addClass("muistin");
     console.log($(this).text())
  });


});



$(".forCopy").click(function() {

  var thisID = $(this).attr("id").split("_");
  kaikkiIDs = muistissa.split("//");

  $.each( kaikkiIDs, function( key, value ) {

    console.log(value);

    var splVal 	= value.split("_");
    var tvID	= splVal[0];
    var pvm	= splVal[1];
    var newPvm	= thisID[1];
    var tid	= splVal[2];
    var newTid	= thisID[2];

      //alert(value)
      if(tvID[1])
      {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/operatio',
	   type:'POST',
	   data: { "id" : tvID, "copy" : "true", "newPvm" : newPvm, "newTid" : newTid },
           success: function(data){
        	console.log(data);

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
			type:'GET',
			data: { "pvm" : newPvm, "tid" : newTid, "from" : "ajax" },
			  success:function(data){
			  //console.log(data);
			  $('#'+newPvm+"_"+newTid).html(data);
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

  		clearKaikki();
  		return false;
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
      }

  });


});


$(".forCut").click(function() {


  var thisID = $(this).attr("id").split("_");
  kaikkiIDs = muistissa.split("//");

  $.each( kaikkiIDs, function( key, value ) {

    $("#"+value).remove();

    var splVal 	= value.split("_");
    var tvID	= splVal[0];
    var pvm	= splVal[1];
    var newPvm	= thisID[1];
    var tid	= splVal[2];
    var newTid	= thisID[2];

      //alert(value)
      if(tvID[1])
      {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/operatio',
	   type:'POST',
	   data: { "id" : tvID, "cut" : "true", "newPvm" : newPvm, "newTid" : newTid },
           success: function(data){
        	console.log(data);

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
			type:'GET',
			data: { "pvm" : newPvm, "tid" : newTid, "from" : "ajax" },
			  success:function(data){
			  //console.log(data);
			  $('#'+newPvm+"_"+newTid).html(data);
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

  		clearKaikki();
  		return false;
    	   },
    	   error: function(data) {
	    	console.log(data);
 	   }
        });
      }

  });

});



$("#trash").click(function() {

  var kl = muistissa;
  kaikkiIDs = muistissa.split("//");

  $.each( kaikkiIDs, function( key, value ) {

    $("#"+value).remove();

    var splVal 	= value.split("_");
    var tvID	= splVal[0];
    var pvm	= splVal[1];
    var tid	= splVal[2];

      //alert(value)
      if(tvID[1])
      {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/operatio',
	   type:'POST',
	   data: { "id" : tvID, "remove" : "true" },
           success: function(data){
        	console.log(data);
  		clearKaikki();
  		return false;
    	   },
    	   error: function(data) {
	    	console.log(data);
 	   }
        });
      }

  });

});




$("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

});

$('.selectpicker').selectpicker({
      style: 'btn-default',
      //size: 4
  });


});
