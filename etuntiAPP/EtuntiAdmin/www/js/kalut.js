$(document).ready(function(){

  var server = 'http://etunti.fi';
  var url = server+"/index.php/api/mob";
  var domain = localStorage.getItem('domain');

  $('#luoAsiakas').click(function(){

	$('#eriTyokalut').hide('slow');
        $.ajax({
           url: url+'/adminkalut?dom='+domain,
	   type:'POST',
 	   data: { "luoAsiakas" : "true" },
           success: function(data){
		data = JSON.parse(data);
		$('#eriTyokalut').html(data).show('slow');
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	   }
        });
  });


  $(document).delegate("#asiakkaat-form.btn-primary","click",function(e){


        $.ajax({
           url: url+'/adminkalut?dom='+domain,
	   type:'POST',
 	   data: $('#asiakkaat-form').serialize(),
           success: function(data){
		data = JSON.parse(data);
   		if(data == 'saveError')
		alert(data)
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").val(xhr.responseText).show();
    	   }
        });


  });


  $('#luoKohde').click(function(){

	$('#eriTyokalut').hide('slow');
        $.ajax({
           url: url+'/adminkalut?dom='+domain,
	   type:'POST',
 	   data: { "luoKohde" : "true" },
           success: function(data){
		data = JSON.parse(data);
		$('#eriTyokalut').html(data).show('slow');
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").val(xhr.responseText).show();
    	   }
        });
  });


  $(document).delegate("#kohteet-form.luoTallennaKohde","click",function(e){


        $.ajax({
           url: url+'/adminkalut?dom='+domain,
	   type:'POST',
 	   data: $('#kohteet-form').serialize(),
           success: function(data){
		data = JSON.parse(data);
   		if(data == 'saveError')
		alert(data)
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").val(xhr.responseText).show();
    	   }
        });


  });



});
