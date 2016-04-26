$(document).ready(function(){

var lang = [];

        $.ajax({
	   async: false,
           url: url+'/lang?dom='+domain,
	   type:'POST',
 	   data: { lang : etunti_language },
           success: function(data){
		var d = JSON.parse(data);

		$.each(d, function( index, value ) {
		  lang[index] = value;
		});

    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	}
        });




  $("#odotta").html("<img src='img/icon.png'>");
  setTimeout(tiedot,1000); 

  function tiedot(){


	domain	= $("#domain").val();
	email = $("#email").val();
	salasana = $("#salasana").val();


	if(my_location == '') my_location = $("#location").val();

	if((domain != '') & (email !='') & (salasana != ''))
	{
		$("#odotta").hide();

	} else {
		//setTimeout(function(){document.location.href = "asetukset.html";},500);
		$("#domainBlokki").show();
		return false;
 	}
	
  }


$("#os").keyup(function(){

  var thisKey = $(this).val();
  var lengThis = thisKey.length;

  if(lengThis > 0)
  {
	$("#getListFromServer").show(370);
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "osoitevaihto", my_location : my_location, thisKey : thisKey, email : email, salasana : salasana, lang : etunti_language },
           success: function(data){
        	console.log(data);
		//$("#result").val(data);
		$("#getListFromServer").html("<label>"+ lang['Valitse_osoite'] +"</label><br>" + data + "<br>");

  		    $("#list").change(function(){
			$("#os").val($( "#list option:selected" ).text());
			$("#kohdenID").val($( "#list option:selected" ).val());
			var listObj = $( "#list option:selected" ).text();
			if(($("#kohdenID").val() !== '') & (listObj != ''))
			{
				$("#osoiteFromBase").html("<h4>" + listObj + "</h4><br>");
				$("#os").hide();
				$("#camButtons").show('slow');
				$("#getListFromServer").remove();
				return false;
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

  } else {
			$("#list").hide();

  }

});



$("#camButton").click(function(){
	appCam.initialize();
});




});
