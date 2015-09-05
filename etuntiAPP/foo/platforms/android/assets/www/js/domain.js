
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

        window.requestFileSystem(LocalFileSystem.PERSISTENT, 0, gotFS, fail);


	    function gotFS(fileSystem) {
	        fileSystem.root.getFile("etunti.cfg", null, gotFileEntry, fail);
	    }
	
	    function gotFileEntry(fileEntry) {
	        fileEntry.file(gotFile, fail);
	    }
	
	    function gotFile(file){
	        readDataUrl(file);
	        readAsText(file);
	    }
	
	    function readDataUrl(file) {
	        var reader = new FileReader();
	        reader.onloadend = function(evt) {
	            console.log("Read as data URL");
	            console.log(evt.target.result);
	
	        };
	        reader.readAsDataURL(file);
	    }
	
	    function readAsText(file) {
	        var reader = new FileReader();
	        reader.onloadend = function(evt) {
	            console.log("Read as text");
	            console.log(evt.target.result);

		    document.getElementById('domain').value = evt.target.result;
	
	        };
	        reader.readAsText(file);
	    }
	
	    function fail(evt) {
	        console.log(evt.target.error.code);
	    }
 },
  
  
 
};

app.initialize();
