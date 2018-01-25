$(document).ready(function(){


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
	url: 'osoite',
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


var step = 41;
var count = step;
function counter(){
    count += -1;


	var time = count*15;
	var minutes = "0" + Math.floor(time / 60);
	var seconds = "0" + (time - minutes * 60);
	jaljella =  minutes.substr(-2) + ":" + seconds.substr(-2);
	$('#countTimer').text('Aikajäljellä: '+jaljella);

    if(count < 1)
    window.location.href="index?keskeyta=true";
}
setInterval(counter, "15000");



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
			console.log(data);
			count = step;

		} else {
			//$('#fullLomake').hide('slow');
			$('.btncheckPosti').hide('slow');
			$('#loytynytOsoitteet').html(data);
			localStorage.setItem('onkokohde', data);
			//console.log(data);
			count = step;

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

$(document).delegate('#sahkoposti', "keyup", function() {

   var sahkoposti = $(this).val();

   if(sahkoposti.length > 5)
   {
	onkokohde(sahkoposti);
   }

});



$(".tallennaUusi").click(function(){


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


   $.ajax({
	url: 'luouusi',
	data:{ "sahkoposti" : sahkoposti, osoite : osoite, postinumero : postinumero, kaupunki : kaupunki, puhelin : puhelin, yhteyshenkilo : yhteyshenkilo, lisatietoja : lisatietoja, tyyppi : tyyppi, yrityksen_nimi : yrityksen_nimi, y_tunnus : y_tunnus },
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
			window.location.href="maksu";
		} else if(data == 'nytRedirectValmis'){
			window.location.href="valmis";
		} else {
			alert(data);
		}

   	},
	error:function(data){
		console.log(data);
    	}
    });

  

});




});
