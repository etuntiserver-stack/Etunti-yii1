<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);
?>

<div class="container-fluid">
<br><br>
<div class="row">
 <div class="form-inline col-sm-12">
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
   </div><div class="form-group col-sm-offset-3">
	<h2>Online-Varaus</h2>
   </div>
 </div>
</div>

<ul class="nav nav-pills nav-justified">
  <li role="presentation"><?php echo CHtml::link('Palvelu','index'); ?></li>
  <li role="presentation"><?php echo CHtml::link('Aika','aika'); ?></li>
  <li role="presentation" class="active"><?php echo CHtml::link('Osoite','osoite'); ?></li>
  <li role="presentation"><?php echo CHtml::link('Maksu','maksu'); ?></li>
</ul>

<br>

<br><br>
<div class="row">
 <div class="col-sm-3">

     <?php if(!isset($_SESSION['onlinevaraus']['modelKohde'])) : ?>
<div id="fullLomake">
     <div class="sahkoposti">
	<input type="text" id="sahkoposti" class="form-control" placeholder="Sähköposti">
     </div>

     <div class="buttons btncheckPosti">
     <br>
	<button class="btn btn-primary btn-block tarkistaSahkoposti">Jatka</button>
     </div>

     <div id="lomake" style="display:none">
     <br>
	<input type="text" id="osoite" class="form-control" placeholder="Osoite">
     <br>
	<input type="text" id="postinumero" class="form-control" placeholder="Postinumero">
     <br>
	<input type="text" id="kaupunki" class="form-control" placeholder="Postitoimipaikka">
     <br>
	<input type="text" id="puhelin" class="form-control" placeholder="Puhelin">
     <br>
	<input type="text" id="yhteyshenkilo" class="form-control" placeholder="Yhteyshenkilö">
     <br>
	<button class="btn btn-primary btn-block tallennaUusi">Tallenna</button>
     </div>
</div>
     <?php endif; ?>

 </div>

 <div class="col-sm-5">
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
 </div>
</div>


</div>




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
			$('#loytynytOsoitteet').html(data);
			$('.sahkoposti, .btncheckPosti').hide('slow');
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
	data:{ "sahkoposti" : sahkoposti, "osoite" : osoite, "postinumero" : postinumero, "kaupunki" : kaupunki, "puhelin" : puhelin, "yhteyshenkilo" : yhteyshenkilo },
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


