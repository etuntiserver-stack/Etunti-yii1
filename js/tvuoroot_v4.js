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

$(document).delegate(".getTekijanTiedot","click",function(e){
	e.preventDefault();
	$("#hovertiedot").html("").hide();
	var id = $(this).attr('for');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/get_tekijantiedot?id='+id,
           success: function(data){
		data = JSON.parse(data);

		if( data['bd'] ){
			$('#temaus-modal').find('.panel-title').html('<i class="fa fa-male"></i>'+data['etusuku']);
      $('#temaus-modal').modal().find('.panel-body').html(data['bd']);

      // Save worker ID into hidden field for changing omasiistijavaroitus via AJAX.
      $('#temaus-modal #tekija-id').val(id);

      // Clear previous success notify from changing warning status.
      $('#temaus-modal #omasiistija-valinta-result').empty().attr('hidden');

      // Get current selection for omasiistijavaroitus
      $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/omasiistijat_siistijakohtainen_varoitus`, {
        type: 'POST',
        data: { 'id': id },
        success: function(data) {
          if (data == '0') {
            $('#temaus-modal #omasiistija-valinta').val(0);
          } else if (data == '1') {
            $('#temaus-modal #omasiistija-valinta').val(1);
          } else {
            console.log(`Error retrieving omasiistijavaroitus data; received response: ${data}`);
          }
        }
      });
		}

           }
        });

});

// Hook into the change event of the selection list for enabling/disabling omasiistijavaroitus.
$(document).delegate('#omasiistija-valinta', 'change', function(e) {
  let id = $('#temaus-modal #tekija-id').val();
  let value = $(this).val();
  $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/omasiistijat_siistijakohtainen_varoitus`, {
    type: 'POST',
    data: { 'id': id, 'value': value },
    success: function(data) {
      $('#temaus-modal #omasiistija-valinta-result').html(`<b>${data}</b>`).removeAttr('hidden');
    }
  });
});

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
	var tids	= [];
	var last_loppu  = [];
	var this_ero	= 0;

  // Get list of tids where omasiistijavaroitus is disabled. After this, draw boxes accordingly.
  let warning_disabled_tids = [];
  $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/omasiistijat_siistijakohtainen_varoitus`, {

    type: 'POST',
    data: {'id': 0},

    error: function (xhr, status, error) {
      console.log(xhr.responseText);
    },

    success: function(data) {

      // Parse list of tids, if possible.
      try {
        JSON.parse(data).forEach((tid) => { warning_disabled_tids.push(tid); });
        console.log(`(omasiistijavaroitus_disabled_tids): ${data}`);
      } catch (e) {
        console.log(`(omasiistijavaroitus_disabled_tids) Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
      }

      // List of tids where warning is enabled is acquired; continue drawing boxes via AJAX.
      $.each(tv_arr, function( tid, value ) {
        yht = 0;
        tids.push(tid);
        let disable_warning = (warning_disabled_tids.includes(tid));
        $.each(value, function( pvm, v ) {
          all_tv_edit 	= '';
          tv_kesto	= 0;
          varoitus_klo 	= false;
          last_not_peruutettu = 0;
          $.each(v, function( i2, laatikko ) {
            this_ero 	= 0;
            $.each(laatikko, function( i3, tv_edit ) {
              if( last_not_peruutettu != 0 && parseInt(tv_edit['alku']) < last_not_peruutettu )
                varoitus_klo = true;
              if( last_loppu[pvm +'_'+ tid] > 0 )
                this_ero = parseInt(tv_edit['alku'])-last_loppu[pvm +'_'+ tid];
              if( this_ero > 0 )
                all_tv_edit += '<p class="text-center reika-danger" title="Kahden työvuoron välinen aika"><i class="fa fa-clock-o"></i> Aika: ' + $.sprint(this_ero) + '</p>';
              tv_kesto	+= tv_edit['tv_kesto'];
    
              // Box styling. Enable red border when omasiistijä is not selected (when configured to do so).
              let style='';
              if (!disable_warning && tv_edit['omasiistijavaroitus']) {
                style += 'border: 1px solid red;';
              }
              if (style.length > 0) {
                style = ` style="${style}"`;
              }
    
              all_tv_edit += `<p ${style}>${tv_edit['tv_edit']}</p>`;
              last_loppu[pvm +'_'+ tid] = parseInt(tv_edit['loppu']);
              if(parseInt(tv_edit['peruutettu']) == 0)
                last_not_peruutettu = parseInt(tv_edit['loppu']);
            });
          });
          //console.log(last_loppu);
          pvm_muutos = pvm.split(".");
          did = pvm_muutos[2] + '' + pvm_muutos[1] + '' +pvm_muutos[0] + '_' + tid;
          if( $("#" + did).length > 0 ){
            $("#" + did).html(all_tv_edit + '<div class="pvm_kesto"><span>' + $.sprint(tv_kesto) + '</span></div>');
            if( $("#varoitus_klo_" + did).length > 0 )
              $("#varoitus_klo_" + did).remove();
            if(varoitus_klo)
              $("#" + did).before('<div id="varoitus_klo_'+did+'" class="varoitus_klo text-center bg-danger p5 mr5">Tarkista kellonajat</div>');
          }
          yht += tv_kesto;
        });
      });
    
      $.hovertietoja();
      console.log('tv_arr_update loaded');
    }
  });
}
jQuery.vkolaskenta = function vkolaskenta(tids){
     //console.log(tids);
     if( $(".sunday").length > 0 ){
	$.each($(".sunday"), function( ) {
		this_sunday 	= $(this).attr('sunday');
        	$.ajax({
        	   url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/getsumbyweekall?this_sunday='+this_sunday,
		   type: 'POST',
		   data: { tids : tids },
        	   success: function(data){
			d = JSON.parse(data);
			//console.log(d);
			$.each(d['vkoAll'], function( tid, seconds ) {
				ts_aika = $("#vko_" + d['did'] +'_' + tid).attr('ts_aika');
				$("#vko_" + d['did'] +'_' + tid).html($.sprint(seconds));
				if( seconds > ts_aika ){
					$("#vko_" + d['did'] +'_' + tid).closest('div').addClass('bg-danger');
				} else {
					$("#vko_" + d['did'] +'_' + tid).closest('div').removeClass('bg-danger');
				}
			});
			$('.odotusweeklaskennan').remove();
			console.log('vkolaskenta loaded.');
        	   },
		   error:function(data){
			console.log(data)
		   }
	        });
	});
     }
}

let tekijaDelay = 500;
let tekijaTimeout = undefined;
$(".getTekijanTiedot").hover(function() {
	let elem = $(this)[0];
	let idVal = -1;
	if(elem) {
		idVal = $(elem).attr("for");
	}
	
	tekijaTimeout = setTimeout(function() {
		if(idVal >= 0) {
			let info = tekija_hover(idVal);
			$("#hovertietoja").html(info.html).show();
		}
	}, tekijaDelay);

}, function() {
	$("#hovertietoja").html("").hide();
	clearTimeout(tekijaTimeout);
})

function tekija_hover(tekija_id) {
	let info = "";
	$.ajax({
		url: location.protocol + "//" + location.host + "/index.php/tyovuoroot/tekijahovertietoja?id=" + tekija_id,
		async: false,
		success: function(data) {
			dataJson = JSON.parse(data);
			info = dataJson;
		},
		error: function(err) {
			console.log("error while fetching hover", err);
		}
	});
	return info;
}

jQuery.hovertietoja = function hovertietoja(){
	var delay=1000, setTimeoutConst;
	$('.tv_edit').hover(function(){

		if( !$(this).hasClass('muistissa') && !$(this).prev('i').hasClass('muistissa') ){
			if( !$(this).prev('i').hasClass('muistin') ){
				$(this).before('<i class="fa fa-pencil-square-o muistin" for="' + $(this).attr('id') + '" title="Aktivoi tämä työvuoro siirto/kopio/poisto varten"></i>');
			}
		}
		this_id = $(this).attr('id');

		setTimeoutConst = setTimeout(function() {
			var thisIdTiedot = hv_tiedot(this_id);
			//console.log('hovertietoja hovered: ' + thisIdTiedot);

			$('#hovertietoja').html( thisIdTiedot ).show();
			const osElem = document.getElementById("hover_os_count");
			const propertyIdElem = document.getElementById("hover_property_id");
			if(osElem && propertyIdElem) {
				const propId = propertyIdElem.value;
				if(propId) {
					// set (and fetch) os count
					$.ajax({
						type: "POST",
						url: "/index.php/tyovuoroot/omasiistijat_lista",
						data: {
							location_id: propId,
							force_refresh: false,
						},
						success: (data) => {
							data = JSON.parse(data);
							// make sure we have an object, that is not an array
							// (empty array is returned when there's no OS data available)
							if(typeof data === "object" && !Array.isArray(data)) {
								const keys = Object.keys(data);
								$(osElem).html("Omasiistijöitä: " + (keys?.length ?? 0));
							} else {
								// empty array (or something else unknown) returned, show 0
								$(osElem).html("Omasiistijöitä: 0");
							}
						},
						error: (err) => {
							console.log("error while fetching OS list", err);
						}
					});
				}
			}
			return false;
		}, delay);

		}, function()
		{ 
		$('#hovertietoja').html('').hide();
		clearTimeout(setTimeoutConst);
	});
	console.log('hovertietoja loaded. V5');
}

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
		'<br><p><input id="tp_mukaan" type="checkbox" checked> Työpaari mukaan</p>' +
		'<br><p><span class="btn btn-block btn-danger" id="cal_poista_paiva_ketjusta" pvm="' + cal_pvm + '">Poista päivä ketjusta</span></p>' +
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

	var tyopaari_mukaan = {};
	if ($('#tp_mukaan').prop('checked')) {
		$(".cal_tilanne").each(function(){
			if(cal_pvm == $(this).attr('pvm')){
				pvm = $(this).attr('pvm');
				tyopaari_mukaan[$(this).attr('tid')] = $(this).attr('pvm');
			}
		});
	}
	//console.log(tyopaari_mukaan);

	if(r){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/pois_pvm_ketjusta',
	   type:'GET',
	   data: { toistuva_id : cal_toistuva_id, tid : cal_tid, pvm : cal_pvm, peruuttaminen : peruuttaminen, tyopaari_mukaan : JSON.stringify(tyopaari_mukaan) },
           success: function(data){
		data = JSON.parse(data);
        	console.log(data);
		if( data['return'] && data['return'] == 'ok' ){

			if ($('#tp_mukaan').prop('checked')) {
				$(".cal_tilanne").each(function(){
					if(cal_pvm == $(this).attr('pvm')){
						//$(this).closest('td').removeClass('bg-success').addClass('bg-warning');
						//$(this).removeClass('fa-gear cal_tilanne').addClass('fa-recycle palauta_kejuun');
						$("#" + $(this).attr('this_id')).closest('p').remove();
					}
				});
			} else {
				//cal_this_item.closest('td').removeClass('bg-success').addClass('bg-warning');
				//cal_this_item.removeClass('fa-gear cal_tilanne').addClass('fa-recycle palauta_kejuun');
				$("#" + cal_this_id).closest('p').remove();
			}

			$("#cal_tilanne").remove();
			$.tv_arr_update(data['tv_arr']);
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
/*
		if( data['return'] && data['return'] == 'ok' ){
			if( $("#" + did).length > 0 )
				$("#" + did).html('');
			this_item.closest('td').removeClass('bg-warning').addClass('bg-success');
			this_item.removeClass('fa-recycle palauta_kejuun').addClass('fa-gear cal_tilanne');
	        	console.log(data);
			$.tv_arr_update(data['tv_arr']);
		}
*/
			$.tv_arr_update(data['tv_arr']);
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
});

$(document).delegate(".muistin","click",function(){
	$('.latikkolisatiedot_paa').remove();
	$(this).remove();
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
				alertti += "Siirtäessä tai poistaessa irrotat " + data['varoitus_tyopaari']['osoite'] + " " + data['varoitus_tyopaari']['alku'] + "-" + data['varoitus_tyopaari']['loppu'] + ", työvuoron olevasta työparista\n\n";
			if( data['varoitus_toistuva'] )
				alertti += "Siirtäessä tai poistaessa irrotat " + data['varoitus_toistuva']['osoite'] + " " + data['varoitus_toistuva']['alku'] + "-" + data['varoitus_toistuva']['loppu'] + ", työvuoron toistuvasta ketjusta.\n\n";

			alertti += "Työparit eivät tule siirrossa mukaan. Mikäli haluat työparit mukaan, avaa työvuoro ja siirrä henkilö, käyttäen ylävalikko.";
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
        	console.log(data);
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
			   '<i class="form-group valitseKokopaiva link glyphicon glyphicon-th-large" did_tid="' + did_tid + '" data-toggle="tooltip" data-placement="top" title="Valitse koko päivä"></i>' +
			   '<i class="form-group plussa link fa fa-plus luominen" pvm="' + pvm + '" tid="' + tid + '" data-toggle="tooltip" data-placement="top" title="Luo uusi työvuoro"></i>' +
			'</div>' +
		 '</div>' +
		'</div>' +
	'</div>'
     );
     if(localStorage.getItem("muistissa")){
     $(this).find('.latikkolisatiedot .form-inline').append('' +
		   	   '<i class="form-group mcut fa fa-exchange" id="forCut_' + did_tid + '" data-toggle="tooltip" data-placement="top" title="Siirrä"></i>' +
		   	   '<i class="form-group mplus fa fa-copy" id="forCopy_' + did_tid + '" data-toggle="tooltip" data-placement="top" title="Kopioi"></i> '
     );
     }

}, function()
{ 
     $(this).find('.muistin, .latikkolisatiedot_paa').remove();
});

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
	openTV(this_id, null);
}
$(document).delegate(".tv_edit","click",function(){
	var this_id = $(this).attr('id');
	var l_sisalto = $(this).html();
	$(this).html('<center><h4 class="text-danger">Odota..</h4></center>').removeClass('tv_edit');
	openTV(this_id, l_sisalto);
	$('#hovertietoja').html('').hide();
});
function openTV(this_id, l_sisalto){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update4_form?this_id='+this_id,
           success: function(data){
				d = JSON.parse(data);

				$('#showres').modal().html(d);
				if( l_sisalto !== null )
					$("#" + this_id).html( l_sisalto ).addClass('tv_edit');
				$('#hovertietoja').html('').hide();
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

	ylhalta_px 	= $( window ).height()-event.pageY;
	oikealta_px 	= $( window ).width()-event.pageX;
	top_px 		= event.pageY;
	left_px 	= event.pageX;
	if( oikealta_px < 300 )
		left_px = event.pageX-300;

	$("div.custom-menu").remove();
	$('<div class="custom-menu" for="' + this_id + '">' + 
		valinnat +
	"</div>").appendTo("body");

	laatikko_height	= $(".custom-menu").height();	
	if( (ylhalta_px-laatikko_height) < 0 ){
		//alert((ylhalta_px-laatikko_height))
		top_px = (ylhalta_px-laatikko_height)+event.pageY-30;
	}
        $("div.custom-menu").css({top: top_px + "px", left: left_px + "px"});
});

$(document).delegate(".close_context_menu, table","click",function(){
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
		$(d).each(function( index, value ) {
			if( value['varoitus_tyopaari'] || value['varoitus_toistuva'] ){
				alertti = "Huomio!\n\n";
				if( value['varoitus_tyopaari'] )
					alertti += "Siirtäessä tai poistaessa irrotat " + value['varoitus_tyopaari']['alku'] + "-" + value['varoitus_tyopaari']['loppu'] + ", " + value['varoitus_tyopaari']['osoite'] + " työvuoro olevasta työparista.\n\r";
				if( value['varoitus_toistuva'] )
					alertti += "Siirtäessä tai poistaessa irrotat " + value['varoitus_toistuva']['alku'] + "-" + value['varoitus_toistuva']['loppu'] + ", " + value['varoitus_toistuva']['osoite'] + " työvuoro toistuvasta ketjusta.\n\r";
				alert(alertti);
			}
		});

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
		console.log('Muisti cleared');
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
	if(!e.data) return;
	if(typeof e.data !== 'string') return;
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
		if(check_toistuva){
			lisa_teksti = "Olet irroittamassa työvuoron toistuvasta ketjusta. Haluatko varmasti siirtää tämän?\nHuomaa, että voit palauttaa työvuoron tähän ketjuun työvuorokortilla olevasta kalenterista.\n\n";
			var r = confirm( lisa_teksti + 'Oletko varmaa?' );
			if(!r){	jQuery.clearKaikki(); return false; }
		}
	}

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/operatio?did=did4',
	   type:'POST',
	   data: doWhat,
           success: function(data){
		data = JSON.parse(data);
		//console.log(data);
		jQuery.clearKaikki();
		if( data['poistettu'] ){
  			$( data['poistettu'] ).each(function(index, tv_id) {
				$('#'+tv_id).closest('td').find('.pvm_kesto, .varoitus_klo').remove();
				$('#'+tv_id).closest('p').remove();
			});
		}
		if( data['tv_arr'] ){
			$.tv_arr_update(data['tv_arr']);
		}
		if( data['tids'] ){
			$.vkolaskenta(data['tids']);
		}
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
