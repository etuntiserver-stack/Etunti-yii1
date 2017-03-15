<?php

?>


<br><br>

<div class="container">

<div class="row">
 <div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('main', 'Salasanan vaihtaminen'); ?></div>
    <div class="panel-body">

        <label><?php echo Yii::t('main', 'Vanha salasana')?></label>
        <input type="password" id="vanha_salasana" name="vanha_salasana" class="form-control input-lg" required autofocus>
        <label><?php echo Yii::t('main', 'Uusi salasana')?></label>
        <input type="password" id="uusi_salasana" name="uusi_salasana" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Varmista uusi salasana')?></label>
        <input type="password" id="varmista_uusi_salasana" name="varmista_uusi_salasana" class="form-control input-lg" required>
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

	$('#success').html('');

	$.ajax({
	  url: 'change_password',
	  data: { vanha_salasana: $('#vanha_salasana').val(), uusi_salasana: $('#uusi_salasana').val(), varmista_uusi_salasana: $('#varmista_uusi_salasana').val() },
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


		if(data == 'InvalidPassword')
		{
			$('#success').html('<br><span class="alert alert-danger btn-block">Vanha salasana on erilainen</span>');
		}
		if(data == 'varmistaUusi')
		{
			$('#success').html('<br><span class="alert alert-danger btn-block">Uusi salasana on erilainen</span>');
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

