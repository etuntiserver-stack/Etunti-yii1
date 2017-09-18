$(document).ready(function(){

   // <-- edico_tehdyt_tyot
   if(loginFull.edico_tehdyt_tyot == ''){
   	$(".naytaToteutuneetTunnit").hide();
   }
   console.log('edico_tehdyt_tyot: '+ loginFull.edico_tehdyt_tyot);
   //     edico_tehdyt_tyot -->



  $(".lahetatunnukset").click(function(){

	var yritystunnus = $("#yritystunnus").val();
	if(yritystunnus === '')
	{
		$("#yritystunnus").css({"border" : "2px red solid"}).focus();
		return false;
	}
	var sahkoposti = $("#sahkoposti").val();
	if(sahkoposti === '')
	{
		$("#sahkoposti").css({"border" : "2px red solid"}).focus();
		return false;
	}

        $.ajax({
           url: url+'/recovery?domain='+yritystunnus,
	   type:'POST',
 	   data: { sahkoposti : sahkoposti },
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);
		if(d['ok'])
		{
			$('#modalBody').html('<p><div class="alert alert-success"><h1>Kiitos</h1><h3>Tunnukset ovat lähettäneet sähköpostille: '+sahkoposti+'</h3></div></p>');
			//$('.closeModal').click(function(){ window.location.reload();  });
		} else {
			$('#modalBody').html('<p><div class="alert alert-danger"><h3>Virhe!!!</h3></div></p>');

		}

			$('#myModal').modal('show'); 

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        		console.log(xhr.responseText);
    	   }
        });


  });


  $("#home").click(function(){
	window.location.href='index.html';
  });

  $("#asetukset").click(function(){
	window.location.href='asetukset.html';
  });

  $("#toteutuneetTunnit").click(function(){
	window.location.href='toteutuneet.html';
  });

});
