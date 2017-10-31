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

<?php
	//if(isset($vastaus))
		//echo $vastaus;

	if($database and !empty($kirjautumistunnus))
	echo '<div class="alert bg-success">'.Yii::t('main', 'Kirjaudu sisään käymällä "Yritystunnus": <b>'.$kirjautumistunnus.'</b> '.CHtml::link('tästä linkistä',array('user/login', 'target'=>'_blank')) ).'</div>';
?> 
  

<div class="row">
 <div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('main', 'Aloita Etunnin käyttäminen'); ?></div>
    <div class="panel-body">
      	<form action="#" method="POST" autocomplete="off">
        <label><?php echo Yii::t('main', 'Kirjautumistunnus')?></label>
        <input type="text" id="kirjautumistunnus" name="kirjautumistunnus" class="form-control input-lg" required autofocus>
        <label><?php echo Yii::t('main', 'Yrityksen nimi')?></label>
        <input type="text" id="yrityksen_nimi" name="yrityksen_nimi" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Yritystunnus')?></label>
        <input type="text" id="yritys_tunnus" name="yritys_tunnus" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Osoite')?></label>
        <input type="text" id="osoite" name="osoite" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Postinumero')?></label>
        <input type="number" id="postinumero" name="postinumero" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Postitoimipaikka')?></label>
        <input type="text" id="postitoimipaikka" name="postitoimipaikka" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Yhteyshenkilö')?></label>
        <input type="text" id="yhteyshenkilo" name="yhteyshenkilo" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Puhelinnumero')?></label>
        <input type="text" id="puhelinnumero" name="puhelinnumero" class="form-control input-lg">
        <label><?php echo Yii::t('main', 'Sähköpostiosoite')?></label>
        <input type="text" id="sahkoposti" name="sahkoposti" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Käyttäjätunnus')?></label>
        <input type="text" id="username" name="username" class="form-control input-lg" required autocomplete='off'>
        <label><?php echo Yii::t('main', 'Salasana')?></label>
        <input type="password" id="password" name="password" class="form-control input-lg" required autocomplete='off'>
	<br>
	<p><a href="#" data-toggle="modal" data-target="#kehdot">Käyttöehdot</a></p>
        <p><button class="btn btn-lg btn-primary btn-group submit" type="submit"><?php echo Yii::t('main', 'Aloita'); ?></button></p>
    	</form>
	<div id="success"></div>

    </div>
  </div>
 </div>
</div>





<!-- Modal -->
<div id="kehdot" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Käyttöehdot</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="button" class="btn btn-primary hyvaksyn" data-dismiss="modal">Hyväksyn</button>
      </div>
    </div>

  </div>
</div>



</div>



<script type="text/javascript">
$(document).ready(function(){

  localStorage.clear();

  $(".hyvaksyn").click(function(){
  	localStorage.setItem('kayttoehdot_luettu', true);
  });

  $("#kehdot").click(function(e){
	e.preventDefault();
  });

  $(".submit").click(function(e){

	if(!localStorage.getItem('kayttoehdot_luettu')){
		e.preventDefault();
		alert('Lue ensin käyttöehdot.');
		return false;
	}
  });

});
</script>
