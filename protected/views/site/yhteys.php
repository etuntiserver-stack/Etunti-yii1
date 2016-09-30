<?php
/*
   if(isset($_POST['nimi']) and isset($_POST['sahkoposti']) and isset($_POST['puhelin'])){

	$mod=new MuutYhteydenotot;

	$mod->nimi = $_POST['nimi'];
	$mod->yritys = $_POST['yritys'];
	$mod->puhelin = $_POST['puhelin'];
	$mod->sahkoposti = $_POST['sahkoposti'];
	$mod->viesti = $_POST['viesti'];

	$mod->save();

	echo '<h3>Kiitos viestistä!</h3>';
   }

?>

<div class="row">
  <div class="col-lg-3">

     <div id="lisaa">
    <BR>
	<form action="#" id="lahetysLomake" method="POST">
	<label>Nimi</label><span class="required">*</span>
	<input type="text" class="form-control" name="nimi" id="nimi">
	<label>Yritys</label>	
	<input type="text" class="form-control" name="yritys">
	<label>Puhelin</label><span class="required">*</span>
	<input type="text" class="form-control" name="puhelin" id="puhelin">
	<label>Sähköposti</label><span class="required">*</span>	
	<input type="text" class="form-control" name="sahkoposti" id="sahkoposti">
	<label>Viesti</label>	
	<textarea class="form-control" name="viesti" rows="6"></textarea>

	<BR>

	<input type="submit" class="btn btn-primary" value="LÄHETÄ">
	</form>

     </div>
  </div>
</div>


<script type="text/javascript">
$( document ).ready(function() {

$('#lahetysLomake').on('submit',function(e) {
 
  var nimi = $("#nimi").val();
  var puhelin = $("#puhelin").val();
  var sahkoposti = $("#sahkoposti").val();

    if (nimi  === '') {
        $('#nimi').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (puhelin  === '') {
        $('#puhelin').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (sahkoposti  === '') {
        $('#sahkoposti').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

});

});
</script>
