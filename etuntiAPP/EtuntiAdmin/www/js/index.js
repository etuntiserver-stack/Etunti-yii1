$(document).ready(function(){

  var login = localStorage.getItem('login');
  if(login == "true")
    $('#domainBlokki').hide();


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

        	if(data == 'loginOk')
		{
		  window.location.href="kalut.html";
		  localStorage.setItem('login', true);
		} else {
		  window.location.href="asetukset.html";
		  localStorage.setItem('login', false);
		}

    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	   }
        });
  }




});
