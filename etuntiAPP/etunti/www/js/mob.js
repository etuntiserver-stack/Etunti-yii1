$(document).ready(function(){


$(".tyo").bootstrapSwitch({
	size: "large",
	onColor: "info",
	onText: "Lopetus",
	offText: "Aloitus"
});


function curDateTime(){

  	var date = new Date();
	var year = date.getFullYear();
	var month = date.getMonth();
	month = month < 10 ? "0" + (month+1) : month+1;
	var day = date.getDate();
	day = day < 10 ? "0" + (day) : day;
	var hours = date.getHours();
	var minutes = date.getMinutes();
	var seconds = date.getSeconds();

	return (day + "." + month + "." + year + " " + hours + ":" + minutes + ":" + seconds);
}

  var domain = "sivex";
  var url = $("#server").val()+"/index.php/api/mob";
  var puh_nro = "0449304851";
  var versio = "0.47";
  var tag = "36073245411209220";
  var gps = "000000";
  var imei = "353888067886268";


function row(tilanne,st,gps){

   if((tilanne == 'tyo_al') & (st == 1))
   {
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'tyo_lp') & (st == 3))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }
   if((tilanne == 'matka_al') & (st == 2))
   {
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'matka_lp') & (st == 2))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }
   if((tilanne == 'lounas_al') & (st == 10))
   {
	var al 	= curDateTime();
	var lp 	= '';
   }
   if((tilanne == 'lounas_lp') & (st == 10))
   {
	var al 	= '';
	var lp 	= curDateTime();
   }

   	var postData = {
	domain: domain,
	imei: imei,
	asiakas_num: versio+"_"+tag,
	puh_numero: puh_nro,
	bluetooth_name: "0",
	sim_serial_number: "0",
	subscriber_id: "0",
	my_location: gps,
	osoite: "0",
	kohde_kannasta: "Testti Osoite",
	kohdenID: "0",
	aloitan: al,
	loppui: lp,
	viesti: "xxx",
	tekijan_nimi: "Roman Sizov",
	tid: "38",
	etaisyys: "0",
	status: st,
	tietoja: "testi",
	hyvaksytty: "0",
	};


        $.ajax({
           url: url+'/imei?dom='+domain,
	   type:'POST',
 	   data: postData,
           success: function(data){
        	console.log(data);
		$("#result").val(data);
    	},
    		error:function (xhr, ajaxOptions, thrownError){
        	console.log(xhr.responseText);
		$("#result").val(xhr.responseText);
    	}
        });

}

$('#tyo input[type="checkbox"]:checked').change(function(){ 

        alert('ok')
    
});

$('#t_aloitan').click(function(){ 
	row("tyo_al",1,gps)
});

$('#t_loppui').click(function(){ 
	row("tyo_lp",3,gps)
});

$('#m_aloitan').click(function(){ 
	row("matka_al",2,gps)
});

$('#m_loppui').click(function(){ 
	row("matka_lp",2,gps)
});

$('#l_aloitan').click(function(){ 
	row("lounas_al",10,gps)
});

$('#l_loppui').click(function(){ 
	row("lounas_lp",10,gps)
});


});
