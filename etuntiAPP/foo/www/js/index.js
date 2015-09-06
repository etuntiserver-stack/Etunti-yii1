
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
 //app.display(nfc.bytesToHexString(tag.id));
 app.nro(tag.id);
 },
  
 
 nro: function(ms) {
   function toDec( x ){
      var val = 0;
      var res = 0;
      var fa = 1;
       // reverse var i = x.length - 1; i >= 0; i--
       for (var i = 0; i < x.length; i++) {  
          res = x[i] & 0xff;
          val += res * fa;
          fa *= 256;

       }
	var par = parseFloat(val+"e-"+val.toString().length).toString().replace("0.","");
        return par;
   }
   	document.getElementById('tagginro').value = toDec(ms);


$(document).ready(function(){

	var domain = document.getElementById('domain').value;
	var imei = document.getElementById('imei').value;

        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: { check : "getObjbyTag", imei : imei, tag : toDec(ms) },
           success: function(data){
        	console.log(data);
		alert('ok');
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		alert('error');
    	}
        });
});

  



 },

 clear: function() {
 tagginro.innerHTML = "";
 },
  
 
};

app.initialize();
