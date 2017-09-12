var eDico = function() {

	   if( $('#p22').length )
	   {
        	$.ajax({
	           url: location.protocol + "//" + location.host + '/index.php/site/edico_etusivulle',
	           //type: "POST",
		   //async: false,
		   //data: { month1 : month1, month2 : month2 },
	           success: function(data){
			data = JSON.parse(data);
			console.log(data);

			if(data['avoin_vinkit'] !== ''){
			$('#p22 table tbody').append('<tr><td>'+ data['avoin_vinkit'] +'</td><td><a href="'+location.protocol + '//' + location.host + '/index.php/vinkkiExtranet">Avoimet vinkit</a></td></tr>');
			}
			if(data['kasittelyt_vinkit'] !== ''){
			$('#p22 table tbody').append('<tr><td>'+ data['kasittelyt_vinkit'] +'</td><td><a href="'+location.protocol + '//' + location.host + '/index.php/vinkkiExtranet">Käsitellyt vinkit</a></td></tr>');
			}
			if(data['avoin_palautteet'] !== ''){
			$('#p22 table tbody').append('<tr><td>'+ data['avoin_palautteet'] +'</td><td><a href="'+location.protocol + '//' + location.host + '/index.php/palautteet">Avoimet palautteet</a></td></tr>');
			}
			if(data['kasittelyt_palautteet'] !== ''){
			$('#p22 table tbody').append('<tr><td>'+ data['kasittelyt_palautteet'] +'</td><td><a href="'+location.protocol + '//' + location.host + '/index.php/palautteet">Käsitellyt palautteet</a></td></tr>');
			}

			if(data['avoin_vinkit'])
			$('#p22').show();

	           }
	        });
	   }

}

var toteututhismonth = function () {

	    // <-- SiteController avoimet_kohteet
            $.ajax({
               url: 'toteututhismonth',
	       //async: false,
               success: function(data){
		    try {
			    var d = JSON.parse(data);
			    $("#toteututhismonth").replaceWith(d);
		    } catch (e) {
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?toteututhismonth'
		    }
               },
               error: function(data){
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?toteututhismonth'
	       }
            });
	    // SiteController avoimet_kohteet -->
}

var tehdyttunnittanaan = function () {

	    // <-- SiteController avoimet_kohteet
            $.ajax({
               url: 'tehdyttunnittanaan',
	       //async: false,
               success: function(data){
		    try {
			    var d = JSON.parse(data);
			    $("#tehdyttunnittanaan").replaceWith(d);
		    } catch (e) {
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?tehdyttunnittanaan'
		    }
               },
               error: function(data){
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?tehdyttunnittanaan'
	       }
            });
	    // SiteController avoimet_kohteet -->
}

var suunnitteltutunnittanaan = function () {

	    // <-- SiteController avoimet_kohteet
            $.ajax({
               url: 'suunnitteltutunnittanaan',
	       //async: false,
               success: function(data){
		    try {
			    var d = JSON.parse(data);
			    $("#suunnitteltutunnittanaan").replaceWith(d);
		    } catch (e) {
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?suunnitteltutunnittanaan'
		    }
               },
               error: function(data){
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?suunnitteltutunnittanaan'
	       }
            });
	    // SiteController avoimet_kohteet -->
}

var suunniteltulistatanaan = function () {

	    // <-- SiteController avoimet_kohteet
            $.ajax({
               url: 'suunniteltulistatanaan',
	       //async: false,
               success: function(data){
		    try {
			    var d = JSON.parse(data);
			    $("#suunniteltulistatanaan").replaceWith(d);
		    } catch (e) {
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?suunniteltulistatanaan'
		    }
               },
               error: function(data){
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?suunniteltulistatanaan'
	       }
            });
	    // SiteController avoimet_kohteet -->
}

var viestittanaan = function () {

	    // <-- SiteController avoimet_kohteet
            $.ajax({
               url: 'viestittanaan',
	       //async: false,
               success: function(data){
		    try {
			    var d = JSON.parse(data);
			    $("#viestittanaan").replaceWith(d);
		    } catch (e) {
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?viestittanaan'
		    }
               },
               error: function(data){
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?viestittanaan'
	       }
            });
	    // SiteController avoimet_kohteet -->
}


var avoimetKohteet = function () {

	    // <-- SiteController avoimet_kohteet
            $.ajax({
               url: 'avoimet_kohteet',
	       //async: false,
               success: function(data){
		    try {
			    var d = JSON.parse(data);
			    $("#avoimet_kohteet").replaceWith(d);
		    } catch (e) {
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?avoimetKohteet'
		    }
               },
               error: function(data){
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?avoimetKohteet'
	       }
            });
	    // SiteController avoimet_kohteet -->
}

var kayttajaonline = function () {

	    // <-- SiteController avoimet_kohteet
            $.ajax({
               url: 'kayttajaonline',
	       //async: false,
               success: function(data){
		    try {
			    var d = JSON.parse(data);
			    $("#kayttajaonline").replaceWith(d);
		    } catch (e) {
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?kayttajaonline'
		    }
               },
               error: function(data){
		            window.location.href=location.protocol + "//" + location.host + '/index.php/site/site_error?kayttajaonline'
	       }
            });
	    // SiteController avoimet_kohteet -->
}

	    eDico();
	    toteututhismonth();
	    tehdyttunnittanaan();
	    suunnitteltutunnittanaan();
	    suunniteltulistatanaan();
	    viestittanaan();
	    avoimetKohteet();
	    kayttajaonline();

