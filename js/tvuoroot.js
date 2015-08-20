$(document).ready(function(){


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


$(".etsi_month").on('change', function() {
	var thisVal = $(this).val();
	if(!thisVal)
	var thisVal = 'kaikki';

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/index',
	   type:'POST',
	   data: { "etsi_month" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});


$(".vietyovuoroon").click(function(){

	var thisVal = $(this).attr("pvmtid");
	window.open(location.protocol + "//" + location.host + '/index.php/tyovuoroot?pvmtid='+thisVal+'#'+thisVal);

});


function clearKaikki(){

	$('#totalForCut').val('');
	$('#total').val('');
	$('#trash').removeClass();
	$("#clear").removeClass();
	$(".mplus").removeClass().addClass("forCopy");
	$(".mcut").removeClass().addClass("forCut");
	$('.fullRivi').css({"opacity":"1"});

}

$("#clear").click(function() {
	clearKaikki();
});

var kl = '';

$(".glyphicon-paste").click(function() {
	var thisID = $(this).attr("for");
	$("#"+thisID).css({"opacity":"0.4"});

	$(".forCopy").removeClass("forCopy").addClass("mplus glyphicon glyphicon-plus text-success");
	$(".forCut").removeClass("forCut").addClass("mcut glyphicon glyphicon-transfer text-success");

	$('#totalForCut').val(thisID + '//' + $('#totalForCut').val());
	kl = $('#totalForCut').val();

	$("#trash").addClass("glyphicon glyphicon-trash btn btn-danger btn-group");
	$("#clear").addClass("glyphicon glyphicon-refresh btn btn-success btn-group");
});


$(".forCopy").click(function() {

  var thisID = $(this).attr("id").split("_");
  var kaikkiIDs = kl.split("//");

  $.each( kaikkiIDs, function( key, value ) {

    //$("#"+value).remove();

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
			  console.log(data);
			  $('#'+newPvm+"_"+newTid).html(data);
			  clearKaikki()
			  //return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

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
  var kaikkiIDs = kl.split("//");

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
			  console.log(data);
			  $('#'+newPvm+"_"+newTid).html(data);
			  clearKaikki();
			  //return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
      }

  });

});



$("#trash").click(function() {

  var kaikkiIDs = kl.split("//");

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
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
      }

  });

});



});
