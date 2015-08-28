<?php


?>

<div class="row">
  <div class="alert alert-info col-lg-4 col-lg-offset-3">
	<button id="t_aloitan" class="btn btn-primary">Työ alkaa</button>
	<button id="t_loppui" class="btn btn-primary">Työ loppu</button>
	<br><br>
	<button id="m_aloitan" class="btn btn-primary">Matka alkaa</button>
	<button id="m_loppui" class="btn btn-primary">Matka loppu</button>
	<br><br>
	<button id="l_aloitan" class="btn btn-primary">Lounastauko alkaa</button>
	<button id="l_loppui" class="btn btn-primary">Lounastauko loppu</button>
	<br><br>

   <?php
    $model=new Sivexkuitti;
    $list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi')), 'imei', 'tekijan_nimi');

    echo '<select id="tekija" class="btn btn-default form-control">';
    foreach($list as $key=>$val){
    echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

	<textarea cols="100" rows="10" class="form-control" id="result"></textarea>
  </div>
</div>


	<input type="hidden" id="server" value="<?php echo Yii::app()->getBaseUrl(true); ?>">

 


<script type="text/javascript">
$(document).ready(function(){

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

  var domain = "<?php echo Yii::app()->user->domain; ?>";
  var url = $("#server").val()+"/index.php/api/mob";
  var puh_nro = "0449304851";
  var versio = "0.47";
  var tag = "36073245411209220";
  var gps = "000000";

  var imei = $("#tekija").val();

  $('#tekija').change(function(){ 
	imei = $("#tekija").val();
  });


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
	tietoja: "0",
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

























$('#status').click(function(){ 

	var getData = '';
	var status = '';
	var KantaID = '';


/*
$.ajaxSetup({
    beforeSend: function(xhr) {
        xhr.setRequestHeader("X_USERNAME", "demo");
        xhr.setRequestHeader("X_PASSWORD", "111111");
        console.log(xhr);
    }, 
});
*/
	$.ajax({
	    url : url+'/imei/'+imei,
	    type:"GET",
	    data: domainData,

	    success:function(data, textStatus, XMLHttpRequest) {
	      console.log(data);
	      getData = JSON.parse(data);
	
	      if(getData['Kohde'] === null)
	      {
	      	console.log("Ei ole mitään avoina");
		$("#result").val("Ei ole mitään avoina");
	      } else {
		KantaID = getData['Kohde'];
	      	console.log(KantaID + " on avoina");
	      	$("#result").val("Kohde: "+KantaID + "  on avoina. Status: " +getData['Status']);
	      }

	    },
    	    error: function(textStatus, errorThrown) {
	    	console.log(textStatus.responseText);
		$("#result").val(textStatus.responseText);
 	    }
	});

});




});
</script>




</html>

