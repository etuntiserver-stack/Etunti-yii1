/*

  document.addEventListener('deviceready', this.readFile, true);
  function readFile() {
        window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, gotFS, fail);
	    function gotFS(fileSystem) {
	        fileSystem.root.getFile("etunti.cfg", null, gotFileEntry, fail);
	    }
	    function gotFileEntry(fileEntry) {
	        fileEntry.file(gotFile, fail);
	    }
	    function gotFile(file){
	        readAsText(file);
	    }	
	    function readAsText(file) {
	        var reader = new FileReader();
	        reader.onloadend = function(evt) {
	            console.log("Read as text");
	            console.log(evt.target.result);
		

	   		 var spFile = evt.target.result.split("//");
		
			 document.getElementById('domain').value=spFile[0];
			 document.getElementById('email').value=spFile[1];
			 document.getElementById('salasana').value=spFile[2];

			 appNFC.initialize();

	        };
	        reader.readAsText(file);
	    }
	    function fail(evt) {
	        console.log(evt.target.error.code);
	    }
  }
*/


var appNFC = {
 // Application Constructor
 initialize: function() {
 this.bindEvents();
 console.log("Starting NFC Reader app");
 },
 // Bind Event Listeners
 bindEvents: function() {
 document.addEventListener('deviceready', this.onDeviceReady, false);
 },
 // deviceready Event Handler 
 onDeviceReady: function() {
 //app.receivedEvent('deviceready');
 nfc.addTagDiscoveredListener(
 appNFC.onNfc, // tag successfully scanned
 function (status) { // listener successfully initialized
 //app.display("Tap a tag to read its id number.");
 },
 function (error) { // listener fails to initialize
 appNFC.display("NFC reader failed to initialize " +
 JSON.stringify(error));
 }
 );
 },
  
 onNfc: function(nfcEvent) {
 var tag = nfcEvent.tag;
 //app.nro(nfc.bytesToHexString(tag.id));
 appNFC.nro(tag.id);
 },
  
 
 nro: function(ms) {


   function toDec( x ){
      var val = 0;
      var res = 0;
      var go = 0;
      var fa = 1;
       // reverse var i = x.length - 1; i >= 0; i--
       for (var i = 0; i < x.length; i++) {  
          res = x[i] & 0xff;
	  go = bigInt(res).times(fa).plus(val);
          val += bigInt(res).times(fa);
          fa *= 256;

	  //console.log(go);
       }

        return go;
   }

   	document.getElementById('tagginro').value = toDec(ms);
	tag = toDec(ms);


$(document).ready(function(){


    var domain = '';
    var email = '';
    var salasana = '';


    if(localStorage.getItem('domain'))
	  domain=localStorage.getItem('domain');
    if(localStorage.getItem('email'))
	  email=localStorage.getItem('email');
    if(localStorage.getItem('salasana'))
	  salasana=localStorage.getItem('salasana');



	var tag = document.getElementById('tagginro').value;

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "getObjbyTag", tag : tag,  email : email, salasana : salasana },
           success: function(data){
        	console.log(data);
		//$("#result2").html(data).show();
		var sp = data.split("//");
		if(sp[2] == 'ok')
		{
		   $('#os').val(sp[0]).css({"border" : "2px green solid"});
		   $('#kohdenID').val(sp[1]);
		   $("#camButtons").show('slow');

		   setTimeout(clearAndExit,120000); //2min

		}
		if(sp[2] == 'error')
		{
		   $('#os').css({"border" : "2px #f14010 solid"}).focus();
		   $('body,html').css({"background" : "#FF9900","color":"white"});
		   $('#kohdenID').val('0');
		   //alert(sp[0]+" "+sp[1])
		}
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		//$("#result2").html(xhr.responseText).show();
    	}
        });
});

  



 },

 clear: function() {
 	tagginro.innerHTML = "";
 },
  
 
};



appNFC.initialize();


function clearAndExit(){
	//alert('bdfff');
	navigator.app.exitApp();
}
