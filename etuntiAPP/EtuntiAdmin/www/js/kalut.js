$(document).ready(function(){

  var server = 'http://etunti.fi';
  var url = server+"/index.php/api/mob";
  var domain = localStorage.getItem('domain');

  $('#luoAsiakas').click(function(){

	$('#eriTyokalut').hide('slow');
        $.ajax({
           url: url+'/adminkalut?dom='+domain,
	   type:'POST',
 	   data: { "luolomakke" : "true" },
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


  $(document).delegate(".btn-primary","click",function(e){


        $.ajax({
           url: url+'/adminkalut?dom='+domain,
	   type:'POST',
 	   data: $('#asiakkaat-form').serialize(),
           success: function(data){
		data = JSON.parse(data);
   	alert(data)
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	   }
        });


  });





});
