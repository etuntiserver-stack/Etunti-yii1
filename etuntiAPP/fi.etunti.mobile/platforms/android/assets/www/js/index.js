
var app = {
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
 app.onNfc, // tag successfully scanned
 function (status) { // listener successfully initialized
 //app.display("Tap a tag to read its id number.");
 },
 function (error) { // listener fails to initialize
 app.display("NFC reader failed to initialize " +
 JSON.stringify(error));
 }
 );
 },
  
 onNfc: function(nfcEvent) {
 var tag = nfcEvent.tag;
 //app.nro(nfc.bytesToHexString(tag.id));
 app.nro(tag.id);
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


$(document).ready(function(){

	var domain = document.getElementById('domain').value;
	var email = document.getElementById('email').value;
	var salasana = document.getElementById('salasana').value;

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "getObjbyTag", tag : $('#tagginro').val(),  email : email, salasana : salasana },
           success: function(data){
        	console.log(data);
		//$("#result2").html(data).show();
		var sp = data.split("//");
		if(sp[2] == 'ok')
		{
		   $('#os').val(sp[0]);
		   $('#kohdenID').val(sp[1]);
		}
		if(sp[2] == 'error')
		{
		   $('#os').val('').css({"border" : "2px #f14010 solid"}).focus();
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

app.initialize();
