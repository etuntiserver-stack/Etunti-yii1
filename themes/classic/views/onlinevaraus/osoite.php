<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);

//print_r($_SESSION['onlinevaraus']);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_2.css">

<div class="container-fluid">
<br>

<div class="row">
 <div class="form-inline col-sm-12">
	<b id="countTimer" class="pull-right"></b>
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
   </div><div class="form-group col-sm-offset-4">
	<h3><?php echo Yii::t('main', 'Online-Varaus'); ?><br>
           <span class="small"><?php echo CHtml::link(Yii::t('main', 'Mikä on online-varaus'),'index'); ?></span>
	</h3>
   </div>
 </div>
</div>


<ul class="steps expanded even-4">
    <li class="tehtty"><?php echo CHtml::link('PALVELU','index'); ?></li>
    <li class="tehtty"><?php echo CHtml::link('AIKA','aika'); ?></li>
    <li class="active"><?php echo CHtml::link('OSOITE','osoite'); ?></li>
    <li class="disabled"><?php echo CHtml::link('MAKSU','maksu'); ?></li>
</ul>

<br><br>

<div class="row">
 <div class="col-sm-8">


<div id="fullLomake">
 <div class="boxes-info">

   <h4 class="title-subtitle text-center"><?php echo Yii::t('main', 'Osoite'); ?></h4>

   <span class="text-sininen"><?php echo Yii::t('main', 'Tunnistaudu sähköpostilla'); ?></span><br>

      <span class="small"><?php echo Yii::t('main', 'sähköpostillaTeksti'); ?></span>
      <br>

   <div class="row">
      <div class="col-sm-6">

     	<div class="sahkoposti">
	<label><?php echo Yii::t('main', 'Sähköposti'); ?></label>
	<input type="text" id="sahkoposti" class="form-control input-lg" placeholder="Sähköposti" value="<?php if(isset($_SESSION['onlinevaraus']['sahkoposti'])) echo $_SESSION['onlinevaraus']['sahkoposti'] ;?>">
     	</div>

      </div><div class="col-sm-6">
       	<div id="loytynytOsoitteet"></div>
      </div>
   </div>

     <br>


     <span class="text-sininen"><?php echo Yii::t('main', 'Tai täytä yhteystietokentät'); ?></span><br>
     <span class="small"><?php echo Yii::t('main', 'taitaytateksti'); ?></span>
     <br>

     <div id="lomake">
      <div class="row">
       <div class="col-sm-6">

	<input type="hidden" id="asiakas_id">

	<label><?php echo Yii::t('main', 'Etu- ja sukunimi'); ?></label>
	<input type="text" id="yhteyshenkilo" class="form-control input-lg" value="<?php if(isset($_SESSION['onlinevaraus']['modelKohde_etu_suku_nimet'])) echo $_SESSION['onlinevaraus']['modelKohde_etu_suku_nimet'] ;?>">

       </div><div class="col-sm-6">

	<label><?php echo Yii::t('main', 'Puhelin'); ?></label>
	<input type="text" id="puhelin" class="form-control input-lg" value="<?php if(isset($_SESSION['onlinevaraus']['modelKohde_puh_nro'])) echo $_SESSION['onlinevaraus']['modelKohde_puh_nro'] ;?>">

       </div>
      </div>

	<br>
	<center><h4><?php echo Yii::t('main', 'Osoite'); ?></h4></center>

      <div class="row">
       <div class="col-sm-12">

	<label><?php echo Yii::t('main', 'Osoite'); ?></label>
	<input type="text" id="osoite" class="form-control input-lg" value="<?php if(isset($_SESSION['onlinevaraus']['modelKohde_osoite'])) echo $_SESSION['onlinevaraus']['modelKohde_osoite'] ;?>">

       </div>
      </div>


      <div class="row">
       <div class="col-sm-6">

	<label><?php echo Yii::t('main', 'Postinumero'); ?></label>
	<input type="text" id="postinumero" class="form-control input-lg" value="<?php if(isset($_SESSION['onlinevaraus']['modelKohde_pnumero'])) echo $_SESSION['onlinevaraus']['modelKohde_pnumero'] ;?>">

       </div><div class="col-sm-6">

	<label><?php echo Yii::t('main', 'Postitoimipaikka'); ?></label>
	<input type="text" id="kaupunki" class="form-control input-lg" value="<?php if(isset($_SESSION['onlinevaraus']['modelKohde_kaupunki'])) echo $_SESSION['onlinevaraus']['modelKohde_kaupunki'] ;?>">

       </div>
      </div>

	<br>
	<center><h4><?php echo Yii::t('main', 'Lisätietoja'); ?></h4></center>

      <div class="row">
       <div class="col-sm-12">

	<label><?php echo Yii::t('main', 'Lisätietoja'); ?></label>
	<textarea id="lisatietoja" class="form-control input-lg" placeholder="<?php echo Yii::t('main', 'Lemmikkejä, ovikoodi ja muuta lisätietoa'); ?>" rows="5"><?php if(isset($_SESSION['onlinevaraus']['modelKohde_tietoja'])) echo $_SESSION['onlinevaraus']['modelKohde_tietoja'] ;?></textarea>

       </div>
      </div>


	<br>
<?php
	echo '
	<div class="row">
	  <div class="col-sm-6">
			'.CHtml::link('Edellinen','aika', array('class'=>'btn btn-lg edellinen')).'
	  </div><div class="col-sm-6">
			<button class="btn btn-lg seuraava tallennaUusi">Maksu</button>
	  </div>
	</div>';
?>




     </div>




 </div>
</div>






 </div>

 <div class="col-sm-4">
   <div id="panGetContent">
   <?php 
   if(isset($_SESSION['onlinevaraus']['paapalvelu']))
   {
	$return = $this->renderPartial('palvelu_save_ajax', array('sivu'=>'osoite'), true); 
   	echo json_decode($return, true);
   }
   ?>
   </div>

	      <div id="alennuskoodi">
		<div class="boxes-info">
		  <center><h4><?php echo Yii::t('main', 'Alennuskoodi'); ?></h4>
			<form class="input-group">
			<input type="text" class="form-control form-group input-lg">
			<span class="input-group-btn">
			  <input type="submit" class="btn btn-lg btn-group btn-warning" value="<?php echo Yii::t('main', 'Aktivoi'); ?>">
			</span>	
			</form>
		  </center>
		</div>
	      </div>

	      <div>
		<div class="boxes-info sininen">
		  <center><h4><?php echo Yii::t('main', 'Asiakaspalvelu'); ?></h4>

		  </center>
		</div>
	      </div>

 </div>
</div>


</div>

<br><br>


<script type="text/javascript">
$(document).ready(function(){



var step = 41;
var count = step;
function counter(){
    count += -1;


	var time = count*15;
	var minutes = "0" + Math.floor(time / 60);
	var seconds = "0" + (time - minutes * 60);
	jaljella =  minutes.substr(-2) + ":" + seconds.substr(-2);
	$('#countTimer').text('Aikajäljellä: '+jaljella);

    if(count < 1)
    window.location.href="index?keskeyta=true";
}
setInterval(counter, "15000");



		if(localStorage.getItem('asiakas_id') !== null)
			$('#asiakas_id').val(localStorage.getItem('asiakas_id'));
		if(localStorage.getItem('yhteyshenkilo') !== null)
			$('#yhteyshenkilo').val(localStorage.getItem('yhteyshenkilo'));
		if(localStorage.getItem('puhelin') !== null)
			$('#puhelin').val(localStorage.getItem('puhelin'));
		if(localStorage.getItem('osoite') !== null)
			$('#osoite').val(localStorage.getItem('osoite'));
		if(localStorage.getItem('postinumero') !== null)
			$('#postinumero').val(localStorage.getItem('postinumero'));
		if(localStorage.getItem('kaupunki') !== null)
			$('#kaupunki').val(localStorage.getItem('kaupunki'));
		if(localStorage.getItem('lisatietoja') !== null)
			$('#lisatietoja').val(localStorage.getItem('lisatietoja'));



if(localStorage.getItem('onkokohde') === 'ei' && localStorage.getItem('sahkoposti') !== '')
{
    $('#loytynytOsoitteet').hide();
    $('#lomake').show('hide');
}




$(document).delegate("#valitseOsoite","change",function(){

   var id = $(this).val();
   localStorage.setItem('valittuOsoiteID', id);
   osoiteAjax(id);

});

function osoiteAjax(id)
{

   $.ajax({
	url: 'get_lomake_ajax?id='+id,
	success:function(data){
		var d = JSON.parse(data);
		console.log(d);
		if(d)
		{

			$('#asiakas_id').val(d['asiakas_id']);
			$('#yhteyshenkilo').val(d['etu_suku_nimet']);
			$('#puhelin').val(d['puh_nro']);
			$('#osoite').val(d['osoite']);
			$('#postinumero').val(d['pnumero']);
			$('#kaupunki').val(d['kaupunki']);
			$('#lisatietoja').val(d['tietoja']);

			//$('#loytynytOsoitteet').hide('slow');
			//$('#panGetContent').html(JSON.parse(data));
		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
}



$("#sahkoposti").keyup(function(){

   var sahkoposti = $(this).val();

   if(sahkoposti.length > 5)
   {
   $.ajax({
	url: 'onkokohde',
	data:{ "sahkoposti" : sahkoposti },
	type:'POST',
	success:function(data){
		data = JSON.parse(data);
		if(data === 'ei')
		{
			localStorage.setItem('onkokohde', 'ei');
			$('#lomake').show('slow');
			$('.btncheckPosti').hide('slow');
			console.log(data);
			count = step;

		} else {
			//$('#fullLomake').hide('slow');
			$('.btncheckPosti').hide('slow');
			$('#loytynytOsoitteet').html(data);
			localStorage.setItem('onkokohde', data);
			//console.log(data);
			count = step;

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
   }

});




$(".tallennaUusi").click(function(){

   var asiakas_id = $('#asiakas_id').val();
   var sahkoposti = $('#sahkoposti').val();
   var osoite = $('#osoite').val();
   var postinumero = $('#postinumero').val();
   var kaupunki = $('#kaupunki').val();
   var puhelin = $('#puhelin').val();
   var yhteyshenkilo = $('#yhteyshenkilo').val();
   var lisatietoja = $('#lisatietoja').val();

   if(sahkoposti === '')
   {
      $('#sahkoposti').focus();
      return false;
   } else if(osoite === ''){
      $('#osoite').focus();
      return false;
   } else if(postinumero === ''){
      $('#postinumero').focus();
      return false;
   } else if(kaupunki === ''){
      $('#kaupunki').focus();
      return false;
   } else if(puhelin === ''){
      $('#puhelin').focus();
      return false;
   } else if(yhteyshenkilo === ''){
      $('#yhteyshenkilo').focus();
      return false;
   }


   $.ajax({
	url: 'luouusi',
	data:{ "sahkoposti" : sahkoposti, "osoite" : osoite, "postinumero" : postinumero, "kaupunki" : kaupunki, "puhelin" : puhelin, "yhteyshenkilo" : yhteyshenkilo, "lisatietoja" : lisatietoja, "asiakas_id" : asiakas_id },
	type:'POST',
	success:function(data){
		console.log(data);
		data = JSON.parse(data);

		if(data == 'nytRedirectMaksulle')
		{

			localStorage.setItem('asiakas_id', $('#asiakas_id').val());
			localStorage.setItem('yhteyshenkilo', $('#yhteyshenkilo').val());
			localStorage.setItem('puhelin', $('#puhelin').val());
			localStorage.setItem('osoite', $('#osoite').val());
			localStorage.setItem('postinumero', $('#postinumero').val());
			localStorage.setItem('kaupunki', $('#kaupunki').val());
			localStorage.setItem('lisatietoja', $('#lisatietoja').val());


			window.location.href="maksu";
		}

   	},
	error:function(data){
		console.log(data);
    	}
    });

  

});




});
</script>


