$(document).ready(function(){

$("#online_reset").click(function(){
	localStorage.clear();
});

$(document).delegate(".kupongi_add","click",function(){
   $.ajax({
	url: 'kupongi_checker?kupongi='+$('.kupongi_id').val(),
	//data:{ kupongi : $('#kupongi_id').val() },
	type:'GET',
	success:function(data){
		console.log(data);
		if(data !== '')
		{
			$(".kupongi_result").html('<span class="text-success">Alennuskoodi on voimassa.</span>');
			ajaaPalveluSave();
			//window.location.reload();

		} else {
			$(".kupongi_result").html('<span class="text-danger">Alennuskoodi ei ole voimassa.</span>');
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
});

$(document).delegate(".kupongi_add_p","click",function(){
   $.ajax({
	url: 'kupongi_checker?kupongi='+$('.kupongi_id_p').val(),
	//data:{ kupongi : $('#kupongi_id').val() },
	type:'GET',
	success:function(data){
		console.log(data);
		if(parseInt(data) > 0)
		{
			$(".kupongi_result_p").html('<span class="text-success">Alennuskoodi on voimassa.</span>');
			ajaaPalveluSave();
			//window.location.reload();
			//$("#hinta").text( parseInt(data) );

		} else {
			$(".kupongi_result_p").html('<span class="text-danger">Alennuskoodi ei ole voimassa.</span>');
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
});

$(".kalenteriin").click(function(){

   var palvelu 	= $('#palvelu option:selected').val();
   if( palvelu === '' ){
	$('#palvelu').focus();
	return false;
   }
   if( parseInt($('#clock').attr('val')) == 0 ){
	alert('Ei valinnut riittävä tietoja');
	return false;
   }

	   $.ajax({
		url: 'index',
		data:{ kalenteriin : true },
		type:'POST',
		success:function(data){
			$('.palvelutlaatikko').hide(370);
			aika_summary('show');
	   	},
		error:function(data){
			console.log(data);
	    	}
	   });
});

$("#palvelu").change(function(){
   var id = $(this).val();
   var t = setTimeout( function() {
	paapalveluAjax(id);
   }, 100 );

});


if( $("#palvelu").val() !== '' ){ paapalveluAjax($("#palvelu").val()); }

function paapalveluAjax(id){
   $.ajax({
	url: 'palvelu_ajax',
	data:{ "id" : id },
	type:'POST',
	success:function(data){
		//console.log(data);
		data = JSON.parse(data);

		if(data[0] != ''){
			$("#toinen_valiko").html(data[0]).show('slow');
		} else {
			$("#toinen_valiko").html('').hide('slow');
		}
			//checker();
		if(data[1] != ''){
			$('#lisapalvelulista').html('<p>' + data[1] + '</p>');
		} else {
			$('#lisapalvelulista').html('');
		}

		ajaaPalveluSave();
		kaksiKalenteria();

   	},
	error:function(data){
		console.log(data);
    	}
    });
}

$(document).delegate("#toinen_valiko_values","change",function(){

	var thisVal 	= $('option:selected', this).val();
	var otsikko 	= $('option:selected', this).attr('otsikko');
	var nimike 	= thisVal;
	var hinta 	= parseFloat($('option:selected', this).attr('hinta'));
	var kesto 	= 0;
	if($('option:selected', this).attr('kesto')){
		kesto = parseFloat($('option:selected', this).attr('kesto'));
	}
	var tyo_toimialue = $('#tyo_toimialue').val();

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ toinen_valiko : "true", otsikko : otsikko, nimike : nimike, hinta : hinta, kesto : kesto, tyo_toimialue : tyo_toimialue },
	type:'POST',
	success:function(data){

		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
			$('.panGetContent').show();
		}
		tuntienTarkistus();
		kaksiKalenteria();
   	},
	error:function(data){
		console.log(data);
    	}
    });

});


$("#tyo_toimialue").change(function(){
	var tyo_toimialue = $(this).val();

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ toinen_valiko : "true", tyo_toimialue : tyo_toimialue },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
			$('.panGetContent').show('370');
		}
		tuntienTarkistus();
   	},
	error:function(data){
		console.log(data);
    	}
    });
});



$(document).delegate(".lisat","click",function(){

   var lisapalvelut = $(this).attr("for").split("//");
   //console.log(lisapalvelut);
   var fordata = $(this).attr("fordata");

   var checked = '';
   if ($(this).is(':checked')) {
	checked = 1;
   } else {
	checked = 0;
   }

   if(lisapalvelut)
   {
   $.ajax({
	url: 'lisat_ajax',
	data:{ fordata : fordata, lisapalvelut : lisapalvelut, checked : checked },
	type:'POST',
	success:function(data){
		//console.log(data);
		ajaaPalveluSave();
   	},
	error:function(data){
		console.log(data);
    	}
    });
    }

});


 function ajaaPalveluSave(){

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ toinen_valiko : "true" },
	type:'POST',
	success:function(data){
		data = JSON.parse(data);
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(data);
			$('.panGetContent').show('370');
		}
		tuntienTarkistus();
   	},
	error:function(data){
		console.log(data);
    	}
    });
  }

  function tuntienTarkistus(){
	var clock = parseFloat($('#clock').attr('val'));
	if((clock > 0) && ( $('#toinen_valiko_values option:selected').val() !== '' ))
	$('.seuraava').removeClass('disabled');

	if( $('#toinen_valiko_values option:selected').val() === '' )
	$('.seuraava').addClass('disabled');
  }


 /* Aika */
var step = 41;
var count1 = step;

$(document).delegate(".day","click",function(){
	$('.day').removeClass('orangeColor');
	$(this).addClass('orangeColor');
	$('#aikoja').show(370);

	$('#valinnuPvm').val( $(this).attr('pvm') );
	aikoja();
	setInterval(aikoja, "15000");
	count1 = step;

	$('html,body').animate({
	   scrollTop: $("#aikoja").offset().top
	});

});





function kaksiKalenteria()
{
   $.ajax({
	url: 'aika_ajax',
	data:{ "nothing" : "true" },
	type:'POST',
	success:function(data){
		//console.log(data);
		data = JSON.parse(data);

		count1 += -1;
		var time = count1*15;
		var minutes = "0" + Math.floor(time / 60);
		var seconds = "0" + (time - minutes * 60);
		jaljella =  minutes.substr(-2) + ":" + seconds.substr(-2);
		$('#countTimer').text('Aikaa jäljellä: '+jaljella);

		if(data !== ''){
		  //console.log(data);
		  $('.kaksiKalenteria').show();
		  $('#kalenterit').html(data);
	          $(".toolt").tooltip();
		}

		if(count1 < 1)
		window.location.href="index?keskeyta=true";
   	},
	error:function(data){
		window.location.href="index?keskeyta=true";
    	}
    });
}

var interval = null;
interval = setInterval(kaksiKalenteria, "15000");


$(document).delegate(".ajaanClick","click",function(){

   count1 = step;
   $(this).remove();
   var pvm = $(this).attr('pvm');
   var tid = $(this).attr('tid');
   var alku = $(this).attr('alku');
   var loppu = $(this).attr('loppu');


   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ "tid" : tid, "pvm" : pvm, "alku" : alku, "loppu" : loppu, "osoiteOnline" : "1" },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
			aika_summary('hide');
			$('#aikoja').hide(370);
			$('.osoitelaatikko').show();

			$('html,body').animate({
			   scrollTop: $(".osoitelaatikko").offset().bottom
			});

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });

});



  clearInterval(aikoja);
  $('#valinnuPvm').val('');
  function aikoja()
  {

   if( $('#valinnuPvm').val() )
   {
   var pvm = $('#valinnuPvm').val();
   $.ajax({
	url: 'ajaat_ajax',
	data:{ "pvm" : pvm },
	type:'POST',
	success:function(data){
		data = JSON.parse(data);
		//console.log(data);
		$('#aikoja').html(data);
		return false;
   	},
	error:function(data){
		console.log(data);
    	}
    });
    }
  }



 /* Osoite */
 $("#uploadKuva").submit(function(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);

    $.ajax({
        url: window.location.pathname,
        type: 'POST',
        data: formData,
        success: function (data) {
            getKuvat();
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;

 });

 getKuvat();

 function getKuvat(){

   $.ajax({
	url: 'index',
	type : 'POST',
	data : { getMyPictures : "true" },
	success:function(data){
		var data = JSON.parse(data);
		$('#getMyPictures').html(data);
   	},
	error:function(data){
		console.log(data);
    	}
    });
  }

  $(document).delegate(".poistaTiedosto","click",function(){

	var forThis = $(this).attr("this");
	var forID = $(this).attr("for");

        $.ajax({
           url: window.location.pathname,
	   type:'POST',
	   data: { "poistaTamaTiedosto" : forThis },
           success: function(data){
		console.log(data);
		$("#"+forID).remove();
           }
        });
  });

tyyppi();
$("#tyyppi").change(function() {
    tyyppi();
});

function tyyppi(){

	var v = $("#tyyppi").val();

	if(v == 'henkilo')
	{
		$('.yritys').hide(375);
		$('#yrityksen_nimi').val('');
		$('#y_tunnus').val('');

	}
	if(v == 'yritys')
	{
		$('.yritys').show(375);
	}
}


		if(localStorage.getItem('tyyppi') !== null)
			$('#tyyppi').val(localStorage.getItem('tyyppi'));
		if(localStorage.getItem('yrityksen_nimi') !== null)
			$('#yrityksen_nimi').val(localStorage.getItem('yrityksen_nimi'));
		if(localStorage.getItem('y_tunnus') !== null)
			$('#y_tunnus').val(localStorage.getItem('y_tunnus'));
		if(localStorage.getItem('yhteyshenkilo') !== null)
			$('#yhteyshenkilo').val(localStorage.getItem('yhteyshenkilo'));
		if(localStorage.getItem('puhelin') !== null)
			$('#puhelin').val(localStorage.getItem('puhelin'));
		if(localStorage.getItem('osoite') !== null)
			$('#osoite').val(localStorage.getItem('osoite'));
		if(localStorage.getItem('postinumero') !== null)
			$('#postinumero').val(localStorage.getItem('postinumero'));
		if(localStorage.getItem('kaupunki') !== null)
			$('#kaupunki').val(localStorage.getItem('kaupunki'));
		if(localStorage.getItem('lisatietoja') !== null)
			$('#lisatietoja').val(localStorage.getItem('lisatietoja'));



if(localStorage.getItem('onkokohde') === 'ei' && localStorage.getItem('sahkoposti') !== '')
{
    $('#loytynytOsoitteet').hide();
    $('#lomake').show('hide');
}




$(document).delegate("#valitseOsoite","change",function(){

   var id = $(this).val();
   localStorage.setItem('valittuOsoiteID', id);
   osoiteAjax(id);

});

function osoiteAjax(id)
{

   $.ajax({
	url: 'get_lomake_ajax?id='+id,
	success:function(data){
		var d = JSON.parse(data);
		console.log(d);
		if(d)
		{


			$('#tyyppi').val(d['tyyppi']);
			$('#yrityksen_nimi').val(d['yrityksen_nimi']);
			$('#y_tunnus').val(d['y_tunnus']);

			$('#yhteyshenkilo').val(d['yhteyshenkilo']);
			$('#puhelin').val(d['puhelin']);
			$('#osoite').val(d['osoite']);
			$('#postinumero').val(d['postinumero']);
			$('#kaupunki').val(d['kaupunki']);
			//$('#lisatietoja').val(d['lisatietoja']);

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
}


function onkokohde(sahkoposti)
{
   $.ajax({
	url: 'onkokohde',
	data:{ "sahkoposti" : sahkoposti },
	type:'POST',
	success:function(data){
		data = JSON.parse(data);
		if(data === 'ei')
		{
			localStorage.setItem('onkokohde', 'ei');
			$('#lomake').show('slow');
			$('.btncheckPosti').hide('slow');
			$("#loytynytOsoitteet").html('');
			console.log(data);
			count = step;

			$('#yhteyshenkilo').removeAttr('readonly');
			$('#puhelin').removeAttr('readonly');
			$('#osoite').removeAttr('readonly');
			$('#postinumero').removeAttr('readonly');
			$('#kaupunki').removeAttr('readonly');

		} else {
			$('.btncheckPosti').hide('slow');
			$('#loytynytOsoitteet').html(data);
			localStorage.setItem('onkokohde', data);
			count = step;

			$('#yhteyshenkilo').attr('readonly', true);
			$('#puhelin').attr('readonly', true);
			$('#osoite').attr('readonly', true);
			$('#postinumero').attr('readonly', true);
			$('#kaupunki').attr('readonly', true);

			$('#valitseOsoite').change();
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
}

if($("#aid_sahkoposti").length)
{
	$("#sahkoposti").val( $("#aid_sahkoposti").val() );
   	var sahkoposti = $("#aid_sahkoposti").val();
	onkokohde(sahkoposti);
	$('.tallennaUusi').html('Valmis');
}

	sahkoposti();
$(document).delegate('#sahkoposti', "keyup, blur", function() {
	sahkoposti();
});

function sahkoposti(){
   var sahkoposti = $('#sahkoposti').val();

   if(sahkoposti.length > 5)
   {
	onkokohde(sahkoposti);
   }
}

$(".tallennaUusi").click(function(){

   var senddata = osoite_validator();
   if( osoite_validator() ){ $(this).remove(); }

   $.ajax({
	url: 'luouusi',
	data: senddata,
	type:'POST',
	success:function(data){
		console.log(data);
		data = JSON.parse(data);

		localStorage.setItem('tyyppi', $('#tyyppi').val());
		localStorage.setItem('yrityksen_nimi', $('#yrityksen_nimi').val());
		localStorage.setItem('y_tunnus', $('#y_tunnus').val());

		localStorage.setItem('yhteyshenkilo', $('#yhteyshenkilo').val());
		localStorage.setItem('puhelin', $('#puhelin').val());
		localStorage.setItem('osoite', $('#osoite').val());
		localStorage.setItem('postinumero', $('#postinumero').val());
		localStorage.setItem('kaupunki', $('#kaupunki').val());
		localStorage.setItem('lisatietoja', $('#lisatietoja').val());


		if(data == 'nytRedirectMaksulle')
		{
		   $.ajax({
			url: 'maksu',
			data:{ "json" : true },
			type:'GET',
			success:function(data){
				//console.log(data);
				data = JSON.parse(data);
				if(data !== '')
				{
					$('.osoitelaatikko').hide(370);
					$('.maksulaatikko').show(370);
					$('#maksu_content').html(data);
					$('html,body').animate({scrollBottom: $('.maksulaatikko').offset().bottom +100 }, 'slow');
				}
		   	},
			error:function(data){
				console.log(data);
		    	}
		    });

		} else {
			alert(data);
		}

   	},
	error:function(data){
		console.log(data);
    	}
    });
});

function osoite_validator(){
   var tyyppi 		= $('#tyyppi').val();
   var yrityksen_nimi 	= $('#yrityksen_nimi').val();
   var y_tunnus 	= $('#y_tunnus').val();

   var sahkoposti 	= $('#sahkoposti').val();
   var osoite 		= $('#osoite').val();
   var postinumero 	= $('#postinumero').val();
   var kaupunki 	= $('#kaupunki').val();
   var puhelin 		= $('#puhelin').val();
   var yhteyshenkilo 	= $('#yhteyshenkilo').val();
   var lisatietoja 	= $('#lisatietoja').val();

   if(sahkoposti === '')
   {
      $('#sahkoposti').focus();
      return false;
   } else if(osoite === ''){
      $('#osoite').focus();
      return false;
   } else if(postinumero === ''){
      $('#postinumero').focus();
      return false;
   } else if(kaupunki === ''){
      $('#kaupunki').focus();
      return false;
   } else if(puhelin === ''){
      $('#puhelin').focus();
      return false;
   } else if(yhteyshenkilo === ''){
      $('#yhteyshenkilo').focus();
      return false;
   }

   $('#varattu_osoite').html( '<b>' + $('#osoite').val() + ', ' + $('#postinumero').val() + ', ' + $('#kaupunki').val() + '</b><br>' );

   var senddata = { sahkoposti : sahkoposti, osoite : osoite, postinumero : postinumero, kaupunki : kaupunki, puhelin : puhelin, yhteyshenkilo : yhteyshenkilo, lisatietoja : lisatietoja, tyyppi : tyyppi, yrityksen_nimi : yrityksen_nimi, y_tunnus : y_tunnus };

   return senddata;
}

$("#show_yhteenveto").click(function(){
	$('html,body').animate({
	   scrollTop: $("#show_yhteenveto").offset().top
	});
	$(this).hide();
});


$(document).delegate('#show_yhteenveto', "click", function() {
	var expanded = $( '#order_summary' ).attr('aria-expanded')
	if(expanded == 'true'){
		$('#show_yhteenveto').text('Sulje');
	}
	if(expanded == 'false'){
		$('#show_yhteenveto').text('Näytä lisää');
	}
});


/* aika_summary */
function aika_summary(show_hide){
  if( parseInt($('#varattu_aika').attr('for')) > 0 ){
	$('#aika_title').html( $('#varattu_aika').attr('pvm') + ', ' + $('#varattu_aika').attr('klo') );
	if(osoite_validator() == false){
	   $( '.osoitelaatikko' ).show(370);
	} else {
	   $( '.osoitelaatikko' ).hide(370);
	}
  }
  if(show_hide == 'show'){
	$( '#aika_summary' ).show(370);
  }
  if(show_hide == 'hide'){
	$( '#aika_summary' ).hide(370);
  }
}
/* aika_summary */

$(document).delegate('.ensimmainen_kk', "click", function() {
	$('#ensimmainen_kk').removeClass('hidden');
	$('#toinen_kk').addClass('hidden');
	$('#kolmas_kk').addClass('hidden');
	var kalenteri_year_month = $(this).attr('kalenteri_year_month');

	   $.ajax({
		url: 'aika_ajax',
		data:{ kalenteri_year_month : kalenteri_year_month },
		type:'POST',
		success:function(data){
			data = JSON.parse(data);
	   	},
		error:function(data){
			console.log(data);
	    	}
	   });

});

$(document).delegate('.toinen_kk', "click", function() {
	$('#ensimmainen_kk').addClass('hidden');
	$('#toinen_kk').removeClass('hidden');
	$('#kolmas_kk').addClass('hidden');
	var kalenteri_year_month = $(this).attr('kalenteri_year_month');

	   $.ajax({
		url: 'aika_ajax',
		data:{ kalenteri_year_month : kalenteri_year_month },
		type:'POST',
		success:function(data){
			data = JSON.parse(data);
	   	},
		error:function(data){
			console.log(data);
	    	}
	   });
});

$(document).delegate('.kolmas_kk', "click", function() {
	$('#ensimmainen_kk').addClass('hidden');
	$('#toinen_kk').addClass('hidden');
	$('#kolmas_kk').removeClass('hidden');
	var kalenteri_year_month = $(this).attr('kalenteri_year_month');

	   $.ajax({
		url: 'aika_ajax',
		data:{ kalenteri_year_month : kalenteri_year_month },
		type:'POST',
		success:function(data){
			data = JSON.parse(data);
	   	},
		error:function(data){
			console.log(data);
	    	}
	   });
});

});
