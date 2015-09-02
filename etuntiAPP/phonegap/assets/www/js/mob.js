$(document).ready(function(){


  var query = location.href.substring((location.href.indexOf('?')+1), location.href.length);
  if(location.href.indexOf('?') < 0) query = '';
      querysplit = query.split('&');
      query = new Array();
  for(var i = 0; i < querysplit.length; i++)
  {
        var namevalue = querysplit[i].split('=');
        namevalue[1] = namevalue[1].replace(/\+/g, ' ');
        query[namevalue[0]] = unescape(namevalue[1]);
  }

  var DeviceIMEI = query['imei'];
  var my_location = query['location'];

  if(query['domain'])
    var domain = query['domain'];
  else
    var domain = $("#domain").val();

    var tag = '';
  if(query['tag'])
    tag = query['tag'];



  var server = "http://etuntifw.azurewebsites.net";
  var url = server+"/index.php/api/mob";
  var puh_nro = "0449304851";
  var versio = "0.47";
  var imei = DeviceIMEI;



  $("#result").append("DeviceIMEI : " + DeviceIMEI + "\n");
  $("#result").append("Location : " + my_location + "\n");


  $("#viestintaURL").click(function(){
	window.location.href='viestinta.html?domain='+domain+'&imei='+DeviceIMEI+'&location='+my_location;
  });

  $("#tvuoro").click(function(){
	window.location.href='tvuoro.html?domain='+domain+'&imei='+DeviceIMEI+'&location='+my_location;
  });



$(".sw").bootstrapSwitch({
	size: "large",
	onColor: "info",
	onText: "Lopetus",
	offText: "Aloitus"
});

$('input[name="tyo"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("tyo_al",1);
  } else {
	row("tyo_lp",3);
  }
	$('.full').css({"opacity" : "0.3"});
});

$('input[name="matka"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("matka_al",2);
  } else {
	row("matka_lp",2);
  }
	$('.full').css({"opacity" : "0.3"});
});

$('input[name="lounas"]').on('switchChange.bootstrapSwitch', function(event, state) {
  console.log(state); 
  if(state == true)
  {
	row("lounas_al",10);
  } else {
	row("lounas_lp",10);
  }
	$('.full').css({"opacity" : "0.3"});
});


function allHide(){
	$("#osoite").hide('slow');
	$('#tyo').hide('slow');
	$('#matka').hide('slow');
	$('#lounas').hide('slow');
}
function allShow(){
	$('#tyo').show('slow');
	$('#matka').show('slow');
	$('#lounas').show('slow');
}
function allTilasetHide(){
	$("#tyo_kohde").hide();
	$("#matka_kohde").hide();
	$("#lounas_kohde").hide();
}


function curDateTime(){

  	var date = new Date();
	var year = date.getFullYear();
	var month = date.getMonth();
	month = month < 10 ? "0" + (month+1) : month+1;
	var day = date.getDate();
	day = day < 10 ? "0" + (day) : day;
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getSeconds();

	return (day + "." + month + "." + year + " " + hours + ":" + minutes + ":" + seconds);
}






function row(tilanne,st){

   if((tilanne == 'tyo_al') & (st == 1))
   {
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'tyo_lp') & (st == 3))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }
   if((tilanne == 'matka_al') & (st == 2))
   {
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'matka_lp') & (st == 2))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }
   if((tilanne == 'lounas_al') & (st == 10))
   {
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'lounas_lp') & (st == 10))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }

   	var postData = {
		domain: domain,
		imei: imei,
		asiakas_num: versio+"_"+tag,
		puh_numero: puh_nro,
		bluetooth_name: "0",
		sim_serial_number: "0",
		subscriber_id: "0",
		my_location: my_location,
		osoite: "0",
		kohde_kannasta: $("#os").val(),
		kohdenID: $("#kohdenID").val(),
		aloitan: al,
		loppui: lp,
		viesti: "xxx",
		etaisyys: "0",
		status: st,
		tietoja: "",
		hyvaksytty: "0",
	};


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: postData,
           success: function(data){
        	console.log(data);

		var query = data.split("//");
		 if(query[4] == 'tagnumerror')
		 {
		   $("#result2").val("<h2>"+query[4]+ " on avoina</h2>").show();
		   return false;
		 }

		set();
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	}
        });

}




        document.addEventListener("deviceready", onDeviceReady, false);
        
        function onDeviceReady() 
        {
            requestFileSystem(LocalFileSystem.PERSISTENT, 0, onSuccess, onError);
        }
        
        function onSuccess(fileSystem) 
        {   
            var directoryEntry = fileSystem.root;
            
            //lets create a file named readme.txt. getFile method actually creates a file and returns a pointer(FileEntry) if it doesn't exist otherwise just returns a pointer to it. It returns the file pointer as callback parameter.
            directoryEntry.getFile("readme.txt", {create: true, exclusive: false}, function(fileEntry){
                //lets write something into the file
                fileEntry.createWriter(function(writer){
                    writer.write("This is the text inside readme file");
                }, function(error){
                    console.log("Error occurred while writing to file. Error code is: " + error.code);
                });
            }, function(error){
                console.log("Error occurred while getting a pointer to file. Error code is: " + error.code);
            });
        }
        
        function onError(evt)
        {
            console.log("Error occurred during request to file system pointer. Error code is: " + evt.code);
        }



  $(".aloita").click(function(){

  	domain = $("#domain").val();

    document.addEventListener("deviceready", onDeviceReady, false);

    // Cordova is ready
    //
    function onDeviceReady() {
        window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, gotFS, fail);
    }

    function gotFS(fileSystem) {
        fileSystem.root.getFile("etunti.cfg", {create: true, exclusive: false}, gotFileEntry, fail);
    }

    function gotFileEntry(fileEntry) {
        fileEntry.createWriter(gotFileWriter, fail);
    }

    function gotFileWriter(writer) {
        writer.onwriteend = function(evt) {
            console.log("contents of file now 'some sample text'");
            writer.truncate(11);  
            writer.onwriteend = function(evt) {
                console.log("contents of file now 'some sample'");
            };
        };
        writer.write(domain);
    }

    function fail(error) {
        console.log(error.code);
    }

	set();

  });


// Checker
    if(domain != ''){
       	set();
    } else {
   	$("#result").append("Domaini puutuu \n");
    }

    function set() {

	$("#odotta").html("<h1>ODOTTA</h1>");
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "testi", imei : imei, my_location : my_location, tag : tag },
           success: function(data){
        	//console.log(data);
		var sp = data.split("//");
		$("#odotta").hide();
		$("#footer").show('slow');
		$("#result").append(sp+"\n");
		$('.full').css({"opacity" : "1"});
		$("#domainBlokki").hide();
		$("#tekija").html("<h4 class='text-warning'>"+sp[5]+"</h4>");

		if((sp[0] == '3') || (sp[0] == '2') || (sp[0] == '10')){
		  $("#osoite").show('slow');
		  allShow();
		  allTilasetHide();
		}
		if(sp[0] == '1')
		{
		  allHide();
		  $("#tyo").show('slow');
		  $("#tyo_kohde").html(sp[1]).show('slow');
		  $('.tyo').bootstrapSwitch('state', true, true);
		} 
		if(sp[0] == '2.1')
		{
		  allHide();
		  $("#matka").show('slow');
		  $("#matka_kohde").html(sp[1]).show('slow');
		  $('.matka').bootstrapSwitch('state', true, true);
		}
		if(sp[0] == '10.1')
		{
		  allHide();
		  $("#lounas").show('slow');
		  $("#lounas_kohde").html(sp[1]).show('slow');
		  $('.lounas').bootstrapSwitch('state', true, true);


		}
		$("#os").val(sp[4]);
		$("#kohdenID").val(sp[6]);

		$("#result").hide();
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result").val(xhr.responseText).show();
    	}
        });
    }



$("#os").keyup(function(){

  var thisKey = $(this).val();
  var lengThis = thisKey.length;

  if(lengThis > 0)
  {
	$("#getListFromServer").show('slow');
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "osoitevaihto", imei : imei, my_location : my_location, thisKey : thisKey },
           success: function(data){
        	console.log(data);
		//$("#result").val(data);
		$("#getListFromServer").html(data);

  		$("#list").change(function(){

			$("#getListFromServer").hide('slow');
			$("#os").val($( "#list option:selected" ).text());
			$("#kohdenID").val($( "#list option:selected" ).val());
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
		$("#result").val(xhr.responseText).show();
    	}
        });

  } else {
			$("#list").hide();
  }

});



/* check messages */
if(domain != ''){
        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "checkviesti", imei : imei, my_location : my_location },
           success: function(data){
        	console.log(data);
		if(parseInt(data) > 0)
		{
		  setInterval(blink, 1000);
		  $("#viestintaURL").addClass("text-danger");
		} else {
		  $("#viestintaURL").removeClass("text-danger");
		}
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result2").html(xhr.responseText).show();
    	}
        });
}


  var blink = function(){
        $('#viestintaURL').toggle();
  };




});
