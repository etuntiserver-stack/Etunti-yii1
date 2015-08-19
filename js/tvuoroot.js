$(document).ready(function(){


function Checker(e,forid) {
// Delete one
  if (e.shiftKey)
  {
alert(forid)

/*
        $.ajax({
           url: "suunnittelu.php",
	   type:'POST',
	   data: {"delete" : "true", "rowid" : rowid, "tid" : tid, "pvm" : pvm},
           success: function(html){
		$('#'+forid).html(html);
           }
        });
*/

  } 
}


  $('.tv_edit').click(function(){
	var forid = $(this).attr("id");
	Checker(event,forid);
	return false;
  });

	var fal = '1';
function ShiftChecker(e) {
  if (e.shiftKey)
	fal = '0';
}



$(".tv_edit").click(function(){

	ShiftChecker(e)
alert(fal)
	if(fal){

	var thisVal = $(this).attr("id").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update?id='+thisVal[1],
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal().html(html);
           }
        });
	}

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

$("#total").click(function() {
	thisID = $(this).text("Muisti");
	$('#totalForCut').val('');
	$('.tv_edit').css({"opacity":"1"});
});

var kl = '';

$(".glyphicon-paste").click(function() {
	var thisID = $(this).attr("id");
	var thisVal = $(this).attr("id").split("_");
	$("#tv_"+thisVal[1]).css({"opacity":"0.4"});

	$(".forMuisti").removeClass("forMuisti").addClass("mplus glyphicon glyphicon-plus text-success");
	$(".forCut").removeClass("forCut").addClass("mcut glyphicon glyphicon-transfer text-success");

	if($('#total').html() == 'Muisti')
	$('#total').text('');

	$('#total').text(thisVal[1] + ' ' + $('#total').text());

	$('#totalForCut').val("#tvt_"+thisVal[1] + ' ' + $('#totalForCut').val());
	kl = $('#totalForCut').val();	
});


$(".forMuisti").click(function() {
	var thisID = $(this).attr("id");
	var spID = $(this).attr("id").split("_");
	var total = $('#total').html();

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muisti',
	   type:'POST',
	   data: { "thisID" : thisID, "total" : total },
           success: function(html){
		//alert(html);
		var newID = html;

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
			type:'GET',
			data: { id : newID },
			  success:function(data){
			  console.log(data);
			  $('#'+spID[1]+"_"+spID[2]).html(data);
			  $('.mplus').removeClass("mplus glyphicon glyphicon-plus text-success").addClass("forMuisti");
			  $('.mcut').removeClass("mcut glyphicon glyphicon-transfer text-success").addClass("forCut");
			  $('#total').html('Muisti');
			  $('#totalForCut').val('');
			  return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});


           }
        });
});


$(".forCut").click(function() {
	var thisID = $(this).attr("id");
	var spID = $(this).attr("id").split("_");
	var total = $('#total').html();

	var cutThis = kl.split(" ");

	$.each( cutThis, function( key, value ) {
	  $(value).remove();
	});


        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muisti',
	   type:'POST',
	   data: { "thisID" : thisID, "total" : total, "cut" : "true" },
           success: function(html){
		//alert(html);
		var newID = html;

		/* uusi latikko */
	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
			type:'GET',
			data: { id : newID },
			  success:function(data){
			  console.log(data);
			  $('#'+spID[1]+"_"+spID[2]).html(data);
			  $('.mplus').removeClass("mplus glyphicon glyphicon-plus text-success").addClass("forMuisti");
			  $('.mcut').removeClass("mcut glyphicon glyphicon-transfer text-success").addClass("forCut");
			  $('#total').html('Muisti');
			  $('#totalForCut').val('');
			  return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});


           }
        });
});





});
