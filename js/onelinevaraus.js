
$(document).ready(function(){

  localStorage.clear();


$("#lispalvimg").click(function(){
	$('#lisapalvelulista').show('slow');
});


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

   clearAll();
   var id = $(this).val();

var t = setTimeout( function() {
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
			$("#lispalvimg").show('slow');
			$('#lisapalvelulista').html(data[1]);
		} else {
			$("#lispalvimg").hide('slow');
		}

		ajaaPalveluSave();

   	},
	error:function(data){
		console.log(data);
    	}
    });

}, 100 );

});



function clearAll(){

   $.ajax({
	url: 'palvelu_ajax',
	data:{ "clear" : "all" },
	type:'POST',
	success:function(data){
		//console.log(data);
   		$('.checkbox').removeAttr('checked');
		$('#panGetContent').html('');
   	},
	error:function(data){
		console.log(data);
    	}
    });

}


$(document).delegate("#toinen_valiko_values","change",function(){

	var thisVal 	= $(this).val().split("//");
	var otsikko 	= thisVal[0];
	var nimike 	= thisVal[1];
	var hinta 	= parseFloat(thisVal[2]);
	var kesto 	= 0;
	if(thisVal[3])
	kesto = parseFloat(thisVal[3]);
	var tyo_toimialue = $('#tyo_toimialue').val();

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ toinen_valiko : "true", otsikko : otsikko, nimike : nimike, hinta : hinta, kesto : kesto, tyo_toimialue : tyo_toimialue },
	type:'POST',
	success:function(data){
		console.log(kesto);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
		}
		tuntienTarkistus();

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
		//console.log(data);
		if(data)
		{
			$('#panGetContent').html(JSON.parse(data));
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

});
