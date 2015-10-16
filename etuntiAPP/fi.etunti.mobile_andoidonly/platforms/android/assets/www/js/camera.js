
  var urlForCamera = server + "/index.php/site/uploadfromphone&domain=" + domain;

var appCam = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    onDeviceReady: function() {


	navigator.camera.getPicture(onSuccess, onFail, { 
	    quality: 100,
	    destinationType: Camera.DestinationType.DATA_URL
	});
	 
	function onSuccess(imageData) {
	    alert(urlForCamera);
	  $.post( urlForCamera, {data: imageData}, function(data) {
	    alert("Image uploaded!");
	  });
	/*move_uploaded_file($_FILES["file"]["tmp_name"], '/path/to/file');*/
	}
	 
	function onFail(message) {
	    //alert('Failed because: ' + message);
	   window.location.href='index.html';
	}


    }
};

appCam.initialize();



