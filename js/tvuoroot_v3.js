$(document).ready(function(){

/*
$('#fixTable td').hover(function()
{
     $(this).find('.latikkolisatiedot').show();
}, function()
{ 
     $(this).find('.latikkolisatiedot').hide();
});
*/
$(document).delegate("#fixTable td","dblclick",function(){
	$(this).find('.katsokaikki').click();
});

$(document).delegate(".katsokaikki","click",function(){

	var forThis = $(this).attr("for");
	var pvm = $(this).attr("pvm");
	var tid = $(this).attr("tid");
	var from = $(this).attr("from");
	var kohteet_siivous = $(this).attr("kohteet_siivous");
	var asiakas = $(this).attr("asiakas");
	var kohde = $(this).attr("kohde");
	var etusukunimi = $(this).attr("etusukunimi");

	var xhr = new XMLHttpRequest();
	xhr.open("POST", location.protocol + "//" + location.host + '/index.php/tyovuoroot/didnew', true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onload = function () {
		try {
			d = JSON.parse(xhr.responseText);
		} catch (e) {
			alert('Kohdetta ei löydy! Päivitä sivu!');
			return false;
		}
		//console.log(xhr.responseText)
		$("#showall").modal().html('' +
		'<div id="modal-form-all" class="popup-basic-left popup-basic popup-lg admin-form mfp-with-anim mfp-hide">' +
		'<div class="panel">' +
            	'<div class="panel-heading">' +
			'<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:170%">' +
				'<span aria-hidden="true">&times;</span>' +
			'</button>' +
              	'<span class="panel-title"><i class="fa fa-clock-o"></i>' +
		pvm + ' ' + etusukunimi + ' &nbsp;' +
		'</span>' +
            	'</div>' +
              	'<div class="panel-body p25">' +
		d+
              	'</div>' +
		'</div>' +
		'</div>');
	};
	xhr.onerror = function () {
		alert('Kohdetta ei löydy! Päivitä sivu!');
	};
	xhr.send('pvm='+pvm+'&tid='+tid+'&from='+from+'&kohteet_siivous='+kohteet_siivous+'&asiakas='+asiakas+'&kohde='+kohde);
});

$("#autoInsert").click(function(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/autoinsert',
           //type: "POST",
           //data: { "pvm" : pvm, "tid" : tid },
           success: function(html){
		$('#showres').modal().html(html);
           }
        });

});

$("#autoRemove").click(function(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/autoremove',
           //type: "POST",
           //data: { "pvm" : pvm, "tid" : tid },
           success: function(html){
		$('#showres').modal().html(html);
           }
        });

});

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }

if(getUrlVars()["tv_id"]){
	var thisVal = getUrlVars()["tv_id"];

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update?id='+thisVal,
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal().html(html);
           },
	   error:function(data){
		alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
}

$(document).delegate(".tv_edit","click",function(){
	var thisVal = $(this).attr("id").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update?id='+thisVal[1],
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal().html(html);
           },
	   error:function(data){
		alert('Kohdetta ei löydy! Päivitä sivu!');
	   }
        });
});



$(document).delegate(".valitseKokopaiva","click",function(){
	var pvm = $(this).attr("pvm");
	var tid = $(this).attr("tid");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/valitse_kokopaiva',
           type: "POST",
           data: { "pvm" : pvm, "tid" : tid },
           success: function(data){
		console.log(data);
		muisti();
           }
        });
   	return false;
});

$(document).delegate(".luominen","click",function(){
	var pvm = $(this).attr("pvm");
	var tid = $(this).attr("tid");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/create',
           type: "POST",
           data: { "pvm" : pvm, "tid" : tid },
           success: function(html){
		$('#showres').modal().html(html);
           }
        });
   	return false;
});


jQuery.clearKaikki = function clearKaikki(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muisticlear',
	   type:'POST',
	   data: { "clear" : 1 },
           success: function(data){
        	console.log(data);

		$(".muistissa").each(function() {
		     $(this).css({"opacity":"1"});
		     $(this).removeClass("muistissa").addClass("muistin");
		});

		$(".muokkausLi").hide();
		$(".mplus").removeClass().addClass("forCopy");
		$(".mcut").removeClass().addClass("forCut");
		$('#muistissa').html('')

    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });


}

$(document).delegate(".muistin","click",function(){

	var thisFor = $(this).attr('for');
	//var thisVal = $(this).attr('for').split('_');
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muistin',
	   type:'POST',
	   data: { "id" : thisFor },
           success: function(data){
        	//console.log(data);
	  	muisti();
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

});



muisti();
function muisti(){

	var dat = '';

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/muistissa',
           success: function(data){
        	//console.log(data);
	
		if(data != 'muistityhja')
		{
		var parseData = 0;
		parseData = data.split(',');
		$(parseData).each(function(index, value) {

			if( $('#'+value).hasClass('ei_saa_muokata') ){ return true; }

     			$('#'+value).css({"opacity":"0.4"});
     			$('#'+value).removeClass("muistin").addClass("muistissa");
		});

			$(".muokkausLi").show();
			$(".forCopy").removeClass("forCopy").addClass("mplus glyphicon glyphicon-plus text-success link");
			$(".forCut").removeClass("forCut").addClass("mcut glyphicon glyphicon-transfer text-success link");
		
		}
		return false;
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

}


$(document).delegate(".mplus","click",function(){
  	var thisID = $(this).attr("id");
	parent.postMessage( "doit//"+thisID, "*");
});

$(document).delegate(".mcut","click",function(){
	var thisID = $(this).attr("id");
	parent.postMessage( "doit//"+thisID, "*");
});

$(document).delegate(".clear","click",function(){
	jQuery.clearKaikki();
});

$(document).delegate(".trash","click",function(){
	var thisID = 'forRemove_1_1';
	parent.postMessage( "doit//"+thisID, "*");
});





window.addEventListener('message', function(e) {
  var edata = e.data.split('//');
  if(edata[0] == 'doit')
  {
	var thisID = edata[1].split('_');


  var newPvm	= thisID[1];
  var newTid	= thisID[2];
  var doWhat	= 0;

  if(thisID[0] == 'forCopy')
  doWhat 	= { 'copy' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(thisID[0] == 'forCut')
  doWhat 	= { 'cut' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(thisID[0] == 'forRemove')
  doWhat 	= { 'remove' : "true", "newPvm" : newPvm, "newTid" : newTid };
  if(thisID[0] == 'checkThis')
  doWhat 	= { 'checkThis' : "true", "newPvm" : newPvm, "newTid" : newTid };

  if(thisID[0] == 'forRemove')
  {
	var r = confirm('Haluatko varmasti poistaa tämän?');
	if(!r)
	return false;
  }

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/operatio',
	   type:'POST',
	   data: doWhat,
           success: function(data){
        	//console.log(data);

		if(data !== '')
		{
			var sp = JSON.parse(data).split('//');
			if(sp[0])
			{
				if(sp[0] == 'Error'){
					alert(sp[1]);
					return false;
				}
				$('#'+newPvm+'_'+newTid).html(JSON.parse(sp[0]));
				
				if(thisID[0] == 'forCopy' && parent.location.href.match(/index/))
				{
					var ThisHeight = $('#'+newPvm+'_'+newTid).height();
					var FirstHeight = $('#first_'+newTid).height(ThisHeight);
				}

				if(thisID[0] == 'forCopy' && parent.location.href.match(/tv2/))
				{
					var ThisHeight = $('#'+newPvm+'_'+newTid).height();
					var FirstHeight = $('#first_'+newPvm).height(ThisHeight);
				}

			}
	
			if(sp[1])
			{
	  			var parseData = sp[1].split(',');
	  			$(parseData).each(function(index, value) {
					console.log(value);
					$('#'+value).remove();
				});
				parseData = '';
			}
	
		  	jQuery.clearKaikki();

		}


    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

        	console.log('doIt funktio '+thisID[0]+' ok!');
  }

});










vkolopputCheck();
function vkolopputCheck(){

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/vkolopput',
           success: function(data){
		//console.log(data);
		if(data == 1)
		$('#vkolopput').removeClass('btn-default').addClass('btn-success');
		if(data == 0)
		$('#vkolopput').removeClass('btn-success').addClass('btn-default');


		$('#vkolopput').click(function(){

		        $.ajax({
		           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/vkolopchange',
			   type:'POST',
			   data: { "nyt" : data },
		           success: function(data){
		        	console.log(data);
				window.location.reload();
		    	   },
		    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
			    	console.log(XMLHttpRequest);
		 	   }
		        });
		
		});


           }
        });
   	return false;

}



$("#viikkonhyppaminen, #vuodenhyppaminen").change(function() {
	var thisVal = $(this).val();
	window.location.href=thisVal;
	return false;
});




$("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

});




});
