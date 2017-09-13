
    var server = 'https://etunti.fi/';
    var domain = '';
    var tunnus = '';
    var salasana = '';
    var paketti = [];
    var loginArr = [];
    var loginFull = [];

    if(localStorage.getItem('loginOK'))
    {
	loginArr = JSON.parse(localStorage.getItem('loginOK')).loginOK[0];
	loginFull = JSON.parse(localStorage.getItem('loginOK')).loginOK;
	//console.log(loginFull.edico_tehdyt_tyot)
    } 




    //localStorage.clear();


    if(localStorage.getItem('loginOK'))
    {

	var asiakasID = JSON.parse(localStorage.getItem('loginOK')).loginOK['asiakasID'];
	
	domain = loginArr['domain'];

	$(document).ready(function(){
		if(loginArr['palvelin']){
			$('#palvelin').val(loginArr['palvelin']);
		}
		$('#domain').val(loginArr['domain']);
		$('#inputEmail').val(loginArr['tunnus']);
		$('#inputPassword').val(loginArr['salasana']);
	});

	//console.log(loginArr);
	//console.log('Asiakas: '+asiakasID);

    }

    if(loginArr['palvelin']){
		server = 'https://'+loginArr['palvelin']+'/';
    }

    // <-- Palvelin
    //var server = '../../';
    var url = server+"index.php/dico/asiakkaat";
    var versio = "";
    // Palvelin -->



    // <-- On device Ready
    document.addEventListener("deviceready", onServerReady1, false);
    function onServerReady1() {

	if(device.platform == 'iOS'){
	  $(document).ready(function(){
	    $('.navbar-fixed-top').css({'margin-top':'20px'});
	    $('.powerButton').hide();
	  });
	}

    }
    // On device Ready -->

    function exitFromApp()
    {
       navigator.app.exitApp();
    }





$(document).ready(function(){

  // <-- Login
  $("#form-signin").on('submit', function(e){

	var values = $(this).serializeArray();
	console.log(values);
	//var url = 'https://' + values[0]['value']+'/index.php/dico/asiakkaat';
	domain = values[1]['value'];

        $.ajax({
           url: url+'/login?domain='+domain,
	   type:'POST',
 	   data: $(this).serialize(),
           success: function(data){
		var d = JSON.parse(data);
		console.log(d);
		//return false;
		if(d['loginOK'])
		{
			localStorage.setItem('loginOK', JSON.stringify(d));
			localStorage.setItem('login_paketti', d['loginOK'].paketti);
			//localStorage.setItem('edico_tehdyt_tyot', d['loginOK'].edico_tehdyt_tyot);
			window.location.href="index.html";
		} else {
			$('#yllaIlmoitus').html('<h3 class="alert alert-danger">Kirjautuminen ei onnistunut. Tarkasta yritystunnus, sähköposti ja salasana</h3>');
		}
    	},
    		error: function (xhr, status, error){
        	console.log(url+'/login?domain='+domain);
    	}
        });
	e.preventDefault();
   });
  //  Login -->



	

$("body").ready(function(){



  $(".to-tilaus").click(function(e){
   if(asiakasID)
   {
	document.addEventListener("deviceready", onInAPPDeviceReady, false);
	event.preventDefault();
	function onInAPPDeviceReady() {
	    var ref = cordova.InAppBrowser.open(encodeURI(server+'index.php/onlinevaraus/index?domain='+domain+'&aid='+asiakasID), '_blank', 'location=yes');

		/*
		ref.addEventListener(
		    'loadstop',
		    function(event) {
		        ref.executeScript({
		            code: "document.getElementById('palvelu').onclick = function() {alert('button was clicked');}"
		        });
		    }
		);
		*/
	}
   }
  });

  $("#home").click(function(){
	window.location.href='index.html';
  });

  $("#asetukset").click(function(){
	window.location.href='asetukset.html';
  });

  $("#toteutuneetTunnit").click(function(){
	window.location.href='toteutuneet.html';
  });

});



   $("#eHeader").replaceWith(''+

    '<header class="navbar navbar-fixed-top navbar-shadow myBgColors">' +

      '<div class="navbar-branding">' +
        '<a class="logo" href="index.html">' +
	'<img src="img/logo.png" height="40">' +
        '</a>' +
      '</div>' +

      '<ul class="nav navbar-nav navbar-right">' +

        '<li class="dropdown menu-merge hidden" data-toggle="tooltip" data-placement="bottom" title="Valitse värit">' +
          '<a class="dropdown-toggle" data-toggle="dropdown" href="#">' +
             '<span class="fa fa-eyedropper"></span> ' +

	  '</a>' +
          '<ul class="dropdown-menu pv5 animated animated-short flipInX" role="menu">' +
            '<li>' +

  '<div id="skin-toolbox">' +
    '<div class="panel">' +
      '<div class="panel-heading">' +

      '</div>' +
      '<div class="panel-body pn">' +

        '<div class="text-dark">' +
          '<div class="col-sm-6">' +
            '<form id="toolbox-header-skin">' +
              '<h4 class="mv20">Väri</h4>' +
              '<div class="skin-toolbox-swatches">' +
                '<div class="checkbox-custom checkbox-disabled fill mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin8" checked value="">' +
                  '<label for="headerSkin8">Light</label>' +
                '</div>' +
               '<div class="checkbox-custom fill checkbox-primary mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin1" value="bg-primary">' +
                  '<label for="headerSkin1">Primary</label>' +
                '</div>' +
                '<div class="checkbox-custom fill checkbox-info mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin3" value="bg-info">' +
                  '<label for="headerSkin3">Info</label>' +
                '</div>' +
                '<div class="checkbox-custom fill checkbox-warning mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin4" value="bg-warning">' +
                  '<label for="headerSkin4">Warning</label>' +
                '</div>' +
                '<div class="checkbox-custom fill checkbox-danger mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin5" value="bg-danger">' +
                  '<label for="headerSkin5">Danger</label>' +
                '</div>' +
                '<div class="checkbox-custom fill checkbox-alert mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin6" value="bg-alert">' +
                  '<label for="headerSkin6">Alert</label>' +
                '</div>' +
                '<div class="checkbox-custom fill checkbox-system mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin7" value="bg-system">' +
                  '<label for="headerSkin7">System</label>' +
                '</div>' +
                '<div class="checkbox-custom fill checkbox-success mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin2" value="bg-success">' +
                  '<label for="headerSkin2">Success</label>' +
                '</div>' +
                '<div class="checkbox-custom fill mb5">' +
                  '<input type="radio" name="headerSkin" id="headerSkin9" value="bg-dark">' +
                  '<label for="headerSkin9">Dark</label>' +
                '</div>' +
              '</div>' +
            '</form>' +
          '</div>' +
        '</div>' +

      '</div>' +
    '</div>' +
  '</div>' +

        '<div class="form-group mn br-t p15">' +
          '<a href="#" id="clearLocalStorage" class="btn btn-primary btn-block pb10 pt10">Palauta oletusasetukset</a>' +
        '</div>' +

	'</li>' +
      '</ul>' +
     '</li>' +


        '<li class="dropdown menu-merge" id="kirjauduOikealla">' +
          '<a href="#" data-toggle="dropdown"> ' +
                 '<span id="asiakasNimi"></span> </a>' +
          '</a>' +
          '<ul class="dropdown-menu list-group dropdown-persist w250" role="menu">' +
            '<li class="list-group-item">' +
              '<a href="#" class="animated animated-short fadeInUp" id="asetukset">' +
                '<span class="fa fa-gear"></span> Omat asetukset </a>' +
            '</li>' +
            
            '<li class="list-group-item">' +
              '<a href="kohteet.html" class="animated animated-short fadeInUp">' +
                '<span class="fa fa-gear"></span> Omat kohteet </a>' +
	    '</li>' +
            '<li class="list-group-item">' +
              '<a id="vaihdaTunnus" href="#" class="animated animated-short fadeInUp">' +
                '<span class="fa fa-sign-out"></span> Kirjaudu ulos </a>' +
            '</li>' +
          '</ul>' +
        '</li>' +
        '<li class="powerButton"><a href="#" id="exitPainike" onclick="exitFromApp()"><span  class="fa fa-power-off"></span></a></li>' +
        '<li id="toggle_sidemenu_t">' +
        		'<span class="fa fa-caret-up"></span>' +
        '</li>' +
      '</ul>' +
    '</header>' 
   );

   $("#eFooter").replaceWith(''+
    '<div class="nav navbar-fixed-bottom navbar-shadow myBgColors">' +
     '<div class="container" id="footer-body">' +
      '<center>' +
      '<div class="row">' +
       '<div class="btn-group foot">' +

	   '<button class="myBgColors btn btn-default"><a href="index.html"><h2 class="fa fa-home"></h2></a></button>' +
	   '<button class="myBgColors btn btn-default"><a href="historia.html?tyyppi=naytaTyovuorot"><h2 class="fa fa-calendar"></h2></a></button>' +
	   '<button class="naytaToteutuneetTunnit myBgColors btn btn-default"><a href="historia.html?tyyppi=naytaToteutuneetTunnit"><h2 class="fa fa-check-square"></h2></a></button>' +
	   '<button class="myBgColors btn btn-default"><a href="historia.html?tyyppi=naytaVinkit"><h2 class="fa fa-thumbs-o-up"></h2></a></button>' +
	   '<button class="myBgColors btn btn-default"><a href="historia.html?tyyppi=naytaPalautteet"><h2 class="fa fa-smile-o"></h2></a></button>' +
	   '<button class="myBgColors btn btn-default"><a href="index.html?sivu=asiakirjat"><h2 class="fa fa-file-text-o"></h2></a></button>' +
	   '<button class="myBgColors btn btn-default"><a href="info.html"><h2 class="fa fa-info-circle"></h2></a></button>' +

       '</div>' +
      '</div>' +
      '</center>' +
     '</div>' +
    '</div>'
   );



   $("#vaihdaTunnus").click(function(){
 	localStorage.removeItem('loginOK');
	//localStorage.clear();
	window.location.href='index.html';
   });



   if(localStorage.getItem('loginOK'))
   {
	$('#laatikot').show(370);
	$('#kirjauduOikealla').show(370);
	$('#asiakasNimi').html(loginFull['asiakasNimi']);

   } else {
	$('#loginLomake').show(370);
   }

});
