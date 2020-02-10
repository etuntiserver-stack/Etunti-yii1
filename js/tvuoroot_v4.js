$(document).ready(function(){
// GLOBALS
jQuery.sprint = function sprint(sec){
	var h = sec/3600 ^ 0 ;
	var m = (sec-h*3600)/60 ^ 0 ;
	return (h<10?"0"+h:h)+":"+(m<10?"0"+m:m);
}

// <-- TV Lahetys
checkChecked();
$('.lahetettava_checkbox').change(function() {
        checkChecked();
});
function checkChecked(){
	var get = [];
	$('.lahetettava_checkbox').each(function() {
		if(this.checked)
			get.push($(this).attr("for"));
	});
	if( get.length > 0 )
		$('#lahetaTyovuoroja').show('slow');
	else
		$('#lahetaTyovuoroja').hide('slow');
	return get; 
}
$(document).delegate(".valitseKaikkiLahetettavaksi","click",function(){
	$('.lahetettava_checkbox').prop("checked", true);
	localStorage.setItem('tvLahetysChckBoxes', 'all');
	valitseTaiPiilota();
	checkChecked();
});
valitseTaiPiilota();
function valitseTaiPiilota(){
	if(localStorage.getItem('tvLahetysChckBoxes') == 'all'){
		$('.lahetettava_checkbox').prop("checked", true);
		$('.valitseKaikkiLahetettavaksi').val('Piilota kaikki').removeClass('valitseKaikkiLahetettavaksi').addClass('piilotaKaikkiLahettykset');

		$(document).delegate(".piilotaKaikkiLahettykset","click",function(){
			$('.lahetettava_checkbox').prop("checked", false);
			localStorage.removeItem('tvLahetysChckBoxes');
			$(this).val('Valitse kaikki').addClass('valitseKaikkiLahetettavaksi').removeClass('piilotaKaikkiLahettykset');
			checkChecked();
		});
	}
}
$("#lahetaTyovuoroja").click(function(){
	if( checkChecked().length > 0 && $('#week').length > 0 ){
		setTimeout(function(){document.location.href = location.protocol + "//" + location.host + "/index.php/tyovuoroot/laheta_k?week=" + $('#week').val() + "&year=" + $('#year').val() + "&tulosta=false&check="+checkChecked();},500);
	} else {
		alert('Valitse työntekijä');
	}
});
//     TV Lahetys -->

$(document).delegate("#showres","click",function(){
	$("#pto_ilmoitus").html('');
});
var varaus_l = 0;
$( ".td_varaus.varaus_l" ).each(function( index ) {
	if( $(this).find('.tv_edit').text() !== '' ){
		varaus_l += 1;
	}
});
if( varaus_l > 0 ){ $('.td_varaus').addClass('in'); }

jQuery.tv_arr_update = function tv_arr_update(tv_arr){
	var did 	= '';
	var kesto_yht 	= [];
	var yht		= 0;
	$.each(tv_arr, function( tid, value ) {
		yht = 0;
		$.each(value, function( pvm, v ) {
			all_tv_edit 	= '';
			tv_kesto	= 0;
			$.each(v, function( i2, tv_edit ) {
				tv_kesto	+= tv_edit[0]['tv_kesto'];
				all_tv_edit += '<p>' + tv_edit[0]['tv_edit'] + '</p>';
			});
			pvm_muutos = pvm.split(".");
			did = pvm_muutos[2] + '' + pvm_muutos[1] + '' +pvm_muutos[0] + '_' + tid;
			//console.log(all_tv_edit);
			if( $("#" + did).length > 0 )
				$("#" + did).html(all_tv_edit + '<div class="pull-right pvm_yht">' + $.sprint(tv_kesto) + '</div>');
			yht += tv_kesto;
		});
		$('#vkoyht_' + tid).html($.sprint(yht));
	});
}

var cal_this_id 	= '';
var cal_this_item 	= '';
var cal_pvm 		= '';
var cal_tid 		= '';
var cal_toistuva_id 	= '';
$(document).delegate(".cal_tilanne","click",function(){
	$("#cal_tilanne").remove();
	cal_this_item = $(this);
	cal_this_id = $(this).attr('this_id');
	cal_toistuva_id = $(this).attr('toistuva_id');
	cal_tid = $(this).attr('tid');
	cal_pvm = $(this).attr('pvm');
	$(this).closest('table').before('' + 
		'<div style="position:relative; z-index: 99999999; opacity: 2" id="cal_tilanne"><div style="position:absolute; width: 100%;">' +
		'<div style="padding: 10px; background: white; border:1px #ddd solid; color:#333; ">' +
		'<span class="pull-right btn btn-sm btn-default" id="cal_sulje">x</span>' +
		'<center><h4>' + cal_pvm + '</h4></center>' +
		'<label>Peruuttaminen</label>' +
		'<select class="form-control" id="cal_peruutettu">' +
		'<option value=""></option>' +
		'<option value="1">Peruutettu</option>' +
		'<option value="2">Peruutettu laskutettava</option>' +
		'</select>' +
		'<p class="text-danger">Huomio! Tämä työvuoro poistetaan ketjusta. Voit luoda tilalle peruutetun työvuoron valitsemalla ylläolevasta listasta.</p>' +
		'<br><p><span class="btn btn-block btn-danger" id="cal_poista_paiva_ketjusta">Poista päivä ketjusta</span></p>' +
		'</div></div></div>'
	);
	$("#cal_sulje").click(function(){
		$("#cal_tilanne").remove();
	});
});

$(document).delegate("#cal_poista_paiva_ketjusta","click",function(){
	var peruuttaminen = $("#cal_peruutettu option:selected").val();
	if( peruuttaminen == 0 )
		var r = confirm('Haluatko varmasti poistaa tämä päivä ketjusta?');
	else
		var r = confirm('Haluatko varmasti poistaa tämä päivä ketjusta ja luoda yksittäinen peruutettu työvuoro?');
	if(r){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/pois_pvm_ketjusta',
	   type:'GET',
	   data: { toistuva_id : cal_toistuva_id, tid : cal_tid, pvm : cal_pvm, peruuttaminen : peruuttaminen },
           success: function(data){
		data = JSON.parse(data);
        	console.log(data);
		if( data['return'] && data['return'] == 'ok' ){
			cal_this_item.closest('td').removeClass('bg-success').addClass('bg-warning');
			cal_this_item.removeClass('fa-gear cal_tilanne').addClass('fa-recycle palauta_kejuun');
			$("#" + cal_this_id).closest('p').remove();
			$.tv_arr_update(data['tv_arr']);
			$("#cal_tilanne").remove();
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
	pvm_muutos = pvm.split(".");
	var did = pvm_muutos[2] + '' + pvm_muutos[1] + '' +pvm_muutos[0] + '_' + tid;
	var r = confirm('Haluatko varmasti palauttaa tämän?');
	if(r){
	// <-- Puhdistetaan laatiko per pvm ja tid
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/palauta_pvm_kejuun',
	   type:'GET',
	   data: { toistuva_id : toistuva_id, tid : tid, pvm : pvm },
           success: function(data){
		data = JSON.parse(data);
        	//console.log(data);
		if( data['return'] && data['return'] == 'on_olemassa' ){
			alert('Huomio!\nTämä päivä ei saa palauttaa, koska löytyy yksittäinen työvuoro saman tiedon mukaan.');
			return false;
		}
		if( data['return'] && data['return'] == 'ok' ){
			if( $("#" + did).length > 0 )
				$("#" + did).html('');
			this_item.closest('td').removeClass('bg-warning').addClass('bg-success');
			this_item.removeClass('fa-recycle palauta_kejuun').addClass('fa-gear cal_tilanne');
	        	console.log(data);
			$.tv_arr_update(data['tv_arr']);
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
	var thisvar = $(this).remove();
	var thisFor = $(this).attr('for');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muistin',
	   type:'POST',
	   data: { "id" : thisFor },
           success: function(data){
		data = JSON.parse(data);
        	console.log(data);
		if( data['varoitus_tyopaari'] || data['varoitus_toistuva'] ){
			alertti = "Huomio!\n\n";
			if( data['varoitus_tyopaari'] )
				alertti += "Siirtäessä tai poistaessa irotat " + data['varoitus_tyopaari']['alku'] + "-" + data['varoitus_tyopaari']['loppu'] + ", " + data['varoitus_tyopaari']['osoite'] + " työvuoro olevasta työparista";
			if( data['varoitus_toistuva'] )
				alertti += "Siirtäessä tai poistaessa irotat " + data['varoitus_toistuva']['alku'] + "-" + data['varoitus_toistuva']['loppu'] + ", " + data['varoitus_toistuva']['osoite'] + " työvuoro toistuvasta ketjusta.";
			alert(alertti);
		}
	  	muisti();
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

});

muisti();
function muisti(){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muistissa',
           success: function(data){
		data = JSON.parse(data);
        	//console.log(data);
	
		if(data != 'muistityhja'){
			$(data).each(function(index, tv_id) {
				if( $('#'+tv_id).hasClass('ei_saa_muokata') ){ return true; }
	     			$('#'+tv_id).css({"opacity":"0.4"});
	     			$('#'+tv_id).addClass("muistissa");
			});
			$(".muokkausLi").show();
			localStorage.setItem("muistissa", data);
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

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }

var this_id = 0;
if(getUrlVars()["tv_id"]){
	this_id = getUrlVars()["tv_id"];
	openTV(this_id);
}
$(document).delegate(".tv_edit","click",function(){
	var this_id = $(this).attr('id');
	openTV(this_id);
});
function openTV(this_id){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update4_form?this_id='+this_id,
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
}
$(document).delegate(".latikkoAsetukset p","contextmenu",function(e){
	e.preventDefault();
	var valinnat = '';
	var this_id = $(this).find('.tv_edit').attr('id');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/contextmenu_valinnat?this_id=' + this_id,
	   //type:'POST',
	   //data: { },
	   async: false,
           success: function(data){
		//d = JSON.parse(data);
        	//console.log(data);
		valinnat += data;
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
	$("div.custom-menu").remove();
	$('<div class="custom-menu" for="' + this_id + '">' + 
		valinnat +
	"</div>")
        .appendTo("body")
        .css({top: event.pageY + "px", left: event.pageX + "px"});
});

$(document).bind("click", function(event) {
	$("div.custom-menu").hide();
});

$(document).delegate("div.custom-menu select","change",function(){
	var this_id = $(this).closest('.custom-menu').attr('for');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/contextmenu_submits?this_id=' + this_id,
	   type:'POST',
	   data: { field : $(this).attr('id'), value : $('option:selected', this).val() },
           success: function(data){
		data = JSON.parse(data);
        	console.log(data);
		if( data['tv_arr'] ){
			$.tv_arr_update(data['tv_arr']);
		}
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
});

$(document).delegate(".valitseKokopaiva","click",function(){
	var pvm = $(this).closest('.latikkoAsetukset').attr("pvm");
	var tid = $(this).closest('.latikkoAsetukset').attr("tid");
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/valitse_kokopaiva',
           type: "POST",
           data: { "pvm" : pvm, "tid" : tid },
           success: function(data){
		d = JSON.parse(data);
		//console.log(data);
		alertti = "Huomio!\n\n";
		$(d).each(function( index, value ) {
			if( value['varoitus_tyopaari'] || value['varoitus_toistuva'] )
				if( value['varoitus_tyopaari'] )
					alertti += "Siirtäessä tai poistaessa irotat " + value['varoitus_tyopaari']['alku'] + "-" + value['varoitus_tyopaari']['loppu'] + ", " + value['varoitus_tyopaari']['osoite'] + " työvuoro olevasta työparista.\n\r";
				if( value['varoitus_toistuva'] )
					alertti += "Siirtäessä tai poistaessa irotat " + value['varoitus_toistuva']['alku'] + "-" + value['varoitus_toistuva']['loppu'] + ", " + value['varoitus_toistuva']['osoite'] + " työvuoro toistuvasta ketjusta.\n\r";
		});
		alert(alertti);
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
			if( !$(this).hasClass('mennytPaivat') )
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
	if(edata[0] == 'doit'){
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
		/* Before Delete */
		if(thisID[0] == 'forRemove'){
			var r = confirm('Haluatko varmasti poistaa tämän?');
			if(!r){	jQuery.clearKaikki(); return false; }
		}
		/* Before CUT */
		if(thisID[0] == 'forCut'){
		var check_toistuva = false;
		if(localStorage.getItem("muistissa")){
			$(localStorage.getItem("muistissa").split(",")).each(function(index, value) {
				if( value.includes( '99999999' ) ){
					check_toistuva = true;
					return false;
				}
			});
			//console.log(check_toistuva);
		}
		var lisa_teksti = '';
		if(check_toistuva)
			lisa_teksti = "Olet irroittamassa työvuoron toistuvasta ketjusta. Haluatko varmasti siirtää tämän?\nHuomaa, että voit palauttaa työvuoron tähän ketjuun työvuorokortilla olevasta kalenterista.\n\n";
		var r = confirm( lisa_teksti + 'Oletko varmaa?' );
		if(!r){	jQuery.clearKaikki(); return false; }
	}

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/operatio?did=did4',
	   type:'POST',
	   data: doWhat,
           success: function(data){
		data = JSON.parse(data);
		console.log(data);

		if( data['poistettu'] ){
  			$( data['poistettu'] ).each(function(index, tv_id) {
				$('#'+tv_id).closest('p').remove();
			});
		}
		if( data['tv_arr'] ){
			$.tv_arr_update(data['tv_arr']);
		}

		jQuery.clearKaikki();
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

        	//console.log('doIt funktio '+thisID[0]+' ok!');
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
