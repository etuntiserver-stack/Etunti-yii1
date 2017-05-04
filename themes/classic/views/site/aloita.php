 <?php // $this->renderPartial('/site/header'); ?> 


<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/etusivu_2.css">
<ul class="steps expanded even-4">
    <li class="disabled"><?php echo CHtml::link('Etusivu', Yii::app()->request->baseUrl.'/index.php/site/index'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Ajankohtaista', Yii::app()->request->baseUrl.'/index.php/site/ajankohtaista'); ?></li>
    <li class="active"><?php echo CHtml::link('Asiakkaat', Yii::app()->request->baseUrl.'/index.php/site/asiakkaat'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yritys', Yii::app()->request->baseUrl.'/index.php/site/yritys'); ?></li>
    <li class="disabled"><?php echo CHtml::link('Yhteystiedot', Yii::app()->request->baseUrl.'/index.php/site/yhteystiedot'); ?></li>
</ul>


<br><br>

<div class="container">

<div class="row">
 <div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('main', 'Aloita Etunnin käyttäminen'); ?></div>
    <div class="panel-body">

        <label><?php echo Yii::t('main', 'Yrityksen nimi')?></label>
        <input type="text" id="yrityksen_nimi" name="yrityksen_nimi" class="form-control input-lg" required autofocus>
        <label><?php echo Yii::t('main', 'Yritystunnus')?></label>
        <input type="text" id="yritys_tunnus" name="yritys_tunnus" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Osoite')?></label>
        <input type="text" id="osoite" name="osoite" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Postinumero')?></label>
        <input type="text" id="postinumero" name="postinumero" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Postitoimipaikka')?></label>
        <input type="text" id="postitoimipaikka" name="postitoimipaikka" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Yhteyshenkilö')?></label>
        <input type="text" id="yhteyshenkilo" name="yhteyshenkilo" class="form-control input-lg" required>
        
	<br>
        <button class="btn btn-lg btn-primary btn-group submit" type="submit"><?php echo Yii::t('main', 'Aloita'); ?></button>
    
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
			window.location.href="etusivu";
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

