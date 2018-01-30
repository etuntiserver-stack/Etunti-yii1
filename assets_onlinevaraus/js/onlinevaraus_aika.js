$(document).ready(function(){

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




kaksiKalenteria();


function kaksiKalenteria()
{
   $.ajax({
	url: 'aika_ajax',
	data:{ "nothing" : "true" },
	type:'POST',
	success:function(data){
		//console.log(data);
		count1 += -1;

		var time = count1*15;
		var minutes = "0" + Math.floor(time / 60);
		var seconds = "0" + (time - minutes * 60);
		jaljella =  minutes.substr(-2) + ":" + seconds.substr(-2);
		$('#countTimer').text('Aikajäljellä: '+jaljella);

		$('#kalenterit').html(JSON.parse(data));
	        $(".toolt").tooltip();

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
		//console.log(JSON.parse(data));
		$('#aikoja').html(JSON.parse(data));
		return false;
   	},
	error:function(data){
		console.log(data);
    	}
    });
    }
  }



});

