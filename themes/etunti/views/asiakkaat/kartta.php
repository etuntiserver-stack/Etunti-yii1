<?php

?>

<div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Kartta'); ?></h2> 


	<div class="row">
	 <div class="col-sm-12">
	  <div class="form-inline">

		<div class="pull-right">
			 <button class="synkronoi_gps_sijainti btn btn-primary myBgColors"><?php echo Yii::t('main', 'Synkronoi kohteet kartalle'); ?></button>
		</div>

		<?php echo Yii::t('main', 'Alue/Kaupunki'); ?> <input type="text" class="form-control form-group" id="alueKaupunki">
		 <button class="tallennaLatLng btn btn-primary myBgColors"><?php echo Yii::t('main', 'Hae'); ?></button>

		<select id="tilanneKartalla" class="form-control">
		<option><?php echo Yii::t('main', 'Tilanne'); ?></option>
		<option value="aktiiviset"><?php echo Yii::t('main', 'Aktiiviset tänään'); ?></option>
		<option value="toteutetut"><?php echo Yii::t('main', 'Toteutetut tänään'); ?></option>
		<option value="kaikki"><?php echo Yii::t('main', 'Kaikki tänään'); ?></option>
		</select>
		<input type="hidden" id="getTila" value="<?php if(isset($_GET['tila'])) echo $_GET['tila']; ?>">
	  </div>
	 </div>
	</div>

	<div class="row">
	  <div id="kartta">
		<iframe scrolling="no" style="width: 100%; height: 700px; border: none" id="iframekartta"></iframe>
	  </div>
	</div>

</div>

<script type="text/javascript">
$(document).ready(function(){


$(".synkronoi_gps_sijainti").click(function(){

	$(".synkronoi_gps_sijainti").html('Odota..');

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/site/synkronoi_gps_sijainti',
           type: "POST",
           data: { sunc : "true" },
           success: function(data){
		//data = JSON.parse(data);
		console.log(data);
		if(data)
		$(".synkronoi_gps_sijainti").html('Synkronoitu '+data+' kohdetta');
           },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });

});

$(".tallennaLatLng").click(function(){
	localStorage.setItem('alueKaupunki', $("#alueKaupunki").val());
	window.location.reload();
});

	var keskusta = '';
	var tila = '';
	
	if($('#getTila').val() !== '')
	{
		tila = $('#getTila').val();
		$("#tilanneKartalla").val(tila);
	}

	if (localStorage.getItem('alueKaupunki') !== "") {
		$("#alueKaupunki").val(localStorage.getItem('alueKaupunki'));
		keskusta = localStorage.getItem('alueKaupunki');
	}


	$('#iframekartta').attr('src', location.protocol + '//' + location.host + '/index.php/kohteet/googlemap?tila='+tila+'&nomenu=true&center='+keskusta);


$("#tilanneKartalla").change(function(){
	var tila = $(this).val();
	window.location.href="kartta?tila="+tila;
});


});
</script>
