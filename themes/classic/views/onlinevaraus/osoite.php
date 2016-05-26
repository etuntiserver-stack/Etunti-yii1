<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_2.css">

<div class="container-fluid">
<br>

<div class="row">
 <div class="form-inline col-sm-12">
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
   </div><div class="form-group col-sm-offset-4">
	<h2>Online-Varaus</h2>
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

     <?php if(!isset($_SESSION['onlinevaraus']['modelKohde'])) : ?>
<div id="fullLomake">
 <div class="boxes-info">

   <div class="row">
      <div class="col-sm-6">

     <div class="sahkoposti">
	<label><?php echo Yii::t('main', 'Sähköposti'); ?></label>
	<input type="text" id="sahkoposti" class="form-control input-lg" placeholder="Sähköposti">
     </div>

     <div class="buttons btncheckPosti">
     <br>
	<button class="btn btn-primary btn-block btn-lg tarkistaSahkoposti">Jatka</button>
     </div>

      </div>
   </div>


     <div id="lomake" style="display:none">
      <div class="row">
       <div class="col-sm-6">

	<label><?php echo Yii::t('main', 'Etu- ja sukunimi'); ?></label>
	<input type="text" id="yhteyshenkilo" class="form-control input-lg">

       </div><div class="col-sm-6">

	<label><?php echo Yii::t('main', 'Puhelin'); ?></label>
	<input type="text" id="puhelin" class="form-control input-lg">

       </div>
      </div>

	<br>
	<center><h4><?php echo Yii::t('main', 'Osoite'); ?></h4></center>

      <div class="row">
       <div class="col-sm-12">

	<label><?php echo Yii::t('main', 'Osoite'); ?></label>
	<input type="text" id="osoite" class="form-control input-lg">

       </div>
      </div>


      <div class="row">
       <div class="col-sm-6">

	<label><?php echo Yii::t('main', 'Postinumero'); ?></label>
	<input type="text" id="postinumero" class="form-control input-lg">

       </div><div class="col-sm-6">

	<label><?php echo Yii::t('main', 'Postitoimipaikka'); ?></label>
	<input type="text" id="kaupunki" class="form-control input-lg">

       </div>
      </div>

	<br>
	<center><h4><?php echo Yii::t('main', 'Lisätietoja'); ?></h4></center>

      <div class="row">
       <div class="col-sm-12">

	<label><?php echo Yii::t('main', 'Lisätietoja'); ?></label>
	<textarea id="lisatietoja" class="form-control input-lg" placeholder="<?php echo Yii::t('main', 'Lemmikkejä, ovikoodi ja muuta lisätietoa'); ?>" rows="5"></textarea>

       </div>
      </div>


	<br>
	<button class="btn btn-primary btn-block btn-lg tallennaUusi">Tallenna</button>

     </div>




 </div>
</div>
     <?php endif; ?>


     <div id="loytynytOsoitteet"></div>
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


$(document).delegate(".loytyiOsoite","click",function(){

   var id = $(this).attr("id").split("_");

   $.ajax({
	url: 'palvelu_save_ajax',
	data:{ "kohde" : id[1] },
	type:'POST',
	success:function(data){
		//console.log(data);
		if(data)
		{
			$('#loytynytOsoitteet').hide('slow');
			$('#panGetContent').html(JSON.parse(data));

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });


});

$(".tarkistaSahkoposti").click(function(){

   var sahkoposti = $('#sahkoposti').val();
   if(sahkoposti === '')
   {
   $('#sahkoposti').focus();
   return false;
   } else {

   $.ajax({
	url: 'onkokohde',
	data:{ "sahkoposti" : sahkoposti },
	type:'POST',
	success:function(data){
		data = JSON.parse(data);
		if(data === 'ei')
		{
			$('#lomake').show('slow');
			$('.btncheckPosti').hide('slow');
			console.log(data);
			count = null;

		} else {
			$('#fullLomake').hide('slow');
			$('#loytynytOsoitteet').html(data);
			//console.log(data);
			count = null;

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });

  }

});




$(".tallennaUusi").click(function(){

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
	data:{ "sahkoposti" : sahkoposti, "osoite" : osoite, "postinumero" : postinumero, "kaupunki" : kaupunki, "puhelin" : puhelin, "yhteyshenkilo" : yhteyshenkilo, "lisatietoja" : lisatietoja },
	type:'POST',
	success:function(data){
		data = JSON.parse(data).split('_');
		if(data[0] == 'ok')
		{

		   $.ajax({
			url: 'palvelu_save_ajax',
			data:{ "kohde" : data[1] },
			type:'POST',
			success:function(data){
				//console.log(data);
				if(data)
				{
					$('#fullLomake').hide();
					$('#panGetContent').html(JSON.parse(data));	
				}
		   	},
			error:function(data){
				console.log(data);
		    	}
		    });


			console.log(data);
			count = null;

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });

  

});



var count = null;
function counter(){
    count += 1;
    console.log("counter: "+count);
    if(count > 20)
    window.location.href="index?keskeyta=true";
}
setInterval(counter, "15000");

});
</script>


