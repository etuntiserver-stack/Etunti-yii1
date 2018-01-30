$(document).ready(function(){



$(document).delegate("#kupongi_add","click",function(){


   $.ajax({
	url: 'kupongi_checker?kupongi='+$('#kupongi_id').val(),
	//data:{ kupongi : $('#kupongi_id').val() },
	type:'GET',
	success:function(data){
		console.log(data);
		if(data !== '')
		{
			$("#kupongi_result").html('<span class="text-success">Alennuskoodi on voimassa.</span>');
			ajaaPalveluSave();

		} else {
			$("#kupongi_result").html('<span class="text-danger">Alennuskoodi ei ole voimassa.</span>');
		}
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
		data = JSON.parse(data);
		//console.log(data);

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
			$('.panGetContent').show('370');
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
		data = JSON.parse(data);

		count1 += -1;
		var time = count1*15;
		var minutes = "0" + Math.floor(time / 60);
		var seconds = "0" + (time - minutes * 60);
		jaljella =  minutes.substr(-2) + ":" + seconds.substr(-2);
		$('#countTimer').text('Aikajäljellä: '+jaljella);

		if(data !== ''){
		  //console.log(data);
		  $('.kaksiKalenteria').show('');
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
setInterval(kaksiKalenteria, "15000");


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
			$('#aikoja').hide(370);
			//aikoja();

			$('html,body').animate({
			   scrollTop: $("#panGetContent").offset().top
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


});
