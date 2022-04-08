<?php

?>


<br><br>

<div class="container">

<div class="row">
 <div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('main', 'Salasanan palauttaminen'); ?></div>
    <div class="panel-body">


        <label for="inputEmail"><?php echo Yii::t('main', 'Yritystunnus')?></label>
        <input type="text" id="domain" class="form-control input-lg" required autofocus>
        <label for="username"><?php echo Yii::t('main', 'Käyttäjätunnus')?></label>
        <input type="text" id="username" class="form-control input-lg" required>
	<br>
        <button class="btn btn-lg btn-primary btn-group submit" type="submit"><?php echo Yii::t('main', 'Lähetä'); ?></button>
        <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php" class="btn btn-lg btn-primary btn-group"><?php echo Yii::t('main', 'Etusivulle'); ?></a>
	<div id="success"></div>
    </div>
  </div>
 </div>
</div>


</div>
<br><br>


<script type="text/javascript">
$(document).ready(function(){

  $(".submit").click(function(){
	$('#success').html('Odota..');
	$.ajax({
	  url: 'salasanan_palauttaminen?check=true&domain='+$('#domain').val(),
	  data: { username: $('#username').val() },
	  type:'POST',
	  success:function(data){

		try
		{
		   data = JSON.parse(data);
	  	   console.log(data);
		}
		catch(e)
		{
		   $('#success').html('<br><span class="alert alert-danger btn-block">Tarkista tiedot</span>');
		   return false;
		}


		if(data == 'domainEmpty')
		{
			$('#success').html('<br><span class="alert alert-danger btn-block">Yritystunnus on pakkolinen</span>');
		}
		if(data == 'error')
		{
			$('#success').html('<br><span class="alert alert-danger btn-block">Käyttäjätunnus ei löydy</span>');
		}
		if(data == "kp_error") {
			$("#success").html('<br><span class="alert alert-danger btn-block">Et voi pyytää uutta salasanaa kotipuhtaaksi yritystunnukselle ilman että käyttäjällä on @kotipuhtaaksi.fi sähköpostiosoite.</span>');
		}
		if(data[0] == 'ok')
		{
			$('input').val('');
			$('#success').html('<br><span class="alert alert-success btn-block">Uusi salasana lähetetty: '+data[1]+'</span>');
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

