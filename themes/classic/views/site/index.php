<?php
	if(isset($_SESSION['domain'])){ unset($_SESSION['domain']); }

header("Access-Control-Allow-Origin: *");
if(isset($_GET['testi'])){
	echo $_GET['testi'];
}
if(isset($_POST['testi'])){
	echo $_POST['testi'];
}
?>
<br><br>
<script src="https://www.google.com/recaptcha/api.js"></script>
<div class="container">


<div class="row">
 <div class="col-sm-offset-3 col-sm-6">
  <div class="panel panel-default">
    <div class="panel-heading"><?=Yii::t('main', 'Aloita Etunnin käyttäminen')?></div>
    <div class="panel-body">

     <div class="row">

      <?php if(isset($_GET['aloita'])) : ?>


      <div class="col-sm-12">
	<legend><h3 class="text-info"><?php echo Yii::t('main', 'Ota käyttöön'); ?></h3></legend>
      	<form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/aloita" id="aloita-lomake" method="POST" autocomplete="off">
        <label><?php echo Yii::t('main', 'Yrityksen nimi')?></label>
        <input type="text" id="yrityksen_nimi" name="yrityksen_nimi" class="form-control input-lg" required autofocus>
        <label><?php echo Yii::t('main', 'Y-tunnus')?></label>
        <input type="text" id="yritys_tunnus" name="yritys_tunnus" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Puhelinnumero')?></label>
        <input type="text" id="puhelinnumero" name="puhelinnumero" class="form-control input-lg">
        <label><?php echo Yii::t('main', 'Sähköpostiosoite')?></label>
        <input type="text" id="sahkoposti" name="sahkoposti" class="form-control input-lg" required>
        <label><?php echo Yii::t('main', 'Vahvista sähköpostiosoite')?></label>
        <input type="text" id="sahkoposti2" name="sahkoposti2" class="form-control input-lg" required>
	<br>
	<p>
		<a href="#" data-toggle="modal" data-target="#kehdot">Käyttöehdot</a> | 
		<?= CHtml::link('Rekisteriseloste',Yii::app()->request->baseUrl."/lib/pdf/Rekisteriseloste_08122017.pdf", array('target' => '_blank')) ?>
	</p>

	<p><div class="g-recaptcha" data-sitekey="6LcY2joUAAAAAFhvZM36PBNwej6vJiaHBZNsXeL4"></div></p>

        <p><button class="btn btn-lg btn-primary btn-group submit" type="submit"><?php echo Yii::t('main', 'Ota käyttöön'); ?></button></p>
    	</form>
	<div id="success"></div>

      </div>
      <?php endif; ?>

      <?php if(!isset($_GET['aloita'])) : ?>
      <div class="col-sm-12">
	<legend><h3 class="text-info"><?php echo Yii::t('main', 'Kirjaudu'); ?></h3></legend>
		<form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/user/login" method="POST">
		<label><?php echo Yii::t('main', 'Kirjautumistunnus')?></label>
		<input type="text" class="form-control input-lg" name="UserLogin[domain]" required>
		<label><?php echo Yii::t('main', 'Käyttäjätunnus')?></label>
		<input type="text" class="form-control input-lg" name="UserLogin[username]" required>
		<label><?php echo Yii::t('main', 'Salasana')?></label>
		<input type="password" class="form-control input-lg" name="UserLogin[password]" required>
		<br>
		<input type="submit" class="btn btn-lg btn-primary btn-group" value="Kirjaudu">
		</form>
		<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/salasanan_palauttaminen" class="link"><?php echo Yii::t('main', 'Unohditko salasanasi?'); ?></a>

      </div>
      <?php endif; ?>

     </div>

    </div>
  </div>
 </div>
</div>


<!-- Modal -->
<div id="kehdot" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Käyttöehdot</h4>
      </div>
      <div class="modal-body">
        <div class="well" style="background: #FFFFFF">
		<?=$this->renderPartial('_kayttoehdot')?>
	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
        <button type="button" class="btn btn-primary hyvaksyn" data-dismiss="modal">Hyväksyn</button>
      </div>
    </div>

  </div>
</div>



</div>

<?php if(isset($_GET['aloita']) and isset($_GET['kirjautumistunnus']) and isset($_GET['email']) and $_GET['aloita'] == "ok") : ?>
<script>
 window.dataLayer = window.dataLayer || [];
 window.dataLayer.push({
 'event' : 'aloita',
 'tilanne' : 'OK',
 'kirjautumistunnus' : '<?=$_GET["kirjautumistunnus"]?>',
 'email' : '<?=$_GET["email"]?>',
 });
</script>
<?php endif; ?>


<?php if(isset($_GET['aloita']) and isset($_GET['kirjautumistunnus']) and isset($_GET['email']) and $_GET['aloita'] == "error") : ?>

<?php endif; ?>


<script type="text/javascript">
$(document).ready(function(){
/* Y-tunnus */
function validateNumber(event) {
    var key = window.event ? event.keyCode : event.which;
    if (event.keyCode === 8 || event.keyCode === 46 || key === 45) {
        return true;
    } else if ( key < 48 || key > 57 || $('#yritys_tunnus').val().length > 9) {
        return false;
    } else {
        return true;
    }
};
$('[id^=yritys_tunnus]').keypress(validateNumber);
/* Y-tunnus */

  localStorage.clear();

  $(".hyvaksyn").click(function(){
  	localStorage.setItem('kayttoehdot_luettu', true);
  });

  $("#kehdot").click(function(e){
	e.preventDefault();
  });

  $(".submit").click(function(e){


	e.preventDefault();

	if( $('#yrityksen_nimi').val() === ''){
		$('#yrityksen_nimi').css({"border" : "1px red solid"}).focus();
		return false;
	}
	if( $('#yritys_tunnus').val().length !== 9){
		$('#yritys_tunnus').css({"border" : "1px red solid"}).focus();
		return false;
	}
	if( $('#sahkoposti').val() === ''){
		$('#sahkoposti').css({"border" : "1px red solid"}).focus();
		return false;
	}
	if( $('#sahkoposti2').val() === ''){
		$('#sahkoposti2').css({"border" : "1px red solid"}).focus();
		return false;
	}
	if( $('#puhelinnumero').val() === ''){
		$('#puhelinnumero').css({"border" : "1px red solid"}).focus();
		return false;
	}
	if (grecaptcha.getResponse() == "" && location.hostname !== "etunti.local"){
		alert("Varmistaa, ettet ole robotti");
		return false;
	}
	if( $('#sahkoposti').val() !== $('#sahkoposti2').val() ){
		alert('Tarkasta sähköpostiosoite.');
		return false;
	}
	if(!localStorage.getItem('kayttoehdot_luettu')){
		alert('Lue ensin käyttöehdot.');
		return false;
	}


	/* y-tunnus tsekkaus */
	var yritys_tunnus = false;
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/site/index',
           type: "POST",
	   async: false,
	   data: { check_lomake : "ytunnus", yritys_tunnus : $("#yritys_tunnus").val() },
           success: function(data){
		console.log(data);
		if( data != 'on_olemassa' ){
			yritys_tunnus = true;
		} else {
			alert('Tämä y-tunnus on jo olemassa');
		}
           }
        });

	/* sahkoposti tsekkaus */
	var sahkoposti = false;
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/site/index',
           type: "POST",
	   async: false,
	   data: { check_lomake : "sahkoposti", sahkoposti : $("#sahkoposti").val() },
           success: function(data){
		console.log(data);
		if( data != 'on_olemassa' ){
			sahkoposti = true;
		} else {
			alert('Tämä sähköposti on jo olemassa');
		}
           }
        });

	if(yritys_tunnus && sahkoposti){
	   $('#aloita-lomake').submit();
	}
  });


});
</script>

