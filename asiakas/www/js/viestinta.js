$(document).ready(function(){

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }


  if(localStorage.getItem('loginOK'))
  {
	var sendData = loginArr;
	sendData['asiakasID'] = asiakasID;

	var katsottu_id = '';
	if(getUrlVars()["id"])
		katsottu_id = getUrlVars()["id"];
alert(katsottu_id)

	console.log(sendData);
        $.ajax({
           url: url+'/viestinta?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
        	//console.log(data);
		var d = JSON.parse(data);
		if(d['lista'])
		{
			$('#resultLaatiko').html(d['lista']);
			reloadSkin();
			reloadDatepicker();
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });

   }


    $(document).delegate('#laheta_viesti', 'click', function() {
	var otsikko = $( "#otsikko" ).val();
	var teksti = $( "#teksti" ).val();
	if( otsikko.length == 0 ){
		$( "#otsikko" ).css({"border" : "1px red solid"}).focus();
		return false;
	}
	if( teksti.length == 0 ){
		$( "#teksti" ).css({"border" : "1px red solid"}).focus();
		return false;
	}

	sendData['uusi_viesti'] = true;
	sendData['otsikko'] = otsikko;
	sendData['teksti'] = teksti;

        $.ajax({
           url: url+'/viestinta?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		if(d['lahetys_status'])
		{
			$('#lahetys_lomake').hide(370);
			$('#lahetys_status').html(d['lahetys_status']).show(370);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });
    });
 

    $(document).delegate('.laheta_vastaus', 'click', function() {
	var id = $( this ).closest('.vastaus').find('#id').val();
	var vastaus = $( this ).closest('.vastaus').find('#vastaus').val();
	if( vastaus.length == 0 ){
		$( "#vastaus" ).css({"border" : "1px red solid"}).focus();
		return false;
	}
	sendData['id'] = id;
	sendData['vastaus'] = vastaus;

        $.ajax({
           url: url+'/viestinta?domain='+domain,
	   type:'POST',
 	   data: sendData,
           success: function(data){
		var d = JSON.parse(data);
		if(d['lahetys_status'])
		{
			$('#lahetys_lomake').hide(370);
			$('#lahetys_status').html(d['lahetys_status']).show(370);
		}
    	   },
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
    	   }
        });
    });
 

    function reloadSkin()
    {
	if( localStorage.getItem('headerSkin') ){
	$('.myBgColors').removeClass(localStorage.getItem('headerSkin'));
	$('.myBgColors').addClass(localStorage.getItem('headerSkin'));
	}
    }
    function reloadDatepicker()
    {
        $( ".datepickerFI" ).datetimepicker({
         format : 'DD.MM.YYYY',
	 locale: 'fi',
        });
    }

});
