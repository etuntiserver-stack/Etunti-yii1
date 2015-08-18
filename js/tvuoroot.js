$(document).ready(function(){

$(".tv_edit").click(function(){

	var thisVal = $(this).attr("id").split("_");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update?id='+thisVal[1],
           type: "GET",
           //data: {"tarjousPainike" : "true"},
           success: function(html){
		$('#showres').modal().html(html);
           }
        });

});


$('.laatikko').bind("contextmenu",function(e){
	var pvm = $(this).attr("pvm");
	var tid = $(this).attr("tid");

        $.ajax({
           url: 'create',
           type: "POST",
           data: { "pvm" : pvm, "tid" : tid },
           success: function(html){
		$('#showres').modal().html(html);
           }
        });
   	return false;
});


$(".etsi_month").on('change', function() {
	var thisVal = $(this).val();
	if(!thisVal)
	var thisVal = 'kaikki';

        $.ajax({
           url: "index",
	   type:'POST',
	   data: { "etsi_month" : thisVal },
           success: function(html){
		window.location.reload();
           }
        });
});


$(".vietyovuoroon").click(function(){

	var thisVal = $(this).attr("pvmtid");
	window.open(location.protocol + "//" + location.host + '/index.php/tyovuoroot?pvmtid='+thisVal+'#'+thisVal);

});


});
