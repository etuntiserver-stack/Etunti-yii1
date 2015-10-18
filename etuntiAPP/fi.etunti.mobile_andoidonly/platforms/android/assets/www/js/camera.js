/*
  var domain = '';
  var kohdenID = '';
  var tyontekija = '38';
  var email = '';
  var salasana = '';
  var my_location = '';
  var path = '';

  function urlForCamera() 
  { 
	domain = document.getElementById('domain').value;
	kohdenID = document.getElementById('kohdenID').value;

	  if(domain)
	  {
		path = server + "/index.php/site/uploadfromphone?dom=" + domain + "&tyontekija=" + tyontekija + "&kohdenID=" +kohdenID;		
		return path;
	  } else {
		alert("Domaini puutuu");
	  }
   }
*/


$(document).ready(function(){


  $("#odotta").html("<img src='img/search.gif'>");
  setTimeout(tiedot,3000); 

  function tiedot(){


	domain	= $("#domain").val();
	email = $("#email").val();
	salasana = $("#salasana").val();


	if(my_location == '') my_location = $("#location").val();

	if((domain != '') & (email !='') & (salasana != ''))
	{
		set();

	} else {
		//setTimeout(function(){document.location.href = "asetukset.html";},500);
		$("#domainBlokki").show();
		return false;
 	}
	
  }


function set(){

  var thisKey = '%';

	$("#getListFromServer").show(370);
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "osoitevaihto", my_location : my_location, thisKey : thisKey, email : email, salasana : salasana },
           success: function(data){
        	console.log(data);
		//$("#result2").val(data).show();
		if(data)
		{
		$("#odotta").hide();
		}

		$("#otsikko").html('<h2>Kuvien lähettäminen</h2>').show();
		$("#getListFromServer").html("<label>Valitse kohde</label><br>" + data + "<br>");

  		$("#list").change(function(){

			$("#getListFromServer").hide(370);
			$("#os").val($( "#list option:selected" ).text());
			$("#kohdenID").val($( "#list option:selected" ).val());

			if($("#kohdenID").val() !== '')
			{
				$("#osoiteFromBase").html("<h4>" + $( "#list option:selected" ).text()+ "</h4><br>");
				$("#camButtons").show('slow');
			}

		});

		var listSize = $('#list option').size();

			$("#valitseOsoite").text("Löyty: "+(listSize-1)+" kohteita");

		if(listSize > 1)
		{
			$("#list").show();
		} else {
			$("#list").hide();
		}

		$("#result").hide();
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").val(xhr.responseText).show();
    	}
        });

 
}


$("#camButton").click(function(){
	appCam.initialize();
});




});
