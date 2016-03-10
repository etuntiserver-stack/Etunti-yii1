$(document).ready(function(){

$("#showres").draggable();

$('td').hover(function()
{
     $(this).find('.plussa').show();
}, function()
{ 
     $(this).find('.plussa').hide();
});


$('#tyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: 'Tyhjä',
	selectAllText: 'Valitse kaikki',
	allSelectedText: 'Kaikki',
	selectedText: 'valittu',
});
/*
$('#tyontekijat').selectpicker({

});
*/


$("#uusiTilaus").click(function(){

   $.ajax({
	url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/uusitilaus',
	data:$(this).serialize(),
	type:'POST',
	success:function(data){
		$('#showres').modal().html(data);
   	},
	error:function(data){
		console.log(data);
    	}
    });

});


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


function tv_editClick(){
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
}


function luominenClick(){
$('.luominen').click(function(){
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
}

jQuery.clearKaikki = function clearKaikki(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muisticlear',
	   type:'POST',
	   data: { "clear" : 1 },
           success: function(data){
        	console.log(data);

		$(".muistissa").each(function() {
		     $(this).css({"opacity":"1"});
		     $(this).removeClass("muistissa").addClass("muistin");
		});

		$('#trash').removeClass();
		$("#clear").removeClass();
		$(".mplus").removeClass().addClass("forCopy");
		$(".mcut").removeClass().addClass("forCut");
		$('#muistissa').html('')

    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });


}

$("#clear").click(function() {
	jQuery.clearKaikki();
});


function muistiClick(){

  $(".muistin").click(function() {

	var thisFor = $(this).attr('for');
	//var thisVal = $(this).attr('for').split('_');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muistin',
	   type:'POST',
	   data: { "id" : thisFor },
           success: function(data){
        	//console.log(data);
	  	muisti();
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

  });
		return false;
}


muisti();
function muisti(){

	var dat = '';

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muistissa',
           success: function(data){
        	//console.log(data);
	
		if(data != 'muistityhja')
		{
		var parseData = 0;
		parseData = data.split(',');
		$(parseData).each(function(index, value) {

     			$('#'+value).css({"opacity":"0.4"});
     			$('#'+value).removeClass("muistin").addClass("muistissa");
		});

			$(".forCopy").removeClass("forCopy").addClass("mplus glyphicon glyphicon-plus text-success link");
			$(".forCut").removeClass("forCut").addClass("mcut glyphicon glyphicon-transfer text-success link");
			$("#trash").addClass("fa fa-trash-o btn btn-danger");
			$("#clear").addClass("fa fa-circle-o-notch btn btn-success");
		
		}
		return false;
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

}





jQuery.klikkaukset = function klikkaukset(){
  tv_editClick();
  luominenClick();
  muistiClick();
  return false;
}
jQuery.klikkaukset();



jQuery.DoIt = function DoIt(thisID,tila){

  var newPvm	= thisID[1];
  var newTid	= thisID[2];
  var doWhat	= 0;

  if(tila == 'copy')
  doWhat 	= { 'copy' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(tila == 'cut')
  doWhat 	= { 'cut' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(tila == 'remove')
  doWhat 	= { 'remove' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(tila == 'checkThis')
  doWhat 	= { 'checkThis' : "true", "newPvm" : newPvm, "newTid" : newTid };

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/operatio',
	   type:'POST',
	   data: doWhat,
           success: function(data){
        	//console.log(data);

		if(data !== '')
		{
			var sp = JSON.parse(data).split('//');
			if(sp[0])
			$('#'+newPvm+"_"+newTid).html(JSON.parse(sp[0]));
	
			if(sp[1])
			{
	  			var parseData = sp[1].split(',');
	  			$(parseData).each(function(index, value) {
					console.log(value);
					$('#'+value).remove();
				});
				parseData = '';
			}
	
			jQuery.klikkaukset();
			jQuery.allKlikk();
		  	jQuery.clearKaikki();
			return false;
		}



    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

        	console.log('doIt funktio '+tila+' ok!');
		return false;

}


jQuery.allKlikk = function allKlikk(){
$(".forCopy").click(function() {
  	var thisID = $(this).attr("id").split("_");
	jQuery.DoIt(thisID,"copy");
	return false;
});

$(".forCut").click(function() {
	var thisID = $(this).attr("id").split("_");
	jQuery.DoIt(thisID,"cut");
	return false;
});
}
jQuery.allKlikk();

$("#trash").click(function() {
	var thisID = $(this).attr("id").split("_");
	jQuery.DoIt(thisID,"remove");
	return false;
});



vkolopputCheck();
function vkolopputCheck(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/vkolopput',
           success: function(data){
		//console.log(data);
		if(data == 1)
		$('#vkolopput').removeClass('btn-default').addClass('btn-success');
		if(data == 0)
		$('#vkolopput').removeClass('btn-success').addClass('btn-default');


		$('#vkolopput').click(function(){

		        $.ajax({
		           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/vkolopchange',
			   type:'POST',
			   data: { "nyt" : data },
		           success: function(data){
		        	console.log(data);
				window.location.reload();
		    	   },
		    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
			    	console.log(XMLHttpRequest);
		 	   }
		        });
		
		});


           }
        });
   	return false;

}


$("#viikkonhyppaminen").change(function() {
	var thisVal = $(this).val();
	window.location.href=thisVal;
	return false;
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




});
