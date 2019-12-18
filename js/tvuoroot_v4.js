$(document).ready(function(){

  var varaus_l = 0;
  $( ".td_varaus.varaus_l" ).each(function( index ) {
	if( $(this).find('.tv_edit').text() !== '' ){
		varaus_l += 1;
	}
  });
  if( varaus_l > 0 ){ $('.td_varaus').addClass('in'); }

$(document).delegate(".pois_ketjusta","click",function(){
	var this_item = $(this);
	var this_id = $(this).attr('this_id');
	var toistuva_id = $(this).attr('toistuva_id');
	var tid = $(this).attr('tid');
	var pvm = $(this).attr('pvm');
	var r = confirm('Haluatko varmasti poistaa tämän?');
	if(r){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/pois_pvm_ketjusta',
	   type:'GET',
	   data: { toistuva_id : toistuva_id, tid : tid, pvm : pvm },
           success: function(data){
		data = JSON.parse(data);
        	console.log(data);
		if( data['return'] && data['return'] == 'ok' ){
			this_item.removeClass('fa-trash text-danger pois_ketjusta').addClass('fa-recycle text-warning palauta_kejuun');
			$("#" + this_id).closest('p').remove();
		}
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
	}
});

$(document).delegate(".palauta_kejuun","click",function(){
	var this_item = $(this);
	var toistuva_id = $(this).attr('toistuva_id');
	var tid = $(this).attr('tid');
	var pvm = $(this).attr('pvm');
	var r = confirm('Haluatko varmasti palauttaa tämän?');
	if(r){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/palauta_pvm_kejuun',
	   type:'GET',
	   data: { toistuva_id : toistuva_id, tid : tid, pvm : pvm },
           success: function(data){
		data = JSON.parse(data);
        	//console.log(data);
		if( data['return'] && data['return'] == 'ok' ){
			this_item.removeClass('fa-recycle text-warning palauta_kejuun').addClass('fa-trash text-danger pois_ketjusta');
	        	console.log(data);
			var did = '';
			$.each(data['tv_arr'], function( tid, value ) {
				$.each(value, function( pvm, v ) {
					all_tv_edit = '';
					$.each(v, function( i2, tv_edit ) {
						all_tv_edit += '<p>' + tv_edit + '</p>';
					});
					pvm_muutos = pvm.split(".");
					did = pvm_muutos[2] + '' + pvm_muutos[1] + '' +pvm_muutos[0] + '_' + tid;
					if( $("#" + did).length > 0 )
						$("#" + did).html(all_tv_edit);
				});
			});

		}
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
	}
});

$(document).delegate(".muistin","click",function(){
	$('.latikkolisatiedot_paa').remove();
	var thisvar = this;
	var thisFor = $(this).attr('for');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muistin',
	   type:'POST',
	   data: { "id" : thisFor },
           success: function(data){
        	//console.log(data);
	  	muisti();
		thisvar.remove();
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

});

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
		var tv_id = 0;
		parseData = data.split(',');
		$(parseData).each(function(index, value) {
			tv_id = value.split('_');
			if( $('#'+tv_id[0]).hasClass('ei_saa_muokata') ){ return true; }
     			$('#'+tv_id[0]).css({"opacity":"0.4"});
     			$('#'+tv_id[0]).addClass("muistissa");
		});
			$(".muokkausLi").show();
			localStorage.setItem("muistissa", true);
			$(".mcut, .mplus").css({"display":"block !important"});
		}
		return false;
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

}

$('td').hover(function()
{
     var did_tid = $(this).find('.latikkoAsetukset').attr('id');
     var pvm = $(this).find('.latikkoAsetukset').attr('pvm');
     var tid = $(this).find('.latikkoAsetukset').attr('tid');
     $(this).find('.latikkoAsetukset').prepend('' +
	'<div class="latikkolisatiedot_paa">' +
		'<div class="latikkolisatiedot">' +
		 '<div class="form-inline">' +
			   '<i class="form-group valitseKokopaiva link glyphicon glyphicon-th-large" did_tid="' + did_tid + '"></i>' +
			   '<i class="form-group plussa link fa fa-plus luominen" pvm="' + pvm + '" tid="' + tid + '"></i>' +
			'</div>' +
		 '</div>' +
		'</div>' +
	'</div>'
     );
     if(localStorage.getItem("muistissa")){
     $(this).find('.latikkolisatiedot .form-inline').append('' +
		   	   '<i class="form-group mcut fa fa-exchange" id="forCut_' + did_tid + '"></i>' +
		   	   '<i class="form-group mplus fa fa-copy" id="forCopy_' + did_tid + '"></i> '
     );
     }

     var delay=1000, setTimeoutConst;
     $(this).find('.tv_edit').hover(function(){
	if( !$(this).hasClass('muistissa') && !$(this).prev('i').hasClass('muistissa') ){
	   if( !$(this).prev('i').hasClass('muistin') ){
		$(this).before('<i class="fa fa-pencil-square-o muistin" for="' + $(this).attr('id') + '"></i>');
	   }
	}
	var this_id = $(this).attr('id');
	setTimeoutConst = setTimeout(function() {
		var hovertietoja = hv_tiedot(this_id);
		$('#hovertietoja').html(hovertietoja).show();
	}, delay);

     }, function()
     { 
	$('#hovertietoja').html('').hide();
	clearTimeout(setTimeoutConst);
     });

}, function()
{ 
     $(this).find('.muistin, .latikkolisatiedot_paa').remove();
});

function hv_tiedot(this_id){
	var hovertietoja = '';
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/hovertietoja?this_id='+this_id,
	   async: false,
           success: function(data){
		d = JSON.parse(data);
		//console.log(d);
		hovertietoja += d;
           },
	   error:function(data){
		console.log(data)
	   }
        });
	return hovertietoja;
}

$(document).delegate(".luominen","click",function(){
	var pvm = $(this).attr("pvm");
	var tid = $(this).attr("tid");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/create4_form',
           type: "GET",
           data: { "pvm" : pvm, "tid" : tid },
           success: function(data){
		d = JSON.parse(data);
		$('#showres').modal().html(d);
		//console.log(data);
           }
        });
   	return false;
});

$(document).delegate(".tv_edit","click",function(){
	var this_id = $(this).attr('id');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update4_form?this_id='+this_id,
           //type: "GET",
           //data: {toistuva_id : toistuva_id},
           success: function(data){
		d = JSON.parse(data);
		$('#showres').modal().html(d);
		//console.log('tv_edit click: ' + data);
           },
	   error:function(data){
		console.log(data);
		alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
});

$(document).delegate(".valitseKokopaiva","click",function(){
	var pvm = $(this).attr("pvm");
	var tid = $(this).attr("tid");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/valitse_kokopaiva',
           type: "POST",
           data: { "pvm" : pvm, "tid" : tid },
           success: function(data){
		console.log(data);
		muisti();
           }
        });
   	return false;
});

jQuery.clearKaikki = function clearKaikki(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muisticlear',
	   type:'POST',
	   data: { "clear" : 1 },
           success: function(data){
        	//console.log(data);

		$(".muistissa").each(function() {
		     $(this).css({"opacity":"1"});
		     $(this).removeClass("muistissa");
		});

		$(".muokkausLi").hide();
		$(".latikkolisatiedot_paa").remove();
		localStorage.clear("muistissa");
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });


}


$(document).delegate(".mplus","click",function(){
  	var thisID = $(this).attr("id");
	parent.postMessage( "doit//"+thisID, "*");
});

$(document).delegate(".mcut","click",function(){
	var thisID = $(this).attr("id");
	parent.postMessage( "doit//"+thisID, "*");
});

$(document).delegate(".clear","click",function(){
	jQuery.clearKaikki();
});

$(document).delegate(".trash","click",function(){
	var thisID = 'forRemove_1_1';
	parent.postMessage( "doit//"+thisID, "*");
});





window.addEventListener('message', function(e) {
  var edata = e.data.split('//');
  if(edata[0] == 'doit')
  {
  var thisID = edata[1].split('_');
  var newPvm	= thisID[1];
  var newTid	= thisID[2];
  var doWhat	= 0;

  if(thisID[0] == 'forCopy')
  doWhat 	= { 'copy' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(thisID[0] == 'forCut')
  doWhat 	= { 'cut' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(thisID[0] == 'forRemove')
  doWhat 	= { 'remove' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(thisID[0] == 'checkThis')
  doWhat 	= { 'checkThis' : "true", "newPvm" : newPvm, "newTid" : newTid };

  if(thisID[0] == 'forRemove')
  {
	var r = confirm('Haluatko varmasti poistaa tämän?');
	if(!r){	return false; }
  }

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/operatio?did=did4',
	   type:'POST',
	   data: doWhat,
           success: function(data){
        	console.log(JSON.parse(data));

		if(data !== '')
		{
			var sp = JSON.parse(data).split('//');
			if(sp[0])
			{
				if(sp[0] == 'Error'){
					alert(sp[1]);
					return false;
				}
				$('#'+newPvm+'_'+newTid).html(JSON.parse(sp[0]));
				
				if(thisID[0] == 'forCopy')
				{
					var ThisHeight = $('#'+newPvm+'_'+newTid).height();
					var FirstHeight = $('#first_'+newPvm).height(ThisHeight);
				}

			}
	
			if(sp[1])
			{
	  			var parseData = sp[1].split(',');
				tv_id = 0;
	  			$(parseData).each(function(index, value) {
					//console.log(value);
					tv_id = value.split('_');
					$('#'+tv_id[0]).remove();
				});
				parseData = '';
			}
	
		  	jQuery.clearKaikki();

		}


    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

        	console.log('doIt funktio '+thisID[0]+' ok!');
  }

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



$("#viikkonhyppaminen, #vuodenhyppaminen").change(function() {
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
