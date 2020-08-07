$(document).ready(function(){


  $('.ajaat').mask('00.00.0000 00:00',{
        placeholder: "pp.kk.vvvv tt:mm"
  });

/*
$(".vietyovuoroon").click(function(){

	var thisVal = $(this).attr("pvmtid");
	window.open(location.protocol + "//" + location.host + '/index.php/tyovuoroot?pvmtid='+thisVal+'#'+thisVal);

});
*/
$(document).delegate(".pvmupdate","click",function(){

	var thisID = $(this).attr("for").split("_");
	var st = $("#"+thisID[0]+"_"+thisID[1]).attr("status");
	var request = $("#"+thisID[0]+"_"+thisID[1]).attr("request");
	var thisVal = $("#"+thisID[0]+"_"+thisID[1]).val();

	if((request == 'loppui') && (st == '1') && (thisVal != ''))
	status = 3;
	else
	status = st;

	if(thisVal == ''){
	alert("VIRHE! Ei voidaan olla tyhjänä");
	return false;
	}

        $.ajax({
           url: 'updatetime',
           type: "POST",
           data: { "id" : thisID[1], "request" : request, "value" : thisVal, "status" : status },
           success: function(data){
		console.log(data);
		var getData = data.split("//");

		if(getData[0] == 'aloitan')
		{
		   $('#altxt_'+thisID[1]).html(' '+getData[1]).addClass("text-success collapsed");
		   //$('#'+thisID[0]+'_'+thisID[1]).removeClass("btn-default").addClass("text-success");
		   $('#alshow_'+thisID[1]).removeClass('in');
		}

		if(getData[0] == 'loppui')
		{
		   $('#lptxt_'+thisID[1]).html(' '+getData[2]).addClass("text-success");
		   //$('#'+thisID[0]+'_'+thisID[1]).removeClass("btn-default").addClass("text-success");
		   $('#ltshow_'+thisID[1]).removeClass('in');
		}

        	$.ajax({
	           url: 'kesto?id='+thisID[1],
	           success: function(data){
		      	console.log(data);
			$('#kesto_'+thisID[1]).html("<strong>"+data+"</strong>").addClass("text-success");	
	           }
	        });

           }
        });

});

$(document).delegate(".openkohde","click",function(){

	var thisID = $(this).attr("id");
	var forid = $(this).attr("for");
	var riviid = $(this).attr("for").split("_");

        $.ajax({
           url: 'showkohteet',
           type: "POST",
           data: { "id" : riviid[1], "thisID" : thisID },
           success: function(html){
		html = JSON.parse(html);
		$('#'+forid).html(html);
           }
        });

});

$(document).delegate("#Kohteet_id","change",function(){

	var kohdenID = $("#sainkohdenID").val().split("_");
	var thisText = $(this).find("option:selected").text();
	var thisVal = $(this).val();
	var Mobile = {fromMob: "true",kohdenID: thisVal,kohde_kannasta: thisText};

       $.ajax({
          url: "update?id="+kohdenID[1],
          type: "POST",
          data: Mobile,
          success: function(html){
	//console.log(html);
	$("#vaihto_kohttisID_"+kohdenID[1]).addClass("text-success").text(thisText);
	//alert(thisText)
          }
       });
		
});


$(document).delegate(".poistaKohde","click",function(){

	var forRivi = $(this).attr("for");
	var riviid = $(this).attr("for").split("_");
	if(confirm('Oletko varmaa?'))
	{
        $.ajax({
           url: 'poistaKohde',
           type: "POST",
           data: { "id" : riviid[1] },
           success: function(html){
		$("#"+forRivi).remove();
           }
        });
	}

});

$(document).delegate(".latikkoAsetukset .tv_edit","click",function(){
   	var thisId = $(this).attr('id');
        $.ajax({
           url: 'get_tyovuorot_day',
           type: "GET",
           data: { "id" : thisId },
           success: function(html){
		html = JSON.parse(html);
		//console.log(html)
		window.location.href= location.protocol + "//" + location.host + "/index.php/tyovuoroot/beta?mode=vko&week="+html['week']+"&year="+html['year']+"&tid="+html['tid'];
           }
        });
});




/*
$(".etsi_tekijan_nimi").change(function(){
	var thisVal = $(this).val();
        $.ajax({
           url: "index",
	   type:'POST',
	   data: { "etsi_tekijan_nimi" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

$(".etsi_kohteet").change(function(){
	var thisVal = $(this).val();
        $.ajax({
           url: "index",
	   type:'POST',
	   data: { "etsi_kohteet" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});

$(".etsi_pvm").on('blur', function() {
	var thisVal = $(this).val();
	if(!thisVal)
	var thisVal = 'kaikki';

        $.ajax({
           url: "index",
	   type:'POST',
	   data: { "etsi_pvm" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});
*/

});
