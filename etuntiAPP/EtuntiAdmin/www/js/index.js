$(document).ready(function(){



  $("#odotta").html("<img src='img/icon.png'>");
  setTimeout(tiedot,3000); 
  function tiedot(){
	domain	= $("#domain").val();
	tunnus = $("#tunnus").val();
	salasana = $("#salasana").val();
	if(domain !== '' && tunnus !== '' && salasana !== '' )
	   checkTunnus(domain,tunnus,salasana);
	else
	   return false;
  }

  function checkTunnus(domain,tunnus,salasana)
  {

        $.ajax({
           url: url+'/check_admin?dom='+domain,
	   type:'POST',
 	   data: { tunnus : tunnus, salasana : salasana },
           success: function(data){
		data = JSON.parse(data);
        	alert(data);

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	   }
        });
  }

});
