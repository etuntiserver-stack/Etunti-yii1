<?php

?>


<br><br>

<div class="container">

<div class="row">
 <div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('main', 'Salasanan luominen'); ?></div>
    <div class="panel-body">

        <label><?php echo Yii::t('main', 'Uusi salasana')?></label>
        <input type="password" id="uusi_salasana" name="uusi_salasana" class="form-control input-lg checkpass" required autofocus>
        <label><?php echo Yii::t('main', 'Varmista uusi salasana')?></label>
        <input type="password" id="varmista_uusi_salasana" name="varmista_uusi_salasana" class="form-control input-lg checkpass" required>
	<br>
        <button class="btn btn-lg btn-primary btn-group submit" type="submit"><?php echo Yii::t('main', 'Lähetä'); ?></button>
	<div id="success"></div>
	<div id="adm_salasana_error"></div>
    </div>
  </div>
 </div>
</div>


</div>
<br><br>


<script type="text/javascript">
$(document).ready(function(){

$(".checkpass").blur(function() {
	validatePassword( $(this).val() );
});

function validatePassword(p) {

        errors = [];
    if (p.length < 8) {
        errors.push("Salasanan pitää olla vähintään 8 merkkiä pitkä."); 
    }
    if (p.search(/[a-z]/i) < 0) {
        errors.push("Salasanan pitää sisältää vähintään yksi kirjain.");
    }
    if (p.search(/[0-9]/) < 0) {
        errors.push("Salasanan pitää sisältää vähintään yksi numero."); 
    }
    if (p.search(/[a-z]/) < 0) { 
	errors.push("Salasanan pitää sisältää vähintään yksi pieni kirjain.") 
    } 
    if (p.search(/[A-Z]/) < 0) { 
	errors.push("Salasanan pitää sisältää vähintään yksi iso kirjain.") 
    }
    if (errors.length > 0) {
        $('#adm_salasana_error').html('<br><div class="alert alert-danger">' + errors.join("<br>") + '</div>' ).show();
        return false;
    }
        $('#adm_salasana_error').html('').hide();
    return true;
}


  $(".submit").click(function(){

	$('#success').html('');

	$.ajax({
	  url: 'confirm?token=<?php echo $token; ?>',
	  data: { uusi_salasana: $('#uusi_salasana').val(), varmista_uusi_salasana: $('#varmista_uusi_salasana').val() },
	  type:'POST',
	  success:function(data){

		try
		{
		   data = JSON.parse(data);
	  	   console.log(data);
		}
		catch(e)
		{
		   $('#success').html('<br><span class="alert bg-warning btn-block">Tarkista tiedot</span>');
		   return false;
		}


		if(data == 'varmistaUusi')
		{
			$('#success').html('<br><span class="alert alert-danger btn-block">Salasanat eivät täsmää</span>');
		}
		if(data == 'emptyUusi')
		{
			$('#success').html('<br><span class="alert alert-warning btn-block">Uusi salasana on tyhjä</span>');
		}
		if(data == 'error')
		{
			$('#success').html('<br><span class="alert alert-danger btn-block">Käyttäjätunnus ei löydy</span>');
		}
		if(data[0] == 'ok')
		{
			window.location.href=location.protocol + "//" + location.host + "/index.php/user/login";
			$('input').val('');
			$('#success').html('<br><span class="alert alert-success btn-block">Salasanasi vaihdettu. Kirjaudu ulos ja käytä uusi salasana.</span>');
			$('.submit').remove();
		}

		//$("#salasanaLomake").submit();
	  },
	  error:function(data){
  		console.log(data); 
	  }
  	});

	return false;
  });




});
</script>

