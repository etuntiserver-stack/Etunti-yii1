$(document).ready(function(){




$(document).delegate(".tuntienHyvaksyntaTaulu","click",function(){
	
    var divToPrint = document.getElementById('tuntienHyvaksyntaTaulu');
    var htmlToPrint = '' +
        '<style type="text/css">' +
	'.table tbody>tr>td{' +
	    	'vertical-align: top;' +
	'}' +
	'.forFooterAlla, .everyviikko{ width: 100%; }' +
        'table th, table td {' +
        'border:1px solid #333;' +
        'padding:3px 5px;' +
        '}' +
	'.tdw1, .tdw2, .tdw3{' +
	'width: 30%;' +
	'}' +
	'.tdw4{' +
	'width: 10%;' +
	'}' +
	'.korvaukset_ennakkot, .lahetys_netvisoriin{' +
		'display: none !important;' +
	'}' +
	'.tv_kesto, .mob_kesto{ float: right; }' +
        '</style>';
    htmlToPrint += $('#forTulostus').html();
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
});

$(document).delegate(".tuntienHyvaksyntaTauluTivistelma","click",function(){
	
    $('#tuntienHyvaksyntaTaulu tr.su_lu_tot').remove();
    var divToPrint = document.getElementById('tuntienHyvaksyntaTaulu');
    var htmlToPrint = '' +
        '<style type="text/css">' +
	'.table tbody>tr>td{' +
	    	'vertical-align: top;' +
	'}' +
        'table {' +
	'border-collapse: collapse;' +
	'border: 0;' +
        '}' +
        'table th, table td {' +
        'border:1px solid #333;' +
        'padding:3px 5px;' +
        '}' +
        '</style>';
    htmlToPrint += $('#forTulostus').html();
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
});



$(document).delegate(".uusirivi","click",function(){

      var forThis = $(this).attr("for");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/uusirivi',
           type: "POST",
	   data: { forThis : forThis },
           success: function(data){
		//console.log(data);
		$('#showres').modal().html(data);
		return false;
           }
        });

});


$(document).delegate(".al","keyup",function(){
	lasketaanKesto();
});

$(document).delegate(".lp","keyup",function(){
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

$(document).delegate(".chckbxHyvaksynta","click",function(){

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



$(document).delegate(".vuosilomaHyvaksynta","click",function(){

  $(this).each(function() {
      var label = $(this).prop("checked");
      var pvm = $(this).attr("pvm");
      var week = $(this).attr("week");
      var tid = $(this).attr("tid");
      var tila = $(this).attr("tila");
      var thisDID = $(this).attr("did");

	var VLFoot	= 0;
	var VKLFoot	= 0;

      if(label)
      {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/vuosiloma_hyvaksy',
           type: "POST",
	   data: { hyvaksy : "kylla", pvm : pvm, tid : tid, tila : tila },
           success: function(data){
		console.log(data);
		VL_VKL_JNE(thisDID, tila, week, 1);
           }
        });
      } else {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/vuosiloma_hyvaksy',
           type: "POST",
	   data: { hyvaksy : "ei", pvm : pvm, tid : tid, tila : tila },
           success: function(data){
		console.log(data);
		VL_VKL_JNE(thisDID, tila, week, 0);
           }
        });
      }
  });
});



function VL_VKL_JNE(thisDID, tila, week, count){

	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.alla'+tila).attr('total', count).html(count);

		// <-- Week
		var forWeek = 0;
  		$( ".yhteensaPvmAllaTaulu_"+week ).each(function( i ) {
			forWeek += parseFloat($(this).find('.alla'+tila).attr('total'));
		});
	  	$('.'+tila+'Week_'+week).html(forWeek);
		//     Week -->

		// <-- Footer
		var forFoot = 0;
  		$( ".forFooterAlla" ).each(function( i ) {
			forFoot += parseFloat($(this).find('.alla'+tila).attr('total'));
		});
	  	$('#yhteensaFooterTaulu').find('.'+tila+'Foot').html(forFoot);
		//     Footer -->
}





});






$(document).ready(function(){


   $(document).delegate(".uusiRiviSubmit","click",function(){

	// <-- tarkista , aloitus ja lopetus
	var lomake  = [{
		aloitan 	: $("#Mobile_aloitan").val(),
		loppui 		: $("#Mobile_loppui").val(),
		tid 		: $("#Mobile_tid").val(),
	}];

	var isLine = '';
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/on_olemassa',
           type: "POST",
	   async: false,
	   data: lomake[0],
           success: function(data){
		data = JSON.parse(data);
		console.log(data);
		if(data !== '')
		isLine = data;
           }
        });

	if(isLine !== '')
	{
		alert(isLine);
		return false;
	}
	// tarkista , aloitus ja lopetus -->


	// <-- check paallekain
	var count	= 0;
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/mobile/check_paallekkainMobile',
		  data:{ tid : $('#Mobile_tid').val(), pvm : $('#pvm').val(), alku : $("#Mobile_aloitan").val(), loppu : $("#Mobile_loppui").val() },
		  type:'POST',
		  async: false,
		  success:function(data){
			data = JSON.parse(data);
			console.log(data);
			if(data > 0)
			count = data;
	   	},
		error:function(data){
			console.log(data);
	    	}
	  });

	  if(count > 0){
		var r = confirm('Aika päällekkäin, haluatko jatkaa');
		if(!r){
			return false;
		}
	  }
	// check paallekain -->




    var Mobile_aloitan = $("#Mobile_aloitan").val();
    var Mobile_loppui = $("#Mobile_loppui").val();
    var Mobile_kohde_kannasta = $("#Mobile_kohde_kannasta").val();
    var Mobile_status = $("#Mobile_status").val();
/*
    var Mobile_tuoteID = $("#Mobile_tuoteID option:selected").val();

    if (Mobile_tuoteID  === '') {
        $('#Mobile_tuoteID').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
*/
    if (Mobile_aloitan  === '__:__') {
        $('#Mobile_aloitan').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (Mobile_loppui  === '__:__') {
        $('#Mobile_loppui').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
/*
    if (Mobile_kohde_kannasta  === '') {
        $('#Mobile_kohde_kannasta').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
*/
    if (Mobile_status  === '') {
        $('#Mobile_status').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

		$('.uusiRiviSubmit').remove();
		$('#mobile-form').submit();
		return false;
  });

  $(document).on('submit', '#mobile-form', function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/mobile/uusirivi',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){

			console.log(data);
			var divID = data.split("_");

			if( divID )
			blockUpdater(divID);

			$('#showres').modal('hide');
		
			//setTimeout(function(){document.location.href = "index";},500);
			//return false;
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });


	e.preventDefault(); 
  });

  $(document).delegate(".uusiTot","click",function(){

	if( 
		$('#Toteutuneet_status option:selected').val() == 3 
		&& $('#Toteutuneet_kohde_kannasta').val() === '' 
		&& $('#Toteutuneet_osoite').val() === '' 
	)
	{
		alert('Valitse osoite!')
		return false;
	}

	$(this).remove();
	$('#toteutuneet-form').submit();

  });

  $(document).on('submit', '#toteutuneet-form', function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );

	  $.ajax({
		  url: 'create',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){

			var divID = data.split("_");
			console.log(divID);

			if( divID )
			blockUpdater(divID);

			$('#showres').modal('hide');

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });


	e.preventDefault(); 
  });

  $(document).delegate(".updTot","click",function(){
		$(this).remove();
		$('#toteutuneet-form-upd').submit();
  });

  $(document).on('submit', '#toteutuneet-form-upd', function(e) {

	console.log( $( this ).serializeArray() );
	console.log( e.target[0].value );

	//alert(e.target[0].value)
	  $.ajax({
		  url: 'update?id='+e.target[0].value,
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){

			var divID = data.split("_");
			console.log(divID);

			if( divID )
			blockUpdater(divID);
	
			$('#showres').modal('hide');

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




$("#showres").draggable();

$(document).delegate(".totRivi","click",function(){

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

});



$(document).delegate(".poistaTot","click",function(){

	var thisVal = $(this).attr("rivi");
	var divID = $(this).attr("for").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/deletebyajax',
           type: "POST",
	   data : { "id" : thisVal },
           success: function(data){
		//console.log(data);

	  	blockUpdater(divID);

           },
	        error:function(data){
		console.log(data);
	  }
        });


});


$(document).delegate(".poistaRivit","click",function(){

	var thisVal = $(this).attr('id');
	var divID = $(this).attr("for").split("_");

	var r = confirm('Oletko varmaa?');
	if(r){
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/poista_luetut_toteutuneet',
           type: "POST",
           data: { "id" : thisVal },
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);

	  	blockUpdater(divID);
		$('#showres').modal('hide');

           }
        });
	}
});




 $(document).delegate(".sirraToteutuun","click",function(){

	$(this).hide();
	$(this).closest('.fullRivi').addClass('bg-success');
	var laatikot = '';
      	var forThis = $(this).prevAll('.tv_edit').attr('id').split("_");
        $.ajax({
           url: 'siirra_toteutuun?id=' + forThis[1],
           //type: "POST",
	   //data: { id : forThis[1] },
           success: function(data){
		data = JSON.parse(data);
		console.log(data);
		if(data['OK'])
		{
			var did = data['did'].split("_");
			laatikot = did[1]+'_'+did[2];
			var divID = [did[1],did[2]];

		        $.ajax({
		           url: 'totpvmtid',
		           type: "GET",
			   data: { pvm : did[1], tid : did[2] },
		           success: function(data){
				d = JSON.parse(data);
				if(d['laatikot'])
				{
					//console.log(laatikot);
					$('#'+laatikot).html(d['laatikot']);
					blockUpdater(divID);
		
				}
		           }
		        });


		}
           }
        });

 });


  function blockUpdater(divID){

		var thisDID = divID[0]+'_'+divID[1];

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/toteutuneet/totpvmtid',
			type:'GET',
			data: { "pvm" : divID[0], "tid" : divID[1] },
			  success:function(data){
			  data = JSON.parse(data);
			  //console.log(data);

				dataUpdater(thisDID, data);

			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

  }

  function dataUpdater(thisDID, data)
  {
		var pvmSuunn 		= $('#yhteensaPvm_'+thisDID).find('.pvmSuunn').attr('total');
		var toteutuneetTunnit 	= parseFloat(data['toteutuneetTunnit']);
		var ero 		= eroaika(pvmSuunn, toteutuneetTunnit);

	 	$('#'+thisDID).html(data['laatikot']);
	 	$('#yhteensaPvm_'+thisDID).find('.pvmTot').attr('total', data['toteutuneetTunnit']).html(sprint(toteutuneetTunnit));
	 	$('#yhteensaPvm_'+thisDID).find('.pvmEro').attr('total', data['toteutuneetTunnit']).html(sprint(ero));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaTyotunnit').attr('total', data['tyotunnit']).html(sprint(data['tyotunnit']));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaMatkat').attr('total', data['matkat']).html(sprint(data['matkat']));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaLounaat').attr('total', data['lounaat']).html(sprint(data['lounaat']));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaIlta').attr('total', data['ilta']).html(sprint(data['ilta']));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaYo').attr('total', data['yo']).html(sprint(data['yo']));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaSu').attr('total', data['su']).html(sprint(data['su']));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaSL').attr('total', data['sl']).html(sprint(data['sl']));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaSPL').attr('total', data['spl']).html(sprint(data['spl']));
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.allaLS').attr('total', data['ls']).html(sprint(data['ls']));

		// <-- Hyvaksyn nappin varten
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('tyotunnit', data['tyotunnit']);
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('matka', data['matkat']);
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('lounaat', data['lounaat']);
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('tyoilta', data['ilta']);
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('tyoyo', data['yo']);
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('tyosu', data['su']);
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('sl', data['sl']);
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('spl', data['spl']);
	  	$('#yhteensaPvmAllaTaulu_'+thisDID).find('.esittele_tyotunnit').attr('ls', data['ls']);
		//     Hyvaksyn nappin varten -->


		// <-- Week
  		var suunnWeek 	= 0;
  		var totWeek 	= 0;
  		var tyotunnitWeek = 0;
  		var matkatWeek 	= 0;
  		var lounaatWeek = 0;
		var iltaWeek	= 0;
		var yoWeek	= 0;
		var suWeek	= 0;
		var SLWeek	= 0;
		var SPLWeek	= 0;
		var LSWeek	= 0;

  		$( ".yhteensaPvm_"+data['week'] ).each(function( i ) {
			suunnWeek += parseFloat($(this).find('.pvmSuunn').attr('total'));
			totWeek += parseFloat($(this).find('.pvmTot').attr('total'));
		});

  		$( ".yhteensaPvmAllaTaulu_"+data['week'] ).each(function( i ) {
			tyotunnitWeek += parseFloat($(this).find('.allaTyotunnit').attr('total'));
			matkatWeek += parseFloat($(this).find('.allaMatkat').attr('total'));
			lounaatWeek += parseFloat($(this).find('.allaLounaat').attr('total'));
			iltaWeek += parseFloat($(this).find('.allaIlta').attr('total'));
			yoWeek += parseFloat($(this).find('.allaYo').attr('total'));
			suWeek += parseFloat($(this).find('.allaSu').attr('total'));

			SLWeek += parseFloat($(this).find('.allaSL').attr('total'));
			SPLWeek += parseFloat($(this).find('.allaSPL').attr('total'));
			LSWeek += parseFloat($(this).find('.allaLS').attr('total'));

		});

	  	$('.suunnWeek_'+data['week']).html(sprint(suunnWeek)+'<br>'+num(suunnWeek));
	  	$('.totWeek_'+data['week']).html(sprint(totWeek)+'<br>'+num(totWeek));
	  	$('.tyotunnitWeek_'+data['week']).html(sprint(tyotunnitWeek)+'<br>'+num(tyotunnitWeek));
	  	$('.matkatWeek_'+data['week']).html(sprint(matkatWeek)+'<br>'+num(matkatWeek));
	  	$('.lounaatWeek_'+data['week']).html(sprint(lounaatWeek)+'<br>'+num(lounaatWeek));

	  	$('.iltaWeek_'+data['week']).html(sprint(iltaWeek)+'<br>'+num(iltaWeek));
	  	$('.yoWeek_'+data['week']).html(sprint(yoWeek)+'<br>'+num(yoWeek));
	  	$('.suWeek_'+data['week']).html(sprint(suWeek)+'<br>'+num(suWeek));

	  	$('.SLWeek_'+data['week']).html(sprint(SLWeek)+'<br>'+num(SLWeek));
	  	$('.SPLWeek_'+data['week']).html(sprint(SPLWeek)+'<br>'+num(SPLWeek));
	  	$('.LSWeek_'+data['week']).html(sprint(LSWeek)+'<br>'+num(LSWeek));
		//     Week -->



		// <-- Footer
  		var suunnFoot 	= 0;
  		var totFoot 	= 0;
  		var tyotunnitFoot = 0;
  		var matkatFoot 	= 0;
  		var lounaatFoot = 0;
		var iltaFoot	= 0;
		var yoFoot	= 0;
		var suFoot	= 0;
		var SLFoot	= 0;
		var SPLFoot	= 0;
		var LSFoot	= 0;


  		$( ".forFooter" ).each(function( i ) {
			suunnFoot += parseFloat($(this).find('.pvmSuunn').attr('total'));
			totFoot += parseFloat($(this).find('.pvmTot').attr('total'));
		});

  		$( ".forFooterAlla" ).each(function( i ) {
			tyotunnitFoot += parseFloat($(this).find('.allaTyotunnit').attr('total'));
			matkatFoot += parseFloat($(this).find('.allaMatkat').attr('total'));
			lounaatFoot += parseFloat($(this).find('.allaLounaat').attr('total'));
			iltaFoot += parseFloat($(this).find('.allaIlta').attr('total'));
			yoFoot += parseFloat($(this).find('.allaYo').attr('total'));
			suFoot += parseFloat($(this).find('.allaSu').attr('total'));

			SLFoot += parseFloat($(this).find('.allaSL').attr('total'));
			SPLFoot += parseFloat($(this).find('.allaSPL').attr('total'));
			LSFoot += parseFloat($(this).find('.allaLS').attr('total'));
		});

	  	$('#yhteensaFooterTaulu').find('.suunnFoot').html(sprint(suunnFoot)+'<br>'+num(suunnFoot));
	  	$('#yhteensaFooterTaulu').find('.totFoot').html(sprint(totFoot)+'<br>'+num(totFoot));
	  	$('#yhteensaFooterTaulu').find('.tyotunnitFoot').html(sprint(tyotunnitFoot)+'<br>'+num(tyotunnitFoot));
	  	$('#yhteensaFooterTaulu').find('.matkatFoot').html(sprint(matkatFoot)+'<br>'+num(matkatFoot));
	  	$('#yhteensaFooterTaulu').find('.lounaatFoot').html(sprint(lounaatFoot)+'<br>'+num(lounaatFoot));

	  	$('#yhteensaFooterTaulu').find('.iltaFoot').html(sprint(iltaFoot)+'<br>'+num(iltaFoot));
	  	$('#yhteensaFooterTaulu').find('.yoFoot').html(sprint(yoFoot)+'<br>'+num(yoFoot));
	  	$('#yhteensaFooterTaulu').find('.suFoot').html(sprint(suFoot)+'<br>'+num(suFoot));

	  	$('#yhteensaFooterTaulu').find('.SLFoot').html(sprint(SLFoot)+'<br>'+num(SLFoot));
	  	$('#yhteensaFooterTaulu').find('.SPLFoot').html(sprint(SPLFoot)+'<br>'+num(SPLFoot));
	  	$('#yhteensaFooterTaulu').find('.LSFoot').html(sprint(LSFoot)+'<br>'+num(LSFoot));
		//     Footer -->


  }



  function eroaika(pvmSuunn, toteutuneetTunnit)
  {
	if(pvmSuunn > toteutuneetTunnit)
		return pvmSuunn-toteutuneetTunnit;
	else
		return toteutuneetTunnit-pvmSuunn;
  }

  function sprint(Myseconds)
  {
	var totalNumberOfSeconds = Myseconds;
	var hours = parseInt( totalNumberOfSeconds / 3600 );
	var minutes = parseInt( (totalNumberOfSeconds - (hours * 3600)) / 60 );
	var seconds = Math.floor((totalNumberOfSeconds - ((hours * 3600) + (minutes * 60))));
	var result = (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes);

	if(Myseconds > 0)
		var res = result;
	else
		var res = '';

	return res;
  }

  function num(Myseconds)
  {
	var totalNumberOfSeconds = Myseconds;
	var hours = parseFloat( totalNumberOfSeconds / 3600 ).toFixed(2);

	if(Myseconds > 0)
		var res = hours;
	else
		var res = '';

	return res;
  }


});

