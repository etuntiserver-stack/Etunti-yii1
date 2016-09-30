$(document).ready(function(){


  $("#headers").html(
  '<div class="row">'+
    '<div class="col-sm-12">'+
     '<div class="head">'+
     	  '<div class="pull-right">'+
	    '<div id="tekija"></div>'+
	    '<div id="version" class="small"></div> '+
	  '</div>'+
	'<img src="img/logo-black.png" class="logo"><br>'+
	'<h1>Järjestelmänvalvoja</h1>'+
     '</div>'+
    '</div>'+
  '</div>');




    document.addEventListener("deviceready", onDeviceReady, false);
    function onDeviceReady() {

	if(device.platform == 'iOS'){

  $("#footlinks").html(
	'<div class="row">'+
	'<footer id="footer">'+
	'<div class="navbar navbar-default navbar-fixed-bottom">' +
	'<div class="" id="footer-body">' +
	    '<center>' +
		'<a href="#" id="home"><h2 class="glyphicon glyphicon-home"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="asetukset"><h2 class="glyphicon glyphicon-cog"></h2></a>&nbsp;&nbsp;&nbsp;' +
	    '</center>' +
	'</div>' +
	'</div>' +
	'</footer>' +
	'</div>');

	painikkeet();

	} else {

  $("#footlinks").html(
	'<div class="row">'+
	'<footer id="footer">'+
	'<div class="navbar navbar-default navbar-fixed-bottom">' +
	'<div class="" id="footer-body">' +
	    '<center>' +
		'<a href="#" id="home"><h2 class="glyphicon glyphicon-home"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" id="asetukset"><h2 class="glyphicon glyphicon-cog"></h2></a>&nbsp;&nbsp;&nbsp;' +
		'<a href="#" onclick="exitFromApp()"><h2 class="glyphicon glyphicon-new-window"></h2></a>' +
	    '</center>' +
	'</div>' +
	'</div>' +
	'</footer>' +
	'</div>');

	}

	painikkeet();
    }



function painikkeet(){

  $("#home").click(function(){
	window.location.href='index.html';
  });
  $("#asetukset").click(function(){
	window.location.href='asetukset.html';
  });
}


});
