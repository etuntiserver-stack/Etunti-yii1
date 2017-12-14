<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */
$asetukset = Asetukset::model()->findbypk(1);

//print_r($_SESSION['onlinevaraus']);

/*
				$_SESSION['onlinevaraus']['asiakas_tiedot']['kohde_id']		= $model->id;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['puhelin']		= $model->puh_nro;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['osoite']		= $model->osoite;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['postinumero']	= $model->pnumero;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['kaupunki']		= $model->kaupunki;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['lisatietoja']	= $model->tietoja;

				$_SESSION['onlinevaraus']['asiakas_tiedot']['asiakas_id']	= $modelAsiakas->id;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['tyyppi'] 		= $modelAsiakas->tyyppi;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['yrityksen_nimi'] 	= $modelAsiakas->yrityksen_nimi;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['y_tunnus'] 	= $modelAsiakas->y_tunnus;
				$_SESSION['onlinevaraus']['asiakas_tiedot']['yhteyshenkilo']	= $modelAsiakas->yhteyshenkilo;
*/
?>

<?php
if(isset($_POST['poistaTamaTiedosto'])){
	unlink($_POST['poistaTamaTiedosto']);
	exit;
}
if(isset($_POST['getMyPictures']))
{

	$i = 0;
  	$kuvat = '';

    	if(isset($_SESSION['onlinevaraus']['kuvat']))
    	{
		foreach(array_reverse(glob('tiedostot/onlinevaraus_temp/'.Yii::app()->user->domain.'/'.$_SESSION['onlinevaraus']['kuvat'].'_*.*')) as $file) {
		$i++;
		$explNimi = explode("/",$file);
	 	$kuvat .= '
			<div class="form-inline" id="t_'.$_SESSION['onlinevaraus']['kuvat'].$i.'">
		  		<div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" for="t_'.$_SESSION['onlinevaraus']['kuvat'].$i.'">X</div>
		  		&nbsp;&nbsp;&nbsp;<a href="../../'.$file.'">'.end($explNimi).'</a>
			</div>
		';
	 	}
    	}
	echo json_encode($kuvat);
	exit;
}

if(isset($_POST['kuvanLisaaminen']))
{
	function rand_string( $length ) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		return substr(str_shuffle($chars),0,$length);
	}
	if(!isset($_SESSION['onlinevaraus']['kuvat']))
		$_SESSION['onlinevaraus']['kuvat'] = rand_string(8);

	if (!file_exists(Yii::app()->basePath."/../tiedostot/onlinevaraus_temp/".Yii::app()->user->domain)) {
	  	mkdir(Yii::app()->basePath."/../tiedostot/onlinevaraus_temp/".Yii::app()->user->domain, 0777, true);
	}

	$uploaddir = Yii::app()->basePath.'/../tiedostot/onlinevaraus_temp/'.Yii::app()->user->domain.'/';
	$uploadfile = $uploaddir . basename($_SESSION['onlinevaraus']['kuvat'].'_'.$_FILES['file']['name']);
	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		//echo "";
	}
	exit;
}
?>





<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/onlinevaraus_2.css">

<div class="container-fluid well">
<br>

<div class="row">
 <div class="form-inline col-sm-12">
	<b id="countTimer" class="pull-right"></b>
   <div class="form-group">
	<img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">

	&nbsp;<span data-toggle="modal" data-target=".kysymys" class="link"><img src="<?php echo Yii::app()->request->baseUrl; ?>/ylapalkki/kysymys.png" height="30"></span>

   </div><div class="form-group col-sm-offset-4">
	<h3><?php echo Yii::t('main', 'Online-Varaus'); ?><br>
           <p class="small link text-sininen" data-toggle="modal" data-target=".mikaOnOnlinevaraus"><?php echo Yii::t('main', 'Mikä on online-varaus'); ?></p>
	</h3>
   </div>
 </div>
</div>


                            <!-- Modal -->
                            <div class="modal fade kysymys">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4>Osoite</h4>
                                  </div>
                                  <div class="modal-body" style="text-align: left">
                                  <p>

1.       Kirjoita osoitekenttiin pyydetyt yhteystiedot. Jos olet käyttänyt palvelua aikaisemmin, niin yhteystietosi löytyvät sähköpostisi perusteella.<br>
2.       Jos sinulla on lemmikkejä, huoneita, jonne et halua kenenkään menevän, ovikoodi tai muuta työntekijän saapumiseen tai palvelun suorittamiseen liittyviä asioita, niin kirjoita ne lisätietoja osioon.<br>
3.       Siirry eteenpäin hyväksymään ja maksamaan palvelun.<br>

<br><br> 

<p>Kaikki kentät ovat pakollisia. Antamasi tiedot tallentuvat järjestelmään, jolloin tietoja ei tarvitse kirjoittaa uudestaan, kun palveluja tilataan tulevaisuudessa. Sinulla voi olla useampia osoitteita tallentuneena. Yhteenveto kenttä päivittyy, kun tietoja kirjataan. Mikäli haluat poistaa tietyn osoitteen järjestelmästä, niin ota yhteyttä asiakaspalveluumme. Asiakaspalvelun yhteystiedot ovat näkyvillä sivustolla.</p>

				  </p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Sulje</button>
                                  </div>
                                </div><!-- /.modal-content -->
                              </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->

<ul class="steps expanded even-4">
    <li class="tehtty"><?php echo CHtml::link('PALVELU','index'); ?></li>
    <li class="tehtty"><?php echo CHtml::link('AIKA','aika'); ?></li>
    <li class="active"><?php echo CHtml::link('OSOITE','osoite'); ?></li>
    <?php if(isset(Yii::app()->user->aid)): ?>
    <li class="disabled"><?php echo Yii::t('main','VALMIS'); ?></li>
    <?php else: ?>
    <li class="disabled"><?php echo Yii::t('main','MAKSU'); ?></li>
    <?php endif; ?>
</ul>

<br><br>

<div class="row">
 <div class="col-sm-8">


<div id="fullLomake">
 <div class="well">

   <h4 class="title-subtitle text-center"><?php echo Yii::t('main', 'Osoite'); ?></h4>

   <span class="text-sininen"><?php echo Yii::t('main', 'Tunnistaudu sähköpostilla'); ?></span>

      <!--<span class="small"><?php echo Yii::t('main', 'sähköpostillaTeksti'); ?></span>-->
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


     <span class="text-sininen"><?php echo Yii::t('main', 'Tai täytä yhteystietokentät'); ?></span>
     <!--<span class="small"><?php echo Yii::t('main', 'taitaytateksti'); ?></span>-->
     <br>

     <div id="lomake">

      <div class="row">
       <div class="col-sm-6">
	<label><?php echo Yii::t('main', 'Asiakastyyppi '); ?></label>
	  <select id="tyyppi" class="form-control input-lg">
	  <option value="henkilo">Yksityishenkilö</option>
	  <option value="yritys">Yritys</option>
	  </select>

       </div>
      </div>

      <div class="row">
       <div class="col-sm-6">

	<label><?php echo Yii::t('main', 'Yhteyshenkilö'); ?></label>
	  <input type="text" id="yhteyshenkilo" class="form-control input-lg">

	<label><?php echo Yii::t('main', 'Puhelin'); ?></label>
	  <input type="text" id="puhelin" class="form-control input-lg">

       </div><div class="col-sm-6">

        <div class="yritys">
	<label><?php echo Yii::t('main', 'Yrityksen Nimi'); ?></label>
	  <input type="text" id="yrityksen_nimi" class="form-control input-lg">
	</div>

        <div class="yritys">
	<label><?php echo Yii::t('main', 'Y-tunnus'); ?></label>
	  <input type="text" id="y_tunnus" class="form-control input-lg">
	</div>

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
      <div id="getMyPictures"></div>
      <br>

      <div class="row">
       <div class="col-sm-6">
	<label><?php echo Yii::t('main', 'Kuvien lisääminen'); ?></label>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-filestyle.js"> </script>
  	<form id="uploadKuva" action="#" method="post" enctype="multipart/form-data">
     	  <div class="input-group">
		<input type="hidden" name="kuvanLisaaminen">
		<input type="file" name="file" class="filestyle" data-icon="false" data-size="lg" data-buttonName="btn-primary" data-buttonText="<?php echo Yii::t('main', 'Lisää kuva'); ?>">
		<span class="input-group-btn">
          		<input type="submit" value="Lataa" class="btn btn-primary btn-lg btn-group myBgColors" />
		</span>
    	  </div>
	</form>
       </div>
      </div>



<script type="text/javascript">
$(document).ready(function(){

 $("#uploadKuva").submit(function(e){
    e.preventDefault();
    var formData = new FormData($(this)[0]);

    $.ajax({
        url: window.location.pathname,
        type: 'POST',
        data: formData,
        success: function (data) {
            getKuvat();
        },
        cache: false,
        contentType: false,
        processData: false
    });

    return false;

 });

 getKuvat();

 function getKuvat(){

   $.ajax({
	url: 'osoite',
	type : 'POST',
	data : { getMyPictures : "true" },
	success:function(data){
		var data = JSON.parse(data);
		$('#getMyPictures').html(data);
   	},
	error:function(data){
		console.log(data);
    	}
    });
  }

  $(document).delegate(".poistaTiedosto","click",function(){

	var forThis = $(this).attr("this");
	var forID = $(this).attr("for");

        $.ajax({
           url: window.location.pathname,
	   type:'POST',
	   data: { "poistaTamaTiedosto" : forThis },
           success: function(data){
		console.log(data);
		$("#"+forID).remove();
           }
        });
  });

});
</script>


	<br>
<?php
	echo '
	<div class="row">
	  <div class="col-xs-6">
			'.CHtml::link('Edellinen','aika', array('class'=>'btn btn-lg edellinen')).'
	  </div><div class="col-xs-6">
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

<!--
	      <div id="alennuskoodi">
		<div class="well">
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
-->

	     <?php if(!empty($asetukset->onlinevaraus_asiakaspalvelu)) : ?>
	      <div>
		<div class="well sininen">
		  <center><h4><?php echo Yii::t('main', 'Asiakaspalvelu'); ?></h4></center>
		  <p class="small"><?php echo str_replace("\n", "<br>", $asetukset->onlinevaraus_asiakaspalvelu); ?></p>
		</div>
	      </div>
	     <?php endif; ?>

 </div>
</div>


</div>

<br><br>

<?php echo $this->renderPartial('_footer'); ?>


<?php
if(isset(Yii::app()->user->aid)){
	$a = Asiakkaat::model()->findByPk(Yii::app()->user->aid);
	if(isset($a->id) and !empty($a->sahkoposti))
	echo '<input type="hidden" id="aid_sahkoposti" value="'.$a->sahkoposti.'">';
}
?>

<script type="text/javascript">
$(document).ready(function(){

tyyppi();
$("#tyyppi").change(function() {
    tyyppi();
});

function tyyppi(){

	var v = $("#tyyppi").val();

	if(v == 'henkilo')
	{
		$('.yritys').hide(375);
		$('#yrityksen_nimi').val('');
		$('#y_tunnus').val('');

	}
	if(v == 'yritys')
	{
		$('.yritys').show(375);
	}
}


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



		if(localStorage.getItem('tyyppi') !== null)
			$('#tyyppi').val(localStorage.getItem('tyyppi'));
		if(localStorage.getItem('yrityksen_nimi') !== null)
			$('#yrityksen_nimi').val(localStorage.getItem('yrityksen_nimi'));
		if(localStorage.getItem('y_tunnus') !== null)
			$('#y_tunnus').val(localStorage.getItem('y_tunnus'));
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


			$('#tyyppi').val(d['tyyppi']);
			$('#yrityksen_nimi').val(d['yrityksen_nimi']);
			$('#y_tunnus').val(d['y_tunnus']);

			$('#yhteyshenkilo').val(d['yhteyshenkilo']);
			$('#puhelin').val(d['puhelin']);
			$('#osoite').val(d['osoite']);
			$('#postinumero').val(d['postinumero']);
			$('#kaupunki').val(d['kaupunki']);
			//$('#lisatietoja').val(d['lisatietoja']);

		}
   	},
	error:function(data){
		console.log(data);
    	}
    });
}


function onkokohde(sahkoposti)
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

if($("#aid_sahkoposti").length)
{
	$("#sahkoposti").val( $("#aid_sahkoposti").val() );
   	var sahkoposti = $("#aid_sahkoposti").val();
	onkokohde(sahkoposti);
	$('.tallennaUusi').html('Valmis');
}

$(document).delegate('#sahkoposti', "keyup", function() {

   var sahkoposti = $(this).val();

   if(sahkoposti.length > 5)
   {
	onkokohde(sahkoposti);
   }

});



$(".tallennaUusi").click(function(){


   var tyyppi 		= $('#tyyppi').val();
   var yrityksen_nimi 	= $('#yrityksen_nimi').val();
   var y_tunnus 	= $('#y_tunnus').val();

   var sahkoposti 	= $('#sahkoposti').val();
   var osoite 		= $('#osoite').val();
   var postinumero 	= $('#postinumero').val();
   var kaupunki 	= $('#kaupunki').val();
   var puhelin 		= $('#puhelin').val();
   var yhteyshenkilo 	= $('#yhteyshenkilo').val();
   var lisatietoja 	= $('#lisatietoja').val();

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
	data:{ "sahkoposti" : sahkoposti, osoite : osoite, postinumero : postinumero, kaupunki : kaupunki, puhelin : puhelin, yhteyshenkilo : yhteyshenkilo, lisatietoja : lisatietoja, tyyppi : tyyppi, yrityksen_nimi : yrityksen_nimi, y_tunnus : y_tunnus },
	type:'POST',
	success:function(data){
		console.log(data);
		data = JSON.parse(data);

		localStorage.setItem('tyyppi', $('#tyyppi').val());
		localStorage.setItem('yrityksen_nimi', $('#yrityksen_nimi').val());
		localStorage.setItem('y_tunnus', $('#y_tunnus').val());

		localStorage.setItem('yhteyshenkilo', $('#yhteyshenkilo').val());
		localStorage.setItem('puhelin', $('#puhelin').val());
		localStorage.setItem('osoite', $('#osoite').val());
		localStorage.setItem('postinumero', $('#postinumero').val());
		localStorage.setItem('kaupunki', $('#kaupunki').val());
		localStorage.setItem('lisatietoja', $('#lisatietoja').val());


		if(data == 'nytRedirectMaksulle')
		{
			window.location.href="maksu";
		} else if(data == 'nytRedirectValmis'){
			window.location.href="valmis";
		} else {
			alert(data);
		}

   	},
	error:function(data){
		console.log(data);
    	}
    });

  

});




});
</script>


