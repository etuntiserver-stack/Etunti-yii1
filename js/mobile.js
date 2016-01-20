$(document).ready(function(){


$(".vastaanotettu").click(function(){

	var thisVid = $(this).attr("for");
	var id = $(this).attr("for").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/viestinta/vastaanotettu?id='+id[1],
           success: function(data){
		console.log(data);
		$("#"+thisVid).hide('slow');
           }
        });

});

$(".vietyovuoroon").click(function(){

	var thisVal = $(this).attr("pvmtid");
	window.open(location.protocol + "//" + location.host + '/index.php/tyovuoroot?pvmtid='+thisVal+'#'+thisVal);

});

$(".pvmupdate").click(function(){


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
		   $('#altxt_'+thisID[1]).html(getData[1]).addClass("text-success");
		   //$('#'+thisID[0]+'_'+thisID[1]).removeClass("btn-default").addClass("text-success");
		   $('#alshow_'+thisID[1]).hide('slow');
		}

		if(getData[0] == 'loppui')
		{
		   $('#lptxt_'+thisID[1]).html(getData[2]).addClass("text-success");
		   //$('#'+thisID[0]+'_'+thisID[1]).removeClass("btn-default").addClass("text-success");
		   $('#ltshow_'+thisID[1]).hide('slow');
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

$(".openkohde").click(function(){

	var thisID = $(this).attr("id");
	var forid = $(this).attr("for");
	var riviid = $(this).attr("for").split("_");

        $.ajax({
           url: 'showkohteet',
           type: "POST",
           data: { "id" : riviid[1], "thisID" : thisID },
           success: function(html){
		$('#'+forid).html(html);
           }
        });

});

$(".poistaKohde").click(function(){

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
