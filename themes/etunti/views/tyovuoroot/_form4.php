<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */


// <-- Check tunnit jos ilmainen
$site = Yii::app()->createController('Site');
$checkPoista = "tyovuorot_3_".Yii::app()->user->adminStatus;
$poista = $site[0]->checkOikeusFields($checkPoista);
$omasiistijavaroitus = (isset($omasiistijavaroitus) ? $omasiistijavaroitus : false);

if(
	!isset($model->id) 
	and $site[0]->laskuri() !== false 
	and isset(Yii::app()->user->ilmainen_kayttotunnit) 
	and Yii::app()->user->ilmainen_kayttotunnit > 500)
{
	Yii::app()->user->setFlash('danger', Yii::app()->user->ilmainen_ilmoitus);
	echo '<script>window.location.href="index"</script>';
	exit;
}
//     Check tunnit jos ilmainen -->


$today = date("d.m.Y");
if(isset($_GET['tid'])){ $model->tid = $_GET['tid']; }
if(!isset($laatikko_pvm)){ $laatikko_pvm = ''; }
if(!isset($laatikko_tid)){ $laatikko_tid = ''; }
if(!isset($laatiko_etusukunimi)){ $laatiko_etusukunimi = ''; }
$nextTv = $this->checkNextTv($this_id);

if(!empty($laatikko_pvm))
	$model->pvm = $laatikko_pvm;
if(!empty($laatikko_tid))
	$model->tid = $laatikko_tid;

$tyopaari = json_decode($model->tyopaari, true);

/** @var Freshdesk object. */
$freshdesk = Yii::createComponent('Freshdesk');

// Get tickets per customer (ignore resolved (4) and closed (5) tickets).
$customer_tickets = $freshdesk->ticketsByCustomerId([4, 5]);

// Alert on open freshdesk tickets
if (isset($customer_tickets[$model->kohteet->asiakas_id ?? 0])) {
  $link = $freshdesk->getCustomerUrl($model->kohteet->asiakas_id ?? 0);
  echo '<div id="freshdesk-notice" class="section alert bg-warning">';
  echo CHtml::link(Yii::t('main', 'Tällä asiakkaalla on avoimia tukipyyntöjä Freshdeskissä. Avaa painamalla tästä.'), $link, ['class' => 'text-dark', 'target' => '_blank']);
  echo '</div>';
}

$ohje = '';
if(isset($model->id)){

	$m = Kohteet::model()->findbypk($model->kohde);
		
	if(isset($m->id))
	{

		  $ohje = '';
		if(isset($m->avaimet) and count($m->avaimet) > 0){
		  $ohje .= Yii::t('main', 'Avain on: ')."<br>";
		  foreach($m->avaimet as $avain)
			$ohje .= $avain->avainnumero."<br>";

		}
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."\n\n";
		if(!empty($m->aikataulu))
		  $ohje .= "\nAikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet: ".str_replace("\n","<br>",$m->toimenpiteet)."<br>";
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja mobiilisovellukseen: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut: ".$m->muut;

	}

	echo '<input type="hidden" id="updateMuoto" value="true">';
} else {
	echo '<input type="hidden" id="updateMuoto" value="false">';
}

if($toistuva){
	$java_prefix = 'ToistuvatTyovuorot';
} else {
	$java_prefix = 'Tyovuoroot';
	$ov = Onlinevaraus::model()->findbypk($model->onlinevaraus_id);
	if(isset($ov->id) and !empty($ov->kohde_id) and empty($model->kohde)){
		Tyovuoroot::model()->updatebypk($model->id, array('kohde'=>$ov->kohde_id));
		$model->kohde = $ov->kohde_id;
	}
}

if(isset($model->id) and !empty($model->tyoajanlaatu) and $model->status == 0){
	$model->status = 11;
}
?>

	<?php if($model->osoiteOnline == 1) : ?>
	<div class="section alert bg-warning">
	<?php echo Yii::t('main', 'Online varaus prosessissa.'); ?>
	</div>
	<?php elseif(isset($ov->id) and $model->osoiteOnline == 2): ?>
	<div class="section alert bg-warning">
	<?php echo Yii::t('main', 'Tämä kohde on onlinevarauksesta.'); ?>
	</div>
	<?php elseif(isset($ov->id) and $model->osoiteOnline == 3): ?>
	<div class="section alert bg-warning">
	<?php echo Yii::t('main', 'Tämä kohde on eDicosta.'); ?>
	</div>
	<?php endif; ?>
	<?=(!empty($nextTv))?'<div class="alert bg-info">Seuraava vuoro: '.$nextTv.'</div>':''?>


<div class="section">
	<div id="kohteen_lisatiedot" class="pull-right"></div>
	<div id="huomio_yllaosa" class="text-center"></div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
	'enableAjaxValidation'=>false,

)); ?>


	<?php echo $form->errorSummary($model); ?>
	<?php if(!$toistuva){ echo $form->hiddenField($model,'toistuva_id'); } ?>
	<?php echo $form->hiddenField($model,'tid'); ?>
	<?php echo $form->error($model,'tid'); ?>

<h4>Työvuoron perustiedot</h4>
<div id="1_tila">
<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepickerFI lomake_kenta', 'readonly'=>'yes'));?>
		<?php echo $form->error($model,'pvm'); ?>
  </div>
  <div class="col-sm-3">
		<label><?php echo Yii::t('main', 'Asiakas tai kohteen yhteyshenkilö'); ?></label><br>
		<input type="text" id="asiakas" class="form-control lomake_kenta" AUTOCOMPLETE="off">
		<div id="asiakasAutocompleteResult"></div>
		<p>Palvelukieli: <span id="client_finnish_service_wish"></span></p>
		<p>Asiakasnumero: <span id="client_number"></span></p>
  </div>
  <div class="col-sm-3">
		<label for="Tyovuoroot_kohde">Kohde</label>
		<?php
       		$criteria = new CDbCriteria();
	        $criteria->order = " osoite ";
		if($create_update == 'create')
			$criteria->condition = " aktiivinen=1 ";
		if($create_update == 'update')
			$criteria->condition = " aktiivinen=1 or id='".$model->kohde."' ";

		// <-- TyoryhmatHelper
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->addCondition(" tyoryhma IN ($ids) ");
		}
		//     TyoryhmatHelper -->

        		$list = CHtml::listData(Kohteet::model()->findAll($criteria), 'id', 'osoite');
        		echo $form->dropDownList($model, 'kohde', $list,array('empty'=>'Valitse','class'=>'form-control kohde lomake_valinta'));
        	?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
        	$l = $this->tilanteet();
		echo $form->dropDownList($model,'status', $l, 
		array('class'=>'form-control lomake_valinta')) ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>20,'maxlength'=>255,'class'=>'form-control lomake_kenta')); ?>
		<?php echo $form->error($model,'osoite'); ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>20,'maxlength'=>255,'class'=>'form-control lomake_kenta')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>20,'maxlength'=>255,'class'=>'form-control lomake_kenta')); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
  </div>
  <div class="col-sm-3">
	<div id="luoavain"><br>
	<?php 
	if(isset($model->kohteet->id) and isset($model->kohteet->avaimet) and count($model->kohteet->avaimet) > 0){
	echo CHtml::link('Avaimet', array('/avaimet/index', 'osoite' => $model->kohteet->osoite), array('class'=>'btn btn-primary btn-block myBgColors', 'target' =>'_blank')); 
	}
	?>
	<?php if(isset($model->kohteet->id) and isset($model->kohteet->avaimet) and count($model->kohteet->avaimet) == 0 and $model->kohteet->asiakas_id > 0): ?>
	    <div class="input-group">
	      <span class="form-control"><?php echo Yii::t('main','Luo avain'); ?></span>
	      <span class="input-group-btn">
		<?=CHtml::link('<i class="fa fa-plus"></i>', array('/avaimet/create', 'asiakas_id' => $model->kohteet->asiakas_id, 'kohde_id' => $model->kohteet->id), array('class'=>'btn btn-primary', 'target' =>'_blank'))?>
	      </span>
	    </div> 
	<?php endif; ?>
	</div> 

  	<div id="tyoajanlaatu_laatikko" style="<?=(($model->status != 11)?'display:none':'')?>">
		<?php echo $form->labelEx($model,'tyoajanlaatu'); ?>
		<div class="input-group">
		<?php 
			$l1 = array(
				'(VL) Vuosiloma/green' => '(VL) Vuosiloma', 
				'(VKL) Viikkolomapäivä/blue' => '(VKL) Viikkolomapäivä',
				'(SL) Sairaus Palkallinen/#FFAC33' => '(SL) Sairaus Palkallinen',
				'(SPL) Sairaus Palkaton/#FFAC33' => '(SPL) Sairaus Palkaton',
				'(LS) Lapsen sairaus/#FFAC33' => '(LS) Lapsen sairaus',
				'(AP) Arkipyhä/#FFAC33' => '(AP) Arkipyhä',
				'(PV) Palkaton vapaa/#FFAC33' => '(PV) Palkaton vapaa',
			);
			$valikkoot = Valikkoot::model()->findAll("select_type = 'vuosilomat'");
			$l2 = array();
			foreach($valikkoot as $vl){
    				$expl = explode("/",$vl->value);
				if(isset($expl[0]) and isset($expl[1]) and isset($expl[2])){
					$l2['('.$expl[0].') '.$expl[1].'/'.$expl[2]] = '('.$expl[0].') '.$expl[1];
				}
			}
			$list = array_merge($l1, $l2);

			echo '<select name="'.$java_prefix.'[tyoajanlaatu]" class="form-control lomake_valinta" id="'.$java_prefix.'_tyoajanlaatu">';
			foreach($list as $key => $val){
				$bg 		= '#fff';
				$selected 	= ''; 
				if(in_array($val, $l1)){ $bg = '#ccc'; }
				if($model->tyoajanlaatu == $key){ $selected = 'selected'; }
				echo '<option value="'.$key.'" style="background: '.$bg.'" '.$selected.'>'.$val.'</option>';
			}
			echo '</select>';
		?>
		<span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="vuosilomat"><i class="fa fa-pencil-square-o"></i></span>
		</span>
		</div>
  	</div>
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'alku'); ?> <span style="color:red">*</span>
		<input type="text" name="<?=$java_prefix?>[alku]" class="form-control laske timeVuorot lomake_kenta" id="alku" value="<?php echo $model->alku; ?>" autofocus>

		<button type="button" style="height: 24px; padding-top: 0px; padding-bottom: 0px;" class="form-control btn btn-sm btn-primary" id="aloitusajat-ilmoita">
			<span class="fa fa-share-square" title="Ilmoita"></span>
		</button>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?> <span style="color:red">*</span>
		<input type="text" name="<?=$java_prefix?>[loppu]" class="form-control laske timeVuorot lomake_kenta" id="loppu" value="<?php echo $model->loppu; ?>">
  </div>

  <div class="col-sm-3">
	<div class="form-inline">
	 <div class="form-group mr20">
		<?php echo $form->labelEx($model,'pituus'); ?>
		<div id="tvPituus" class="p10"><?php echo $model->pituus; ?></div>
	 </div>
	 <div class="form-group">
		<label><?php echo Yii::t('main', 'Arvioitu kesto'); ?></label>
		<div id="arvioitu_kesto" class="p10">00:00</div>
	 </div>
	</div>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>

		<div class="input-group">
		<?php
			$l1 = array(
				'Normaali/' => 'Normaali', 
				'Ei lasketa/red' => 'Ei lasketa', 
				'Varallaolo/#c67520' => 'Varallaolo',
				'Ehdollinen varallaolo/blue' => 'Ehdollinen varallaolo'
			);
			$valikkoot = Valikkoot::model()->findAll("select_type = 'tyoajanmerkinta'");
			$l2 = array();
			foreach($valikkoot as $vl){
    				$expl = explode("/",$vl->value);
				if(isset($expl[0]) and isset($expl[1])){
					$l2[$expl[0].'/'.$expl[1]] = $expl[0];
				}
			}
			$list = array_merge($l1, $l2);
			ksort($list);
			$maaritetty = [];
			if(!empty($model->tyoajanmerkinta)){
				$expl = explode("/",$model->tyoajanmerkinta);
				$value = (isset($expl[0])) ? $expl[0] : '';
				$maaritetty[$model->tyoajanmerkinta] = '<option value="'.$model->tyoajanmerkinta.'" selected>'.$value.'</option>';
			}
			echo '<select name="'.$java_prefix.'[tyoajanmerkinta]" class="form-control lomake_valinta" id="'.$java_prefix.'_tyoajanmerkinta">';
				$list = array_merge($l1, $l2);
				foreach($list as $key => $val){
					$expl 	= explode("/",$key);
					$color = (isset($expl[1])) ? $expl[1] : '';

					if(isset($maaritetty[$key]))
						echo $maaritetty[$key];
					else
						echo '<option style="color:'.$color.'" value="'.$key.'">'.$val.'</option>';
				}
			echo '</select>';
        	?>

		<span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="tyoajanmerkinta"><i class="fa fa-pencil-square-o"></i></span>
		</span>
		</div>
  </div>
</div>

<?php if ($create_update == 'update' && isset($model->kohteet->asiakas_id)): ?>
<div class="row">
  <!-- TEMP -->
  <!-- <div class="col-md-6" id="omasiistija-toiminnot">
    <div class="col-sm-12">
      <?php /* echo $form->labelEx($model, 'omasiistijailmoitus', ['style' => 'font-weight: bold']); */ ?>
    </div>
    <div class="col-sm-12 input-group">
        <?php /* echo $form->dropDownList($model, 'omasiistijailmoitus', [
          0 => Yii::t('main', 'Ei ilmoitettu'),
          1 => Yii::t('main', 'Ilmoitettu'),
        ], ['class'=>'form-control lomake_valinta']); */ ?>
      <span class="input-group-btn">
        <button type="button" class="form-control btn btn-sm btn-primary" id="omasiistijat-ilmoita"><span class="fa fa-share-square"></span> Ilmoita
      </span>
    </div>
  </div> -->
</div>
<script>
$(function() {

  // Flag for when omasiistijä email is sent and form is submitted, for the
  // submit function to redirect back to the form. This is so that the shift may
  // be removed from a cyclic shift (toistuvasta irroittaminen).
  // This has to be before document.ready for it to work, in this case.
  var submitRedirectBack = false;

  // Updating on list selection change is disabled for now. Button only shown if 
  // the correct value "Ilmoita aloitusaika/green" is selected when opening.
  //\  => "Ilmoita aloitusaika/green" || "Aloitusaika ilmoitettu/"
  if ($('#<?=$java_prefix?>_tyoajanmerkinta').val() == "Ilmoita aloitusaika/green") {
    $('#aloitusajat-ilmoita').removeAttr('disabled').show();
  } else {
    $('#aloitusajat-ilmoita').attr('disabled', 'disabled').hide();
  }

  // Hide the notify button on ANY relevant changes.
  $('#<?=$java_prefix?>_pvm, #asiakas, #<?=$java_prefix?>_osoite, #<?=$java_prefix?>_kohde, #<?=$java_prefix?>_alku, #<?=$java_prefix?>_loppu').on('change', function(e) {
    $('#aloitusajat-ilmoita').attr('disabled', 'disabled').hide();
  });

  // Aloitusaikailmoitus -
  $('#aloitusajat-ilmoita').on('click', function(e) {

    // "Ilmoita aloitusaika/green" || "Aloitusaika ilmoitettu/"
    if ($('#<?=$java_prefix?>_tyoajanmerkinta').val() != "Ilmoita aloitusaika/green") {
      if (!confirm('Työvuoron aloitusaikaa ei ole merkitty ilmoitettavaksi. Lähetetäänkö ilmoitus silti?')) {
        return false;
      }
    }

    // asiakas_id, kohde_id, pvm, aloitusaika, lopetusaika
    const asiakasId = <?= $model->kohteet->asiakas_id ?? 0; ?>;
    const kohdeId = <?= $model->kohteet->id ?? 0; ?>;
    const pvm = $('#<?= $java_prefix ?>_pvm').val();
    const alku = $('#alku').val();
    const loppu = $('#loppu').val();

    if (!confirm(`
      Asiakas: <?= $model->kohteet->asiakkaat->Etusukunimi ?? ''; ?>\n
      Kohde: <?= $model->kohteet->osoite ?? ''; ?> (ID ${kohdeId})\n
      Aika: ${pvm} klo ${alku} - ${loppu}\n
      Huom. Jos asiakasta vaihdetaan, vuoro tulee tallentaa ennen ilmoituksen lähettämistä.\n
      Jos tiedot on väärin tai puuttuu, paina EI ja päivitä sivu.\n\n
      Ilmoitetaanko ajat sähköposteihin <?= $model->kohteet->asiakkaat->AloitusajatEmailsString ?? ''; ?>?\n
      (Lähettäessä ilmoitusta, odota kun sivu päivittyy ja työvuoro avataan uudestaan)
    `)) {
      return false;
    }

    // Perform notification.
    $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/aloitusaikojen_ilmoitus`, {

      type: 'POST',
      data: {
        'asiakas_id': asiakasId,
        'kohde_id': kohdeId,
        'pvm': pvm,
        'alku': alku,
        'loppu': loppu
      },

      // Error handling just in case.
      error: function (xhr, status, error) {
        alert(`Aloitusaikailmoituksen lähetyksessä tapahtui sisäinen virhe: ${xhr.responseText}`);
        console.log(xhr.responseText);
      },

      // Success, parse received JSON.
      success: function (data) {
        console.log(data);

        // Try parse response JSON.
        let parsed = null;
        try {
          parsed = JSON.parse(data);
        } catch (e) {
          console.log(`Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
          alert("Aloitusaikailmoituksen lähetyksessä tapahtui virhe: palvelin palautti viallisen tuloksen.");
          return false;
        }

        // Check if parsing failed. Notify log and let it go.
        if (typeof (parsed) != "object") {
          console.log("Parsed data is unusable (not an object).");
          alert("Aloitusaikailmoituksen lähetyksessä tapahtui virhe: palvelin palautti viallisen tuloksen.");
          return false;
        }

        // Check if data is empty, which means possible server error.
        if (parsed.length == 0) {
          console.log("Empty response received.");
          alert("Aloitusaikailmoituksen lähetyksessä tapahtui virhe: tyhjä vastaus vastaanotettu palvelimelta.");
          return false;
        }

        // Check if empty message, meaning logical fault.
        if (!('message' in parsed) || parsed.message.length == 0) {
          alert(`Aloitusaikailmoituksen lähetyksessä tapahtui virhe: palvelin ei palauttanut vastausta.`);
          return false;
        }

        // Check if operation failed.
        if (!('success' in parsed) || parsed.success != true) {
          alert(`Aloitusaikailmoituksen lähetyksessä tapahtui virhe: ${parsed.message}`);
          return false;
        }

        // Everything is normal; notification has been sent. Notify the user
        // with the returned result message, update the selection box and
        // disable the button for sending the notification.
        console.log(parsed.message);
        // alert(parsed.message);
        $('#<?=$java_prefix?>_tyoajanmerkinta').val("Aloitusaika ilmoitettu/");
        $('#aloitusajat-ilmoita').attr('disabled', 'disabled');

        // The model needs to be saved, whether it is cyclic or not. If cyclic,
        // the shift must be removed from it (toistuvasta irroittaminen).
        // Set flag for submit so it knows to redirect BACK to this form.
        submitRedirectBack = true;
        $('#tyovuoroot-form').submit();
      }
    })
  });

});
</script>
<?php endif; ?>


<script type="text/javascript">
$(document).ready(function(){

 $('#<?=$java_prefix?>_tyoajanlaatu').change(function(){
	if($('option:selected', this).val() !== ''){
		$('#<?=$java_prefix?>_kohde').val('');
		$('#<?=$java_prefix?>_osoite').val('');
		$('#<?=$java_prefix?>_postinumero').val('');
		$('#<?=$java_prefix?>_postitoimipaikka').val('');
		$('#alku').val('00:00');
		$('#loppu').val('00:00');
	}
	if( $('option:selected', this).text() == '(VL) Vuosiloma' ){
		$("#alku").val('08:00').attr('readonly', true);
		$("#loppu").val('15:30').attr('readonly', true);
	}
 });

 $(document).delegate("#<?=$java_prefix?>_status","change",function(){
	if($(this).val() == '10'){
		$('#<?=$java_prefix?>_tyoajanmerkinta').val('Ei lasketa/red');
	} else {
		$('#<?=$java_prefix?>_tyoajanmerkinta').val('Normaali/');
	}
	vuosilomat($(this).val());
 });

 vuosilomat($('#<?=$java_prefix?>_status').val());
 function vuosilomat(val){
	if(val == 11){
		$("#1_tila input, #1_tila select").attr('readonly', true);
		//$("#alku, #loppu").val('00:00').removeAttr('readonly');
		$('#<?=$java_prefix?>_tyoajanmerkinta').val('Normaali/');
		$('#<?=$java_prefix?>_status').val('11').removeAttr('readonly');
		$('#<?=$java_prefix?>_osoite').val('');
		$('#<?=$java_prefix?>_kohde').val('');
		$('#<?=$java_prefix?>_postinumero').val('');
		$('#<?=$java_prefix?>_postitoimipaikka').val('');
		$('#luoavain').hide('slow');
		$("#tyoajanlaatu_laatikko").show('slow');
		$("#<?=$java_prefix?>_tyoajanlaatu").removeAttr('readonly').css({"border" : "2px green solid"}).focus();
	} else {
		$("#tyovuoroot-form input, #tyovuoroot-form select").removeAttr('readonly');
		$(".readonly").attr('readonly', true);
		$('#luoavain').show('slow');
		$("#<?=$java_prefix?>_tyoajanlaatu").val('');
		$("#tyoajanlaatu_laatikko").hide('slow');
	}
 }
});
</script>
<br>
<div class="row">
  <div class="col-sm-6">
		<i class="pull-left fa fa-star text-danger"></i>
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php 
		echo $form->textarea($model,'tietoja',array('rows'=>5,'class'=>'form-control lomake_kenta', 'placeholder'=>'Esim. Avainten tiedot tai kohteesa olevat rajoitukset.')); 
		?>
		<?php echo $form->error($model,'tietoja'); ?>
  </div>
  <div class="col-sm-6">
		<p><div id="kohde_url"></div></p>
		<?php echo $form->labelEx($model,'ohje'); ?>
		<div style="height:100px; overflow: scroll; overflow-x:hidden;">
		<div class="ohje"><?php echo $ohje; ?></div>
		</div>
  </div>
</div>
</div><!-- 1 tila -->
<br>
<div class="row">
  <div class="col-sm-3 select2-bootstrap" id="tyopari-container">
		<label><?php echo Yii::t('main', 'Työpari'); ?></label><br>
		<?php 
		// <-- Order tyontekijat
		if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0){
			$tt_order_1 = "tekijan_nimi";
			$tt_order_2 = "sukunimi";
		} else {
			$tt_order_1 = "sukunimi";
			$tt_order_2 = "tekijan_nimi";
		}
		// Order tyontekijat -->

		$criteria=new CDbCriteria;
		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

		$criteria->condition =" aktiivinen=1 and id!='".$laatikko_tid."' ";

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
	       		$criteria->addCondition (" id IN ($ids) and id!='".$laatikko_tid."' ");
		}
		//     Tyoryhmat -->


 		$tt = Tyontekijat::model()->findAll($criteria);

		$elem = '<select style="width: 100%" name="'.$java_prefix.'[tyopaari][]" id="tyopaari" class="bootstrap-select2 select2" multiple>';
		foreach($tt as $tekija) {
			if(is_array($tyopaari) and in_array($tekija->id, $tyopaari, true)) {
				$elem .= '<option selected value="'.$tekija->id.'">'.$tekija->$tt_order_1. ' ' . $tekija->$tt_order_2.'</option>';
			} else {
				$elem .= '<option value="'.$tekija->id.'">'.$tekija->$tt_order_1. ' ' . $tekija->$tt_order_2.'</option>';
			}
		}
		$elem .= "</select>";

		echo $elem;
		?>


  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'peruutettu'); ?>
		<?php
		$list = $this->peruutettuArray();
		echo $form->dropDownList($model,'peruutettu', $list, 
		array('empty'=>'Valitse','class'=>'form-control lomake_valinta'));
		?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'piilota_mobiilista'); ?>
		<?php 
        	$l = array(0=>'Kyllä',1=>'Ei');
			echo $form->dropDownList($model,'piilota_mobiilista', $l, 
			array('class'=>'form-control lomake_valinta')) ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'laskutettu'); ?>
		<?php
			// <-- Check laskutetut
			if(isset($model->kohteet->asiakkaat->id) and $model->kohteet->asiakkaat->id > 0)
			{
				$etunti_tunniste 	= 'la_'.date("m.Y", strtotime($laatikko_pvm)).'_'.$model->kohteet->asiakkaat->id;
				$query				= "etunti_tunniste='".$etunti_tunniste."'";
				$laskutetut_ids 	= Lasku::LaskutetutIDs('tv_id', $query);

				if(isset($laskutetut_ids[$this_id]))
					$model->laskutettu = 1;
			}
			
        	$l = array(0 => 'Ei laskutettu', 1 => 'Laskutettu');
			echo $form->dropDownList($model,'laskutettu', $l, 
			array('class'=>'form-control lomake_valinta')) 
		?>
  </div>
</div>
<br>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tuoteID'); ?> <span style="color:red">*</span> <b class="fa fa-info-circle text-danger" data-toggle="tooltip" title="Huomio! Tuote/Palvelu valikko tulee automaattisesti valitsemalla kohden ja myös silloin, kun kohteen tietoihin on määritelty tuotteet ja palvelut yksikkönä h."></b>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->order = " nimike ";
       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 AND yksikko='h' AND nayta_vain_onlinevarauksessa=0";
		$tp = TuotteetPalvelut::model()->findAll($criteria);
		$oletus = TuotteetPalvelut::model()->find("oletustuote=2");
		if( !isset($model->id) and isset($oletus->id) ){
			$model->tuoteID = $oletus->id;
		}
		$list = [];
		// if for some reason $tp is empty (the domain probably doesn't use
		// products at all), we'll add an empty option, so they can save
		// the shift.
		if(empty($tp)) {
			$list[0] = "Valitse";
		} else {
			$list = CHtml::listData($tp, 'id', 'nimike');
		}
		echo $form->dropDownList(
			$model,
			'tuoteID', 
			$list,
			["class" => "form-control"]
		);
		?>
  </div>
  <div class="col-sm-3">
		<label><?=Yii::t('main','Valitse tuotteet ja lisäpalvelut')?></label>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->order = " nimike ";
       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 AND nayta_vain_onlinevarauksessa=0";
		$tp = TuotteetPalvelut::model()->findAll($criteria);
		echo '<select name="lisapalvelu_tuote" id="lisapalvelu_tuote" class="form-control lomake_valinta">';
		echo '<option value=>Valitse</option>';
		foreach($tp as $item){
			echo '<option value="'.$item->id.'" yksikko="'.$item->yksikko.'">'.$item->nimike.'</option>';
		}
		echo '</select>';
		?>
  </div>
  <div class="col-sm-3">
		<label><?=Yii::t('main','Lisäpalvelun määrä')?></label>
    		<div class="input-group">
		      <?php echo CHtml::numberField('lisapalvelu_maara','lisapalvelu_maara',array('class'=>'form-control lomake_kenta', 'placeholder' => 'määrä')); ?>
		      <span class="input-group-btn">
		        <button class="btn btn-primary plus_lisapalvelu lomake_btn" type="button"><i class="fa fa-plus"></i></button>
		      </span>
		</div>
  </div>
  <div class="col-sm-3">
		<div id="viesti_mobiili_div">
		<?php 
		$t = Tyontekijat::model()->findbypk($laatikko_tid);
		if(!empty($t->gcm_reg_id)) :
		?>
  		<div class="section">
		<label><?php echo Yii::t('main','Ilmoita työntekijää viestillä'); ?></label><br>
			<input type="checkbox" name="<?=$java_prefix?>[PushNotify]" class="sw" id="<?=$java_prefix?>_PushNotify">
	    	</div>
		<?php endif; ?>
		</div>
  </div>
</div>
<br>
<?php
	$criteria = new CDbCriteria();
        $criteria->order = " id DESC ";
	$criteria->condition = " tv_id!=0 AND tv_id='".$model->id."' AND tid='".$laatikko_tid."' ";
	$mobile = Mobile::model()->find($criteria);
?>
<div class="row">
  <div class="col-sm-3">
    <div class="input-group">
      <span><?php echo Yii::t('main','Toistuva työvuoro'); ?></span>
      <span class="input-group-btn">
        <input type="checkbox" name="<?=$java_prefix?>[is_toistuva]" class="sw" id="is_toistuva" <?=(( strtotime($laatikko_pvm) < strtotime(date("Y-m-d")) )? 'disabled': '')?>>
      </span>
    </div>  
  </div>
  <div class="col-sm-3">
    <div class="input-group">
      <span class="form-control lomake_kenta"><?php echo Yii::t('main','URL linkit'); ?></span>
      <span class="input-group-btn">
        <button class="btn btn-primary uusilinkki lomake_btn" type="button"><i class="fa fa-plus"></i></button>
      </span>
    </div>  
  </div>
  <div class="col-sm-3">
    <div class="input-group">
      <span class="form-control lomake_kenta"><?php echo Yii::t('main','Työerittely'); ?></span>
      <span class="input-group-btn">
        <button class="btn btn-primary uusierittely lomake_btn <?=(isset($mobile->id) and is_array(json_decode($mobile->tyo_erittelyt, true)))?'disabled':''?>" type="button"><i class="fa fa-plus"></i></button>
      </span>
    </div>  
  </div>
  <div class="col-sm-3">
    <div class="input-group">
      <span class="form-control lomake_kenta"><?php echo Yii::t('main','Muistiinpano'); ?></span>
      <span class="input-group-btn">
        <button class="btn btn-primary uusimuistinpanno lomake_btn" type="button"><i class="fa fa-plus"></i></button>
      </span>
    </div>  
	<?php if(!empty(Yii::app()->user->kotipuhtaaksi) && $toistuva) : ?>
		<span class="text-danger">Muistathan lisätä muistiinpanon jos irroitat työvuoron ketjusta</span>
	<?php endif; ?>
  </div>
</div>
<br>
<div class="row">
  <?php if (!empty(Yii::app()->user->kp)): ?>
  <div class="col-sm-3">
    <?php
    // echo $form->labelEx($model,'omasiistijavaroitus');
    echo $form->dropDownList($model,'omasiistijavaroitus', [
      0 => Yii::t('main', 'Omasiistijävaroitus piilossa'),
      1 => Yii::t('main', 'Varoita omasiistijöistä'),
    ], ['class'=>'form-control lomake_valinta']);
    ?>
  </div>
  <?php endif; ?>
</div>


<!-- #region Omasiistijät -->
<?php if (isset($model->id) && !empty(Yii::app()->user->kp)): ?>

<br>
<div class="row">
  <div class="col-md-6">
    <!-- Varoitus jos ei ole omasiistijää. -->
    <div class="row">
      <div id="omasiistija-varoitus" class="col-sm-12 text-danger mb5" style="display:none;border:2px solid red;border-radius:4px;text-align:center;height:25px;padding-top:3px">
        <b>Varoitus: Omasiistijää ei ole valittuna!</b>
      </div>
    </div>

    <!-- Avattava omasiistijälista -->
    <div class="row">

      <!--
        Omasiistijät listataan ulkoisesta näkymästä joka hakee työntekijät AJAXilla
        ja vaatii kohteen ID sitä varten. Koska työvuoronäkymässä kohde on vaihtuva
        (lista, josta voidaan valita kohde), tämä kohde ID täytyy antaa
        omasiistijänäkymälle dynaamisesti.

        Ensiksi, annetaan esitäytetty arvo placeholder kenttään (placeholder_id).
        Kun kohde muutetaan, vaihdetaan myös omasiistijälistan kohde ID
      -->

      <?php
      $omasiistijat_div_id = 'omasiistijat_lista'; // itse omasiistijälistan id
      $omasiistijat_placeholder_id = 'omasiistijat_kohde'; // piilotetun placeholderkentän id
      ?>

      <!-- Alue omasiistijälistalle oikeassa alanurkassa työvuoronäkymässä. -->
      <div class="col-sm-12">
        <?= $this->renderPartial('//kohteet/omasiistijat', [
          'kohde_id' => 0,
          'div_id' => $omasiistijat_div_id,
          'placeholder_id' => $omasiistijat_placeholder_id
        ]); ?>
      </div>
    </div>
  </div>

  <?php if ($create_update == 'update'): ?>
  <div class="col-md-6" id="omasiistija-toiminnot">
    <div class="col-sm-12">
      <?php echo $form->labelEx($model, 'omasiistijailmoitus', ['style' => 'font-weight: bold']); ?>
    </div>
    <div class="col-sm-12 input-group">
        <?= $form->dropDownList($model, 'omasiistijailmoitus', [
          0 => Yii::t('main', 'Ei ilmoitettu'),
          1 => Yii::t('main', 'Ilmoitettu'),
        ], ['class'=>'form-control lomake_valinta']); ?>
      <span class="input-group-btn">
        <button type="button" class="form-control btn btn-sm btn-primary" id="omasiistijat-ilmoita"><span class="fa fa-share-square"></span> Ilmoita
      </span>
    </div>
    <!-- <span class="text-secondary">Huom. Kun ilmoitus lähetetään, tämä työvuoro irroitetaan mahdollisesta ketjusta ja avataan uudelleen.</span> -->
  </div>
  <?php endif; ?>

</div>

<?php endif; ?>
<!-- #endregion Omasiistijät -->



<br>
<div class="row">
	<div id="lisapalvelu_lista">
	<?php $lisa_tuotteet = json_decode($model->lisa_tuotteet, true); ?>
	<?php if( isset($lisa_tuotteet['tuote']) and is_array($lisa_tuotteet['tuote'])  ) : ?>
	<div class="col-sm-6 lisapalvelu_laatiko">
	<legend><?php echo Yii::t('main','Lisäpalvelut'); ?></legend>
	<?php foreach($lisa_tuotteet['tuote'] as $k => $v) : ?>
	<?php 
		$t_nimike = '';
		$t_yksikko = '';
		$tp = TuotteetPalvelut::model()->findByPK($v);
		if( isset($tp->id) ){ 
			$t_nimike = $tp->nimike;
			$t_yksikko = $tp->yksikko;
		}
	?>
	<div class="row">
	 <div class="col-sm-11">
		<?=$t_nimike?>: <b><?=json_decode($model->lisa_tuotteet, true)['maara'][$k]?> <?=$t_yksikko?></b>
		<input type="hidden" name="<?=$java_prefix?>[lisa_tuotteet][tuote][]" value="<?=$v?>">
	 </div>
	 <div class="col-sm-1">
		<div class="pull-right">
			<span class="link fa fa-trash text-danger poista_lisa"></span>
		</div>
		<input type="hidden" name="<?=$java_prefix?>[lisa_tuotteet][maara][]" value="<?=json_decode($model->lisa_tuotteet, true)['maara'][$k]?>">
	 </div>
	</div>
	<?php endforeach; ?>
	</div>
	<?php endif; ?>
	</div>

	<!-- Url linkit -->
	<div id="linkkilista">
	<?php
		$urls = $this->getTVUrls($model->url_linkkit);
		if( count($urls) > 0 ){
			echo '
			<div class="col-sm-6 linkkilista_laatiko">
			<legend>'.Yii::t('main','URL linkit').'</legend>';
	 		foreach($urls as $k => $v){
			echo '
			 <div class="row">
			  <div class="col-sm-5">
			   <input type="text" name="'.$java_prefix.'[url_linkkit][nimike][]" class="form-control" value="'.$k.'" placeholder="URL nimike">
			  </div>
			  <div class="col-sm-5">
			   <input type="text" name="'.$java_prefix.'[url_linkkit][url][]" class="form-control" value="'.$v.'" placeholder="http osoite">
			  </div>
			  <div class="col-sm-1 text-right">
			   <span class="btn btn-danger fa fa-trash poislistasta"></span>
			  </div>
	 		 </div>';
			}
			echo '</div>';
		}
	?>
	</div>
	<!-- Url linkit / -->

	<div id="erittelynlista">
	 <?php if(is_array(json_decode($model->tyo_erittelyt, true))): ?>
	 <div class="col-sm-6 erittelynlista_laatiko">
	 <legend><?php echo Yii::t('main','Työerittelyt'); ?></legend>
	 <?php foreach(json_decode($model->tyo_erittelyt, true) as $k => $v): ?>
	 <div class="row">
	  <div class="col-sm-11">
	   <?php if( isset($mobile->id) ) : ?>
	    <input type="text" name="<?=$java_prefix?>[tyo_erittelyt][]" class="form-control input-sm" value="<?=$v?>" readonly>
	   <?php else: ?>
	    <input type="text" name="<?=$java_prefix?>[tyo_erittelyt][]" class="form-control input-sm" value="<?=$v?>">
	   <?php endif; ?>
	  </div>
	  <div class="col-sm-1 text-right">
	   <?php if( isset($mobile->id) and is_array(json_decode($mobile->tyo_erittelyt, true)) and in_array($k, json_decode($mobile->tyo_erittelyt, true)) ){
		echo '<span class="text-success fa fa-check"></span>';
	   } ?>
	   <?php if( !isset($mobile->id) ){
		echo '<span class="link text-danger fa fa-trash poislistasta"></span>';
	   } ?>
	  </div>
	 </div>
	 <?php endforeach; ?>
	 </div>
	 <?php endif; ?>
	</div>
</div><!-- row -->

	<div id="muistiinpanolista">
	 <?php if(is_array(json_decode($model->muistiinpano, true))): ?>
	 <p><div class="row panel-footer"><div class="col-sm-12 muistiinpanolista_laatiko">
	 <legend><?php echo Yii::t('main','Muistiinpanot'); ?></legend>
	 <?php foreach(json_decode($model->muistiinpano, true) as $k => $v): ?>
	 <div class="row">
	  <div class="col-sm-11">
	   <?php if( isset($mobile->id) ) : ?>
	    <textarea name="<?=$java_prefix?>[muistiinpano][]" class="form-control" readonly><?=$v?></textarea>
	   <?php else: ?>
	    <textarea name="<?=$java_prefix?>[muistiinpano][]" class="form-control"><?=$v?></textarea>
	   <?php endif; ?>
	  </div>
	  <div class="col-sm-1 text-right">
		<span class="link text-danger fa fa-trash poislistasta"></span>
	  </div>
	 </div>
	 <?php endforeach; ?>
	 </div></div></p><!--row-->
	 <?php endif; ?>
	</div>



<?php
    $pfrom = '';
    $pto = '';
    $viikkoja = '';
    $viikko_paivat = array();
    $toistuvaID =  '<span id="toistuvaID"></span>';

    if($toistuva){
	$tvt = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id); // Toistuva modelissa on GETtoistuva_id
	if(isset($tvt->id)){
    		$viikkoja = $tvt->viikkoja;
    		$viikko_paivat = json_decode($tvt->viikko_paivat, true);
    		$pfrom = $tvt->pfrom;
    		$pto = $tvt->pto;
    		$toistuvaID =  '<span id="toistuvaID">'.$model->toistuva_id.'</span>';
	}

    }
?>
<br>
<div class="row text-center">
	<div id="kohteen_tiedostot"></div>
</div>


<div id="toistuvaAll" class="collapse">
 <br><h4><?php echo Yii::t('main','Toistuvan työvuoroketjun asetukset'); ?></h4></p>
 <div class="row">
  <div class="col-sm-12">
   <div class="panel-footer">
	<div class="row" id="alkaen_loppuen">
	  <div class="col-sm-4">
		<label><?php echo Yii::t('main', 'Alkaen'); ?></label>
		<input type="text" class="form-control datepickerFI" name="ToistuvatTyovuorot[pfrom]" id="pfrom" value="<?php echo date('d.m.Y', strtotime($laatikko_pvm)); ?>">
	  </div>
	  <div class="col-sm-4">
		<label><?php echo Yii::t('main', 'Loppuen'); ?></label>
		<div class="input-group">
		      <input type="text" class="form-control datepickerFI" name="ToistuvatTyovuorot[pto]" id="pto" value="<?php if(!empty($pto)) echo date('d.m.Y', strtotime($pto)); ?>">
		      <span class="input-group-btn pto_save link" title="Tallenna ja sulje ikkuna.">
		        <button class="btn btn-default pto_save_button" type="button" disabled><i class="fa fa-save"></i></button>
		      </span>
		</div>
		<div id="pto_ilmoitus" style="position:relative;"></div>
	  </div>
	  <div class="col-sm-4">
		<label><?php echo Yii::t('main', 'Työvuorojen viikkoväli'); ?></label>
		<select class="form-control" name="ToistuvatTyovuorot[viikkoja]" id="Toistuva_viikkoja">
		<?php
		if(!empty($viikkoja)) echo '<option value="'.$viikkoja.'">'.$viikkoja.'</option>';
		?>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		</select>
	  </div>
	</div>
		<?php if($toistuva and !empty($pfrom)):?>
		<p class="p10 bg-info">Tämä toistuva työvuoro on alkanut ennen nykyistä ajankohtaa. Uudet muutokset tehdään tästä päivästä eteenpäin ja siitä syntyy uusi toistuva työvuoro sekä vanha toistuva työvuoro jää samanlaiseksi tähän päivään saakka.</p>
		<?php endif; ?>
	<br>
	<div class="row" id="vikoPvm">
	  <div class="col-sm-12 text-center">
	  <label><?php echo Yii::t('main', 'Ma'); ?></label>
	
	  <?php if(in_array(1, $viikko_paivat)): ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][1]" id="ma" value="1" checked>
	  <?php else: ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][1]" id="ma" value="1">
	  <?php endif; ?>
	  <label><?php echo Yii::t('main', 'Ti'); ?></label>
	  <?php if(in_array(2, $viikko_paivat)): ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][2]" id="ti" value="2" checked>
	  <?php else: ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][2]" id="ti" value="2">
	  <?php endif; ?>
	  <label><?php echo Yii::t('main', 'Ke'); ?></label>
	  <?php if(in_array(3, $viikko_paivat)): ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][3]" id="ke" value="3" checked>
	  <?php else: ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][3]" id="ke" value="3">
	  <?php endif; ?>
	  <label><?php echo Yii::t('main', 'To'); ?></label>
	  <?php if(in_array(4, $viikko_paivat)): ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][4]" id="to" value="4" checked>
	  <?php else: ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][4]" id="to" value="4">
	  <?php endif; ?>
	  <label><?php echo Yii::t('main', 'Pe'); ?></label>
	  <?php if(in_array(5, $viikko_paivat)): ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][5]" id="pe" value="5" checked>
	  <?php else: ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][5]" id="pe" value="5">
	  <?php endif; ?>

	  <label><?php echo Yii::t('main', 'La'); ?></label>

	  <?php if(in_array(6, $viikko_paivat)): ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][6]" id="la" value="6" checked>
	  <?php else: ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][6]" id="la" value="6">
	  <?php endif; ?>

	  <label><?php echo Yii::t('main', 'Su'); ?></label>
	
	  <?php if(in_array(7, $viikko_paivat)): ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][7]" id="su" value="7" checked>
	  <?php else: ?>
	  <input type="checkbox" class="sw vkopvmswitch" name="<?=$java_prefix?>[viikko_paivat][7]" id="su" value="7">
	  <?php endif; ?>

	  </div>
	</div>
	<?php
		$m_start = new DateTime();
		$m_start->modify("first day of this month");
		$m_interval = new DateInterval('P1M');
		$m_end = new DateTime($m_start->format("Y-m-d"));
		$m_end->modify("+3 month");
		$m_period = new DatePeriod($m_start, $m_interval, $m_end);
	?>
	<input type="hidden" id="cal_start" value="<?=$m_start->format("Y/n/j")?>">
	<div id="sopivatPaivat"></div>

   </div>
  </div>
 </div>
</div><!-- toistuvaAll -->



		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->

<br>
	<?php if($toistuva): ?>
		<p class="text-center text-danger ilmoitus_pvm_muuttosta">
		Tämä työvuoro kuulu toistuvan ketjun, jolloin poistot ja peruutukset saa tehdä kalenterista, painamalla <i class="fa fa-gear"></i> ikonia valitun päivän alla.<br>
		Huomio! Lomaketta ei tarvitse tallentaa päiviä poistaessa tai palauttaessa.
		</p>
	<?php endif; ?>
	<div class="panel-footer text-right">
		<?php 
		if(isset($model->id))
			echo '<span class="btn btn-danger tvpoisto mr5" tilanne="poista_pvm">POISTA: '.$laatikko_pvm.' ('.$laatiko_etusukunimi.')</span>';
		if(isset($model->id) and $toistuva and $poista == 1 and date("Ymd", strtotime($model->pfrom)) >= date("Ymd"))
			echo '<span class="btn btn-danger tvpoisto mr5" style="display:none" tilanne="poista_ketju_kokonaan">POISTA KAIKKI: '.$model->pfrom.'-'.$model->pto.'</span>';
		if(isset($model->id) and $toistuva and $poista == 1 and date("Ymd", strtotime($laatikko_pvm)) >= date("Ymd"))
			echo '<span class="btn btn-danger tvpoisto mr5" style="display:none" tilanne="poista_alkaen">POISTA ALKAEN: '.$laatikko_pvm.' ('.$laatiko_etusukunimi.')</span>';
		 ?>
		<?php /* echo CHtml::Button('Reload',array('class'=>'btn btn-default reload')); */ ?>
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php 
	   	$checkLuo = "tyovuorot_1_".Yii::app()->user->adminStatus;
	   	$luo = $site[0]->checkOikeusFields($checkLuo);

	   	$checkTallenna = "tyovuorot_2_".Yii::app()->user->adminStatus;
	   	$tallenna = $site[0]->checkOikeusFields($checkTallenna);

		if(!isset($model->id) and $luo == 1)
		echo CHtml::submitButton('Luo',array('class'=>'btn btn-primary','id'=>'submitButton'));
		elseif(isset($model->id) and $tallenna == 1)
		echo CHtml::submitButton('Tallenna',array('class'=>'btn btn-primary','id'=>'submitButton', 'style' => 'display:none')); 
		?>

		<div id="virheilmoitus" class="alert bg-danger" style="display:none"></div>
	</div>		



<?php $this->endWidget(); ?>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>


<script type="text/javascript">
$(document).ready(function(){
  $(document).delegate(".datepickerFI","blur",function(){
	$('#submitButton').show();
  });
  $(document).delegate("input, textarea","keyup",function(){
	if( $(this).attr('id') != 'tp_mukaan' ){
		$('#submitButton').show();
	}
  });
  $(document).delegate("select","change",function(){
	if( $(this).attr('id') != 'cal_peruutettu' && $(this).attr('id') != 'tp_mukaan' ){
		$('#submitButton').show();
	}
  });
  $('.sw').on('switchChange.bootstrapSwitch', function(event, state) {
	if( $(this).attr('id') != 'is_toistuva' && $(this).attr('id') != 'tp_mukaan' ){
		$('#submitButton').show();
	}
  });

  setTimeout(function() {
	$('#hovertietoja').html('').hide();
  }, 1000);

  $(".sw").bootstrapSwitch({
	size: "small",
	onColor: "primary",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

  $('.mult').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
	includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
	maxHeight: 300,
  });
  // init select2
  $(".select2").select2();
  //console.log(Date.now(), "after mult, before getAsiakasByKohde");
  
  if($('#<?=$java_prefix?>_kohde').val() !== ''){
	const kohdeElem = document.getElementById("<?=$java_prefix?>_kohde");
	const kohdeOn = kohdeElem.options[kohdeElem.selectedIndex].value;
	  	 $.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/getAsiakasByKohde',
			type:'GET',
			data: { "id" : kohdeOn },
			  success:function(data){
				//console.log(Date.now(), "getAsiakasByKohde success");
			     if(data){
				data = JSON.parse(data);
			  	//console.log(data);
				$('#asiakas').val(data);
			     } else {
			  	console.log('ei ole asiakas id');
			     }

			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});
  }

  var toistuva 	= ($('#is_toistuva').bootstrapSwitch('state') === true)? true : false;
  $('#is_toistuva').on('switchChange.bootstrapSwitch', function(event, state) {
	if(state === true){
		toistuva = true;
		$('#toistuvaAll').addClass('in');
		tarkistusLista('<?=$this_id?>');
		$("#toistuva_aktiivinen").addClass('in');
		if( $('#pto').val() === '' )
			$('#pto').addClass('bg-danger');
		$('.ilmoitus_pvm_muuttosta, #toistuvaAll').show();
		$('.tvpoisto').show();
	} else {
		toistuva = false;
		$('#toistuvaAll').removeClass('in');
		$('#huomio_yllaosa').html('').hide();
		$('.ilmoitus_pvm_muuttosta').hide();
		$('.tvpoisto, #tekijanVaihdo_huomio').hide();
		$(".mult").multiselect("enable");
		$('.tvpoisto').hide();
	}

  });
  var pfrom 	= $("#pfrom").val();
  var pto 	= $("#pto").val();
  var tekijanVaihdo 	= $('#tekijanVaihdo option:selected').val();

  $('#tekijanVaihdo, #tyopaari').change(function(){
	tyopaari_exists_checker();
  });
  function tyopaari_exists_checker(){
	if($('#tyopaari').val() !== null){
		$($('#tyopaari').val()).each(function( index, val ) {
			if( $('#tekijanVaihdo option:selected').val() == val ){
				alert('Tämä henkilö on jo työparina.');
				$('#tekijanVaihdo').val(tekijanVaihdo).css({'border' : '1px red solid'});
				return false;
			}
		});
	}
	if('<?=$model->id?>' !== '')
		$('#<?=$java_prefix?>_tid').val( $('#tekijanVaihdo option:selected').val() );
  }
  $('#pfrom').on('blur change', function(){
	if(toistuva && !pfrom_and_laatikkopvm_check()){
		alert('Toistuvan työvuoron aloituspäivämäärää ei voida muokata alkamaan ajemmin kun: ' + pfrom);
		$("#pfrom").val(pfrom);
		return false;
	}
	if(toistuva && !pfrom_and_pto_check()){
		alert('Toistuvan työvuoron aloituspäivä ei voi olla myöhemmin kun lopetuspäivä.');
		$("#pfrom").val(pfrom);
		return false;
	}
	$('.lomake_kenta').prop('readonly', false);
	$('#ToistuvatTyovuorot_peruutettu, .lomake_valinta, .lomake_btn').prop('disabled', false);
	$('#huomio_yllaosa').html('');
  });
  $('#pto').on('blur change', function(){
	if(toistuva && !pfrom_and_pto_check()){
		alert('Toistuvan työvuoron aloituspäivä ei voi olla myöhemmin kun lopetuspäivä.');
		$("#pfrom").val(pfrom);
		return false;
	}
	if( toistuva && pto != $(this).val() ){
		if( '<?=$toistuva?>' ){
			$('.pto_save_button').removeClass('btn-default').addClass('btn-primary').removeAttr('disabled');
			$('#pto_ilmoitus').html('<p class="small text-danger" style="position:absolute; z-index:9999; padding: 10px; background:white; border:1px #ddd solid">Tallenna muutettu lopetuspäivä vieressä olevalla painikkeella.<br><br>Huomio! Muita muutoksia ei tallenneta.</p>');
		}
	} else {
		$('.pto_save_button').removeClass('btn-primary').addClass('btn-default').attr('disabled', 'yes');
	}
	$('#pto').removeClass('bg-danger');
  });
  $('.pto_save_button').click(function(){
	var r = confirm('Haluatko muuttaa ketjun lopetuspäivän ja sulkea ikkunan? Huom. muita muutoksia ei tallenneta.');
	if(('<?=$model->id?>') !== '' && r){
	$.ajax({
	  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/pto_muutos?id=<?=$model->id?>',
	  data:{ pto : $("#pto").val() },
	  type:'POST',
	  success:function(data){
		data = JSON.parse(data);
		//console.log(data);
		laatikonPaivays();
   	  },
	  error:function(data){
		console.log(data);
    	  }
	});
	}
  });
  $(document).delegate("#cal_poista_paiva_ketjusta, .palauta_kejuun","click",function(){
	tarkistusLista('<?=$this_id?>');
  });
  $('.reload').click(function(){
	tarkistusLista('<?=$this_id?>');
  });
  $('#pto, #pfrom, #Toistuva_viikkoja, #tyopaari, #tekijanVaihdo, #alku, #loppu, #ToistuvatTyovuorot_kohde').on('blur change select', function(){
	tarkistusLista('<?=$this_id?>');
  });
  $('#ma,#ti,#ke,#to,#pe,#la,#su').on('switchChange.bootstrapSwitch', function(event, state) {
	tarkistusLista('<?=$this_id?>');
  });
  function tarkistusLista(this_id){
  
	if( !toistuva )
		return false;
	if(!pfrom_and_today_check()){
		if(!$("#pfrom").hasClass('bg-danger'))
			$("#pfrom").addClass('bg-danger');
		$(".vkopvmswitch").bootstrapSwitch('disabled', true);
		$("#Toistuva_viikkoja").attr('disabled', 'yes');
		$('#tekijanVaihdo').attr('disabled', 'yes');
		$('#tekijanVaihdo_huomio').remove();
		$(".mult").multiselect("disable");
	} else {
		$("#pfrom").removeClass('bg-danger');
		$(".vkopvmswitch").bootstrapSwitch('disabled', false);
		$("#Toistuva_viikkoja").removeAttr('disabled');
		$('#tekijanVaihdo').removeAttr('disabled');
		$(".mult").multiselect("enable");
	}
	var post_tids = sendpost_tyopaarit_all();

	/* vkopaivat */
	var vkopaivat = [];
	$("input.vkopvmswitch:checkbox:checked").each(function( ) {
		vkopaivat.push($(this).val());
	});

	$.ajax({
	  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/pvmTarkistus_lista?this_id=' + this_id + '&cal_start=' + $("#cal_start").val() + '&tid=' + $('#<?=$java_prefix?>_tid').val() + '&laatikko_pvm=<?=$laatikko_pvm?>',
	  data:{ pfrom : $("#pfrom").val(), pto : $("#pto").val(), viikkoja : $("#Toistuva_viikkoja option:selected").val(), vkopaivat : vkopaivat, post_tids : post_tids, osoite : $('#<?=$java_prefix?>_osoite').val(), alku : $('#alku').val(), loppu : $('#loppu').val(), tyopaari_laatikko : $('#tyopaari').val() },
	  type:'POST',
	  success:function(data){
		data = JSON.parse(data);
		if( data['error'] ){
			$('#sopivatPaivat').html(data['error']).show();
			return false;
		}
		//console.log(data);
		$('#sopivatPaivat').html('');
		$('#sopivatPaivat').append('<br><div class="row"><div class="col-sm-12">' + data + '</div></div>').show('slow');
		$('.vasemalle').click(function(){
			cal_start = $("#cal_start").val();
			var d = new Date(cal_start);
			d.setMonth(d.getMonth() - 3);
			$("#cal_start").val(d.getFullYear() + '/' + (d.getMonth()+1) + '/' + d.getDate());
			tarkistusLista('<?=$this_id?>');
		});
		$('.oikealle').click(function(){
			cal_start = $("#cal_start").val();
			var d = new Date(cal_start);
			d.setMonth(d.getMonth() + 3);
			$("#cal_start").val(d.getFullYear() + '/' + (d.getMonth()+1) + '/' + d.getDate());
			tarkistusLista('<?=$this_id?>');
		});

   	  },
	  error:function(data){
		console.log(data);
    	  }
	});
  }
  function pfrom_and_laatikkopvm_check(){
	var valinnut_pfrom = $('#pfrom').val().split('.');
	var new_pfrom = new Date(+valinnut_pfrom[1]+"/"+valinnut_pfrom[0]+"/"+valinnut_pfrom[2]);
	var laatikko_pvm = $('#<?=$java_prefix?>_pvm').val().split('.');
	var new_pvm = new Date(+laatikko_pvm[1]+"/"+laatikko_pvm[0]+"/"+laatikko_pvm[2]);
	if(new_pfrom.setHours(0,0,0,0) < new_pvm.setHours(0,0,0,0)) {
		return false;
	}
	return true;
  }
  function pfrom_and_today_check(){
	var valinnut_pfrom = $('#pfrom').val().split('.');
	var new_pfrom = new Date(+valinnut_pfrom[1]+"/"+valinnut_pfrom[0]+"/"+valinnut_pfrom[2]);
	var todaysDate = new Date();
	if(new_pfrom.setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0)) {
		return false;
	}
	return true;
  }
  function pfrom_and_pto_check(){
	var valinnut_pfrom = $('#pfrom').val().split('.');
	var new_pfrom = new Date(+valinnut_pfrom[1]+"/"+valinnut_pfrom[0]+"/"+valinnut_pfrom[2]);
	var valinnut_pto = $('#pto').val().split('.');
	var new_pto = new Date(+valinnut_pto[1]+"/"+valinnut_pto[0]+"/"+valinnut_pto[2]);
	if(new_pfrom.setHours(0,0,0,0) > new_pto.setHours(0,0,0,0)) {
		return false;
	}
	return true;
  }
  $('#submitButton').click(function(){
	$('#tyovuoroot-form').submit();
	return false;
  });

  /* on submit */
  var newCreatedTvId = -1;
  $('#tyovuoroot-form').on('submit',function(e) {
    $('#submitButton').attr('disabled', 'disabled');
	var pvmTarkistus = $('#submitButton').attr('pvmTarkistus');
	toistuva = ($('#is_toistuva').bootstrapSwitch('state') === true)? true : false;

  <?php if (!empty(Yii::app()->user->kp)): ?>
  // If omasiistijavaroitus is selected as sent, and toistuva selected, prevent submit
  if (toistuva) {
    if ($('#<?= $java_prefix ?>_omasiistijailmoitus').val() == 1) {
      alert("Omasiistijäilmoitusta ei voida asettaa lähetetyksi koko ketjulle.");
      $('#<?= $java_prefix ?>_omasiistijailmoitus').css('border', '2px solid red').focus();
      $('#submitButton').removeAttr('disabled');
      return false;
    }
  }
  <?php endif; ?>

	if( $('#<?=$java_prefix?>_tuoteID').val() == null )
	{
		$('#<?=$java_prefix?>_tuoteID').addClass('bg-danger').focus();
    	$('#submitButton').removeAttr('disabled');
		return false;
	}

	/* <-- Tarkistetaan Aloitus/Lopetus Klo ja status */
	if( $('#<?=$java_prefix?>_status option:selected').val() === '' )
	{
		$('#<?=$java_prefix?>_status').addClass('bg-danger').focus();
    	$('#submitButton').removeAttr('disabled');
		return false;
	}
	if( $('#alku').val() === '' ){
		$('#alku').addClass('bg-danger').focus();
    	$('#submitButton').removeAttr('disabled');
		return false;
	}
	if( $('#loppu').val() === '' ){
		$('#loppu').addClass('bg-danger').focus();
    	$('#submitButton').removeAttr('disabled');
		return false;
	}
	/*     Tarkistetaan Aloitus/Lopetus Klo ja status --> */

	/* <-- Aloitus ja lopetus ajaat */
	var timeStart = new Date("<?=date("m/d/Y")?> " + $('#alku').val()).getTime();
	var timeEnd = new Date("<?=date("m/d/Y")?> " + $('#loppu').val()).getTime();

  	if( $('#alku').val() !== '' && $('#loppu').val() !== '' && timeEnd < timeStart )
  	{
  		alert('Lopetusaika ei voi olla aiemmin kuin aloitusaika.');
  		$('#submitButton').removeAttr('disabled');
		return false;
  	}
	/* <-- Aloitus ja lopetus ajaat */

	/* <-- Tarkistetaan toistuvat asiat */
 	if( toistuva == true ){
	  	if($('#pfrom').val() !== ''){
			pfrom = $('#pfrom').val().split(".");
			pfrom = parseInt(pfrom[2]+''+pfrom[1]+''+pfrom[0]);
		}
  		if($('#pto').val() !== ''){
			pto = $('#pto').val().split(".");
			pto = parseInt(pto[2]+''+pto[1]+''+pto[0]);
		}
		if(pto !=='' & pto < pfrom){
			alert('Toistuvan työvuoron lopetuspäivämäärä ei voi olla ennen toistuvan työvuoron aloituspäivämäärää.');
      		$('#submitButton').removeAttr('disabled');
			return false;
		}
		if( $('#pfrom').val() === '' ){
			$('#pfrom').addClass('bg-danger').focus();
      $('#submitButton').removeAttr('disabled');
			return false;
		}
		if( $('#pto').val() === '' ){
			$('#pto').addClass('bg-danger').focus();
      $('#submitButton').removeAttr('disabled');
			return false;
		}

		if(!pfrom_and_today_check()){
			alert('Toistuvan työvuoron aloituspäivämäärää ei voida aloita alkamaan menneisyydestä.');
      $('#submitButton').removeAttr('disabled');
			return false;
		}
		var vkopvmswitch_check = false;
		$( ".vkopvmswitch" ).each(function() {
			if($( this ).prop( "checked" ) == true){
				vkopvmswitch_check = true;
				return false;
			}
		});
		if(!vkopvmswitch_check){ 
			alert('Valitse viikko päivä');
      $('#submitButton').removeAttr('disabled');
			return false;
		}
	}

	// <-- tarkistetaan tietoja pituus
	var leng = $('#<?=$java_prefix?>_tietoja').val().length;

	var raja = 10000;
	if(leng > raja){
		alert('Tietoja mobiilisovellukseen kentän merkkimäärä ei voi ylittää '+raja+' rajaa');
    $('#submitButton').removeAttr('disabled');
		return false;
	}
	// tarkistetaan tietoja -->

	var str = '';
	$('#virheilmoitus').html('').hide();
	// <-- Viimeinen kysymys

	if( '<?=$create_update?>' == 'update'){
		$.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update4?this_id=<?=$this_id?>&laatikko_pvm=<?=$laatikko_pvm?>&laatikko_tid=<?=$laatikko_tid?>',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
        console.log(data);

        // Try parse JSON to get new ID for the new tv.
        try {
          let parsed = JSON.parse(data);
          // actionUpdate4 is kinda messy, and sometimes echoes valid JSON array, and sometimes a JSON object INSIDE an array. Check here.
          if ('id' in parsed) {
            newCreatedTvId = parsed.id
          } else if ($.isArray(parsed) && 'id' in parsed[0]) {
            newCreatedTvId = parsed[0].id;
          }
        } catch {
          console.log("Unable to parse received data.");
        }

        laatikonPaivays();
        return false;
        //window.location.reload();
	   	},
		  error: function(xhr, status, error) {
			$('#virheilmoitus').html('Virheilmoitus: \n\n' + xhr.responseText).show();
      $('#submitButton').removeAttr('disabled');
          }
		});
	}
	if( '<?=$create_update?>' == 'create'){
		$.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/create4?toistuva=' + toistuva + '&laatikko_pvm=<?=$laatikko_pvm?>&laatikko_tid=<?=$laatikko_tid?>',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			//console.log(data);
			laatikonPaivays();
			return false;
	   	  },
		  error: function(xhr, status, error) {
			$('#virheilmoitus').html('Virheilmoitus: \n\n' + xhr.responseText).show();
      $('#submitButton').removeAttr('disabled');
	    	  }
		});
	}
	e.preventDefault();
  }); /* on submit */

  function sendpost_tyopaarit_all(){
	var returnthis = [];
	if( '<?=$model->id?>' !== '' )
		returnthis.push(parseInt($('#tekijanVaihdo option:selected').val()));
	else
		returnthis.push(parseInt('<?=$laatikko_tid?>'));

	/* Työpari */
	var tyopaari = $('#tyopaari').val();
	if(tyopaari !== null){
		//console.log('Uudet työparit: ' + tyopaari);
		$(tyopaari).each(function( index, val ) {
			returnthis.push(parseInt(val));
		});
	}
	returnthis = returnthis.filter((a, b) => returnthis.indexOf(a) === b); // remove duplicates
	return returnthis;
  }

  function getAllTids(){
	var tids = [];
	tids.push($('#tekijanVaihdo option:selected').val());
	if( ('<?=$model->id?>') !== '' && ('<?=$model->tid?>') !== $('#tekijanVaihdo option:selected').val() ){
		tids.push('<?=$model->tid?>');
	}
	if( ('<?=$model->id?>') === '' ){
		tids.push($('#Tyovuoroot_tid').val());
	}
	/* Työpari */
	var tyopaari = $('#tyopaari').val();
	if(tyopaari !== null){
		//console.log('Uudet työparit: ' + tyopaari);
		$(tyopaari).each(function( index, val ) {
			tids.push(val);
		});
	}
	if( '<?=$model->tyopaari?>' !== '' ){
		var edelliset_tyoparit = JSON.parse('<?=(is_array(json_decode($model->tyopaari, true)))?json_encode(array_values(json_decode($model->tyopaari, true))):""?>');
		//console.log('Edelliset työparit: ' + edelliset_tyoparit);
		var c = tids.concat(edelliset_tyoparit);
		var tids = c.filter(function (item, pos) {return c.indexOf(item) == pos});
	}
	/* Työpari */
	//console.log('Tids joille päivitetään laatikko: ' + tids);
	//return tids;
	const newTids = tids.reduce((obj, tid) => {
		obj[tid] = tid;
		return obj;
	}, {});
	//console.log("New tids", newTids);
	return newTids;
  }

  function dids_before(){
	var dids = [];
	$.each(getAllTids(), function( tid, value ) {
		$("div[id$='_"+ value +"']").each(function() {
			if( $(this).hasClass('latikkoAsetukset') ){
				dids.push( $(this).attr('id') );
			}
		});
	});
	return dids;
  }
  
  var dids_before_arr = dids_before();

  function laatikonPaivays(){
	const params = new URLSearchParams(window.location.search);
	// get mode from search params, default to empty string
	// this is a fix for 'tt' mode calendar. see actionDid4 for more details.
	const mode = params.get("mode") ?? "";
	//console.log("Mode:", mode);
	$.ajax({
		url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did4?from=<?=$haku_from?>&to=<?=$haku_to?>&mode=' + mode,
		type: 'POST',
		data: { tids : JSON.stringify(getAllTids()) },
		success:function(data){
			data = JSON.parse(data);
			//console.log(data);
			// <-- Tyhjenna kaikki kuluvia laatikot
			$.each(dids_before_arr, function( i, v ) {
				$("#" + v).html('');
				//console.log(v);
			});
			$.tv_arr_update(data);
			$.vkolaskenta(JSON.stringify(getAllTids()));
        $('#showres').modal('hide');

      // Redirect back to modal when doing real-time changes like sending omasiistijävaroitus.
      if (typeof(submitRedirectBack) !== 'undefined' && submitRedirectBack && newCreatedTvId > 0) {
        let dYear = <?= date('Y', strtotime($laatikko_pvm)); ?> // build return URL year
        let dWeek = <?= date('W', strtotime($laatikko_pvm)); ?> // build return URL week
        let tempNewId = newCreatedTvId;                         // set new created id to temporary variable for redirect
        newCreatedTvId = -1;                                    // reset new created ID aswell to avoid bugs
        submitRedirectBack = false;                             // disable further redirect
        window.location.href = `${location.protocol}//${location.host}/index.php/tyovuoroot/beta?mode=vko&year=${dYear}&week=${dWeek}&tv_id=${tempNewId}`;
      }
		},error:function(data){
		  	console.log(data);
			//window.location.href=location.protocol + "//" + location.host + '/index.php';
		}
	});
  }

  laskePituus();
  function laskePituus(){

	var alku = $("#alku").val().split(':');
	var loppu = $("#loppu").val().split(':');

	if(loppu[0] < alku[0])
	var d2 = new Date(2016, 0, 21, loppu[0], loppu[1]);
	else
	var d2 = new Date(2016, 0, 20, loppu[0], loppu[1]);

	var d1 = new Date(2016, 0, 20, alku[0], alku[1]);
	var seconds =  (d2- d1)/1000;
	var sec = seconds;
	var h = sec/3600 ^ 0 ;
	var m = (sec-h*3600)/60 ^ 0 ;

	$("#tvPituus").html((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
  }

  $('#asiakas').keyup(function(){
	var thisVal = $(this).val();

	if( thisVal.length >= 2 )
	{
	  	 $.ajax({
			url: 'asiakas_autocomplete',
			type:'GET',
			async : false,
			data: { "key" : thisVal },
			  success:function(data){
				data = JSON.parse(data);
			  	//console.log(data);
				if(data !== '')
					$('#asiakasAutocompleteResult').html(data).show();
				else
					$('#asiakasAutocompleteResult').html('').show();
			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});

	} else {
					$('#asiakasAutocompleteResult').html('');
	}

	$('.asiakasSelecter').click(function(){
		var thisVal = $(this).attr('for');
		var thisAsiakas = $(this).text();
		  	 $.ajax({
				url: 'getKohdeByAsiakas',
				type:'GET',
				data: { "id" : thisVal },
				  success:function(data){
					data = JSON.parse(data);
				  	//console.log(data);
					$('#<?=$java_prefix?>_kohde').html(data['options']);
					$('#<?=$java_prefix?>_kohde').val(data['first']);
					OsoiteVaihto(data['first']);
					$('#asiakasAutocompleteResult').html('').hide();
					$('#asiakas').val(thisAsiakas);
				  },
				  error:function(data){
				  	console.log(data);
				  }
		 	});
	});

	$('.kohteenSelecter').click(function(){
		var thisVal = $(this).attr('for');
		var thisAsiakas = $(this).text();
		  	 $.ajax({
				url: 'getKohdeById',
				type:'GET',
				data: { "id" : thisVal },
				  success:function(data){
					data = JSON.parse(data);
				  	//console.log(data);
					$('#<?=$java_prefix?>_kohde').html(data);
					OsoiteVaihto(thisVal);
					$('#asiakasAutocompleteResult').html('').hide();
					$('#asiakas').val(thisAsiakas);
				  },
				  error:function(data){
				  	console.log(data);
				  }
		 	});
	});
  });

  $('.timeVuorot').mask('00:00',{
        placeholder: "__:__"
  });

  // Poistaminen
  $('.tvpoisto').click(function(){
	var tilanne = $(this).attr('tilanne');
	var this_id = '<?=$this_id?>';
	if(toistuva == true){
		if( tilanne == 'poista_ketju_kokonaan' )
			var r = confirm('Poista kaikki ketjuun kuuluvat työvuorot ja työparit.');
		if( tilanne == 'poista_pvm' )
			var r = confirm('Poistaanko tämä päivä/henkilö ketjusta?');
		if( tilanne == 'poista_alkaen' )
			var r = confirm('Poistaanko tästä päivästä alkaen kaikki <?=$laatiko_etusukunimi?> työvuorot');
	} else {
		var r = confirm('Haluatko varmasti poistaa?');
	}

	if(r)
	{
        $.ajax({
           url: 'poistaTv?this_id=' + this_id,
	   type:'POST',
	   data: { tilanne : tilanne, pfrom : $('#pfrom').val(), pto : $('#pto').val(), laatikko_pvm : '<?=$laatikko_pvm?>', laatikko_tid : '<?=$laatikko_tid?>' },
           success: function(data){
		data = JSON.parse(data);
		laatikonPaivays();
		//console.log(data);
    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
		window.location.href=location.protocol + "//" + location.host + '/index.php';
 	   }
        });
	}
  });

  $('#alku').blur(function(){
	$(this).removeClass('bg-danger');
	var alku = $("#alku").val().split(':');
	if((alku[1] && alku[1] > 59) || (alku[0] && alku[0] > 23))
	{
		$("#alku").val('');
		alert('Tarkista aika.');
		return false;
	}
	if(!alku[1] & $("#alku").val() !== '')
	{
		var h = $("#alku").val() ^ 0 ;
		var m = 0 ^ 0 ;
		$("#alku").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
		laskePituus();
	}
  });

  $('#loppu').blur(function(){
	$(this).removeClass('bg-danger');
	var loppu = $("#loppu").val().split(':');
	if((loppu[1] && loppu[1] > 59) || (loppu[0] && loppu[0] > 23))
	{
		$("#loppu").val('');
		alert('Tarkista aika.');
		return false;
	}
	if(!loppu[1])
	{
		var h = $("#loppu").val() ^ 0 ;
		var m = 0 ^ 0 ;
		$("#loppu").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
		laskePituus();
	}
  });

  $('#alku, #loppu').on('keyup, change', function(){
	laskePituus();
  });

  if( $('#<?=$java_prefix?>_kohde').val() !== '' ){
	const kohdeElem = document.getElementById("<?=$java_prefix?>_kohde");
	const thisID = kohdeElem.options[kohdeElem.selectedIndex].value;
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/showohje?id='+thisID,
		  success:function(data){
			//console.log(data);
			var d = JSON.parse(data);
			if(d[2] !== ''){
				$('#arvioitu_kesto').html(d[2]);
			} else {
				$('#arvioitu_kesto').html('00:00');
			}

			$("#kohteen_lisatiedot").html('<span style="" class="link fa fa-2x fa-phone avataan_lisatiedot" data-toggle="collapse" data-target="#open_kohde_'+ thisID +'"></span><div style="position:relative;"><div style="position:absolute;top:5px;right: 20px;z-index:9999;background:white;width:220px;border:1px #ccc solid" class="p15 bg-warning collapse" id="open_kohde_'+ thisID +'">Puh.: <b>'+ d[7] +'</b><br>Sähköposti: <b>'+ d[8] +'</b></div></div>');

			if(d[11])
				$('#kohteen_tiedostot').html(d[11]);
			if(d[14]) {
				$("#client_finnish_service_wish").html(d[14]);
			}
			if(d[15]) {
				$("#client_number").html(d[15]);
			}
	   	},
		error:function(data){
			console.log(data);
	    	}
	  });
  }

  $(document).delegate("#<?=$java_prefix?>_kohde","change",function(){
	var thisID = $(this, 'option:selected').val();
	OsoiteVaihto(thisID);
  });
  function OsoiteVaihto(thisID){
	$('#kohteen_tiedostot').html('');
	$("#kohteen_lisatiedot").html('');
	$('#<?=$java_prefix?>_status').val('3').css({"border" : "1px green solid"});
	$('#<?=$java_prefix?>_tyoajanlaatu').val('');
	$("#<?=$java_prefix?>_kohde").removeClass('bg-danger');
	var tyo_erittelyt 	= '';
	var url_links 		= '';
	var tv_id 		= '<?php if(isset($model->id)){ echo $model->id; } ?>';
	linkkiKohteeseen();
	$.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/showohje?id='+ thisID +'&tv_id='+ tv_id,
		  success:function(data){
			//console.log(data);
			var d = JSON.parse(data);
			$('.ohje').html(d[0]);
			$('#<?=$java_prefix?>_tietoja').val(d[1]);
			$('#<?=$java_prefix?>_osoite').val(d[3]);
			$('#<?=$java_prefix?>_postinumero').val(d[4]);
			$('#<?=$java_prefix?>_postitoimipaikka').val(d[5]);

			// <-- tyo_erittelyt 
			$("#erittelynlista").html('');
			if($.isArray(d[6])){
			$.each(d[6], function( index, value ) {
			  tyo_erittelyt += '' +
				 '<div class="row">' +
				  '<div class="col-sm-11">' +
				   '<input type="text" name="<?=$java_prefix?>[tyo_erittelyt][]" class="form-control input-sm" value="'+ value +'">' +
				  '</div>' +
				  '<div class="col-sm-1 text-right">' +
				   '<span class="link text-danger fa fa-trash poislistasta"></span>' +
				  '</div>' +
		 		 '</div>';
			});
			}
			$("#erittelynlista").html('<div class="col-sm-6 erittelynlista_laatiko"><legend>Työerittelyt</legend>' + tyo_erittelyt + '</div>');
			//    tyo_erittelyt -->

			// <-- url linkit 
			$("#linkkilista").html('');
			if(d[12]){
			$.each(d[12], function( index, value ) {
			  url_links += '' +
			 '<div class="row">' +
			  '<div class="col-sm-5">' +
			   '<input type="text" name="<?=$java_prefix?>[url_linkkit][nimike][]" class="form-control" placeholder="URL nimike" value="'+ index +'">' +
			  '</div>' +
			  '<div class="col-sm-5">' +
			   '<input type="text" name="<?=$java_prefix?>[url_linkkit][url][]" class="form-control" placeholder="http osoite" value="'+ value +'">' +
			  '</div>' +
			  '<div class="col-sm-1 text-right">' +
			   '<span class="btn btn-danger fa fa-trash poislistasta"></span>' +
			  '</div>' +
	 		 '</div>';
			});
			}
			$("#linkkilista").html('<div class="col-sm-6 linkkilista_laatiko"><legend>URL linkit</legend>' + url_links + '</div>');
			//    url linkit -->

			if(d[2] !== ''){
				$('#arvioitu_kesto').html(d[2]);
			} else {
				$('#arvioitu_kesto').html('00:00');
			}

			$("#kohteen_lisatiedot").html('<span style="" class="link fa fa-2x fa-phone avataan_lisatiedot" data-toggle="collapse" data-target="#open_kohde_'+ thisID +'"></span><div style="position:relative;"><div style="position:absolute;top:5px;right: 20px;z-index:9999;background:white;width:220px;border:1px #ccc solid" class="p15 bg-warning collapse" id="open_kohde_'+ thisID +'">Puh.: <b>'+ d[7] +'</b><br>Sähköposti: <b>'+ d[8] +'</b></div></div>');

			if(d[9] !== '')
				$('#alku').val(d[9]);
			if(d[10] !== '')
				$('#loppu').val(d[10]);
			if(d[11])
				$('#kohteen_tiedostot').html(d[11]);
			if(d[13])
				$('#<?=$java_prefix?>_tuoteID').val(d[13]);

			// set finnish service wish text
			if(d[14]) {
				$("#client_finnish_service_wish").html(d[14]);
			}
			if(d[15]) {
				$("#client_number").html(d[15]);
			}
			return false;

		}, error:function(data){
			console.log(data);
		}
	});
  }
  // <-- lisapalvelu_tuote
  $('.plus_lisapalvelu').click(function(){
	var lisapalvelu_tuote = $('#lisapalvelu_tuote option:selected').val();
	var lisapalvelu_yksikko = $('#lisapalvelu_tuote option:selected').attr('yksikko');
	var lisapalvelu_maara = $('#lisapalvelu_maara').val();
	if( lisapalvelu_tuote === '' ){
		$('#lisapalvelu_tuote').css({'border' : '1px red solid'}).focus();
		return false;
	}
	if( lisapalvelu_maara === '' ){
		$('#lisapalvelu_maara').css({'border' : '1px red solid'}).focus();
		return false;
	}

	var lp_lista = $("#lisapalvelu_lista").text().trim();
	if( lp_lista == '' ){
		$("#lisapalvelu_lista").append('<div class="col-sm-6 lisapalvelu_laatiko"><legend>Lisäpalvelut</legend>');
	}

	$('.lisapalvelu_laatiko').append('' +
	'<div class="row">' +
	 '<div class="col-sm-11">' +
		$('#lisapalvelu_tuote option:selected').text() + ': <b>' + $('#lisapalvelu_maara').val() + ' '+ lisapalvelu_yksikko +'</b>' +
		'<input type="hidden" name="<?=$java_prefix?>[lisa_tuotteet][tuote][]" value="'+ $('#lisapalvelu_tuote option:selected').val() +'">' +
	 '</div>' +
	 '<div class="col-sm-1">' +
		'<div class="pull-right">' + 
			'<span class="link fa fa-trash text-danger poista_lisa"></span>' +
		'</div>' +
		'<input type="hidden" name="<?=$java_prefix?>[lisa_tuotteet][maara][]" value="'+ $('#lisapalvelu_maara').val() +'">' +
	 '</div>' +
	'</div>' );

	if( lp_lista == '' ){
		$(".lisapalvelu_laatiko").append('</div>');
	}


	$('#lisapalvelu_tuote').css({'border' : '1px green solid'}).val('');
	$('#lisapalvelu_maara').css({'border' : '1px green solid'}).val('');
  });
  $(document).delegate(".poista_lisa","click",function(){
	$(this).closest('.row').remove();
  });
  $("#lisapalvelu_tuote").change(function(){
	$("#lisapalvelu_maara").css({'border' : '1px red solid'}).focus();
  });
  // lisapalvelu_tuote -->
  $(".uusierittely").click(function(){
	var er_lista = $("#erittelynlista").text().trim();
	if( er_lista == '' )
		$("#erittelynlista").append('<div class="col-sm-6 erittelynlista_laatiko"><legend>Työerittelyt</legend>');

	$(".erittelynlista_laatiko").append('' +
		 '<div class="row">' +
		  '<div class="col-sm-11">' +
		   '<input type="text" name="<?=$java_prefix?>[tyo_erittelyt][]" class="form-control input-sm">' +
		  '</div>' +
		  '<div class="col-sm-1 text-right">' +
		   '<span class="link text-danger fa fa-trash poislistasta"></span>' +
		  '</div>' +
 		 '</div>'
	);
	if( er_lista == '' )
		$(".erittelynlista_laatiko").append('</div>');

	$(".erittelynlista_laatiko input:last").focus();
  });
  $(".uusilinkki").click(function(){
	var url_lista = $("#linkkilista").text().trim();
	if( url_lista == '' )
		$("#linkkilista").append('<div class="col-sm-6 linkkilista_laatiko"><legend>URL linkit</legend>');

	$(".linkkilista_laatiko").append('' +
			 '<div class="row">' +
			  '<div class="col-sm-5">' +
			   '<input type="text" name="<?=$java_prefix?>[url_linkkit][nimike][]" class="form-control" placeholder="URL nimike">' +
			  '</div>' +
			  '<div class="col-sm-5">' +
			   '<input type="text" name="<?=$java_prefix?>[url_linkkit][url][]" class="form-control" placeholder="http osoite">' +
			  '</div>' +
			  '<div class="col-sm-1 text-right">' +
			   '<span class="btn btn-danger fa fa-trash poislistasta"></span>' +
			  '</div>' +
	 		 '</div>'
	);
	if( url_lista == '' )
		$(".linkkilista_laatiko").append('</div>');

	$(".linkkilista_laatiko input:last").focus();
  });
  $(document).delegate(".poislistasta","click",function(){
	$(this).closest(".row").remove();
  });

  $(".uusimuistinpanno").click(function(){
	var mp_lista = $("#muistiinpanolista").text().trim();
	if( mp_lista == '' )
		$("#muistiinpanolista").append('<p><div class="row panel-footer"><div class="col-sm-12 muistiinpanolista_laatiko"><legend>Muistiinpanot</legend>');

	$(".muistiinpanolista_laatiko").append('' +
		 '<div class="row">' +
		  '<div class="col-sm-11">' +
		   '<textarea name="<?=$java_prefix?>[muistiinpano][]" class="form-control"></textarea>' +
		  '</div>' +
		  '<div class="col-sm-1 text-right">' +
		   '<span class="link text-danger fa fa-trash pois_muistiinpano"></span>' +
		  '</div>' +
 		 '</div>'
	);
	if( mp_lista == '' )
		$(".muistiinpanolista_laatiko").append('</div></div></p>');

	$(".muistiinpanolista_laatiko textarea:last").val('<?=date("d.m.Y H:i")?> - <?=Yii::app()->user->nimi?>:\n').focus();
  });
  $(document).delegate(".pois_muistiinpano","click",function(){
	$(this).closest(".row").remove();
  });

  linkkiKohteeseen();
  function linkkiKohteeseen(){
	// seems to be much after than the jQuery version
	let selectedKohde = document.getElementById("<?=$java_prefix?>_kohde");
	let kohdeName = selectedKohde.options[selectedKohde.selectedIndex].text;
	let url = location.protocol + "//" + location.host + '/index.php/kohteet/update?id='+ selectedKohde.value;
	$("#kohde_url").html('<a href="'+url+'" target="_blank">Muokkaa kohdetta '+kohdeName+'</a>');
  }

  // <-- modal siirtaminen
  $("#modal-form").find(".panel-heading").hover(function() {
	    $(this).css('cursor','pointer');
	}, function() {
	    $(this).css('cursor','auto');
  });
  $('#modal-form').draggable({
            handle: ".panel-heading",
	    revert:"invalid",
  });
  // modal siirtaminen -->


/* Hyva malli
	$('.pakotta_luoda_yskittainen').focus(function() {
		prev_val = $(this).val();
	}).change(function() {
		c = confirm('Tämä muutos pakottaa ota pois tämä päivä toistuvasta ketjusta.\nHaluatko jatkaa?');
		if(!c){
			$(this).val(prev_val);
			return false;
		}
		$('#is_toistuva').bootstrapSwitch('state', false);
		$($('.pakotta_luoda_yskittainen')).each(function() {
			$(this).css({'border':'1px #dddddd solid'});
		});
	});
*/
});
</script>


<!-- #region Omasiistijät JS -->
<?php if (isset($model->id) && !empty(Yii::app()->user->kp)): ?>
<script>

  // Hide the selection and notification button until warnings are shown.
  $('#omasiistija-toiminnot').hide();
  
  // Tallennetut omasiistijät tarkistusta varten.
  let omasiistijat = [],
      omasiistijaVaroitusTila = false,
      omasiistijatOverride = {};

  /**
   * Toggle the regulars warning display.
   */
  const omasiistijaVaroitusToggle = function(val = null) {
    if ((val !== null ? (val !== false) : !omasiistijaVaroitusTila)) {
      $('#omasiistija-toiminnot').show();
      if ($('#<?= $java_prefix ?>_omasiistijailmoitus').val() > 0) {
        $('#omasiistijat-ilmoita').attr('disabled', 'disabled');
        $('#omasiistija-varoitus').hide();
        omasiistijaVaroitusTila = false;
      } else {
        $('#omasiistijat-ilmoita').removeAttr('disabled')
        $('#omasiistija-varoitus').show();
        omasiistijaVaroitusTila = true
      }
    } else {
      $('#omasiistijat-ilmoita').attr('disabled', 'disabled');
      $('#omasiistija-varoitus').hide();
      omasiistijaVaroitusTila = false;
    }
    return omasiistijaVaroitusTila;
  };

  /**
  * Tarkistetaan että valituissa työntekijöissä on vähintään yksi joka on
  * käynyt kohteessa aiemmin (omasiistijä).
  */
  const omasiistijaTarkistus = function() {

    // Get current selected worker and pairs.
    //let tid = $('#tekijanVaihdo option:selected').val();
	const tekijanVaihdoElem = document.getElementById("tekijanVaihdo");
	const tid = tekijanVaihdoElem.value;
	// get coworkers, value can be null... default to empty arr
	const tyoparit = $("#tyopaari").val() ?? [];

    // Assign overrides.
    omasiistijatOverride['tid'] = tid;
    omasiistijatOverride['tyopaari'] = JSON.stringify(tyoparit);
    omasiistijatOverride['peruutettu'] = $('#<?= $java_prefix; ?>_peruutettu').val();
    omasiistijatOverride['omasiistijavaroitus'] = $('#<?= $java_prefix ?>_omasiistijavaroitus').val();
    // omasiistijatOverride['omasiistijailmoitus'] = $('#<?= $java_prefix ?>_omasiistijailmoitus').val();

    // Do checks only if virtual, as otherwise no warnings are shown.
    if (omasiistijatOverride['omasiistijavaroitus'] == 0) {
      omasiistijaVaroitusToggle(false);
      return false;
    }

    $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/omasiistijat_tarkistus`, {

      type: 'POST',
      data: {
        'shift_ids': '<?= $this_id ?>',
        'override': JSON.stringify(omasiistijatOverride)
      },

      error: function(xhr, status, error) {
        console.log(`(Omasiistijävaroituksen tarkastus epäonnistui. Virhe: ${xhr.responseText}`);
      },

      success: function(data) {
        // Output 1 means warnings should be shown; in any other case, hide the warnings.
        console.log(`(Omasiistijävaroituksen tarkastus: Received response: ${data}`);
        omasiistijaVaroitusToggle(data == 1);
      }
    });
  };

  // Aina kun työntekijä vaihdetaan yläreunan valikosta, tarkistetaan
  // omasiistijän tilanne uusiksi, jotta varoitus voidaan näyttää/piilottaa.
  // Sama tehdään kun valintoja muutetaan työparilistalla.
  $('#tekijanVaihdo, #tyopari-container .mult, #<?= $java_prefix; ?>_omasiistijavaroitus, ' +
    '#<?= $java_prefix; ?>_omasiistijailmoitus, #<?= $java_prefix; ?>_peruutettu').change(function() {
    omasiistijaTarkistus();
  });

  // select 2 doesn't work on the traditional on change event listener
  // like the one we have above, so we'll need to create a separate 
  // listener for it.
  $("#tyopaari").select().on("change", (e) => {
	omasiistijaTarkistus();
  });

  /**
   * Ajetaan tämä funktio aina kun kohde vaihdetaan, tai kun sivu ladataan
   * ensimmäistä kertaa. Tämä hakee omasiistijät ja tarkistaa että ainakin
   * yksi valituista siistijöistä on käynyt kohteessa; muuten, näytetään
   * varoitus. Samalla kerrotaan omasiistijänäkymälle mikä kohde kyseessä.
   */
  const kohteenVaihto = function() {

    $(".sw").bootstrapSwitch({
      size: "small",
      onColor: "primary",
      offColor: "danger",
      onText: "Kyllä",
      offText: "Ei"
    });

    // Piilotetaan mahdollisesti auki oleva lista.
    $('#<?= $omasiistijat_div_id ?>.in').collapse('hide');

    // Haetaan valittu arvo kohdelistasta.
	const kohdeElem = document.getElementById(`<?=$java_prefix?>_kohde`);
	const valittuKohde = kohdeElem.value;

    // Asetetaan omasiistijänäkymän piilotettuun kenttään uusi ID, jonka
    // avulla omasiistijänäkymä hakee omasiistijät listalleen.
    $('#<?= $omasiistijat_placeholder_id ?>').text(valittuKohde);

    // Kohde vaihdettu, tai kortti juuri avattu. Haetaan omasiistijälista.
    // Haetaan omasiistijät, jotta voidaan näyttää varoitus jos ei ole valittuna.
    omasiistijat = [];
    $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/omasiistijat_lista`, {

      type: 'POST',
      data: {
        'location_id': valittuKohde,
        'force_refresh': false, // TODO: selection
      },

      error: function(xhr, status, error) {
        $(`#${workersDivId} .well`).html(`Pyynnössä tapahtui virhe: ${xhr.responseText}`);
        console.log(`(Omasiistijähaku kohteelle ${valittuKohde}) Error: ${xhr.responseText}`);
      },

      success: function(data) {

        console.log(`(Omasiistijähaku kohteelle ${valittuKohde}) Received response, length: ${data.length}`);
        let parsed = null;
        try {
          parsed = JSON.parse(data);
        } catch (e) {
          console.log(`(Omasiistijähaku kohteelle ${valittuKohde}) Error: Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
          return;
        }

        if (typeof(parsed) != "object") {
          console.log(`(Omasiistijähaku kohteelle ${valittuKohde}) Error: Parsed data is unusable (not an object).`);
        } else {
          $.each(parsed, (index, item) => { omasiistijat.push(item['id']); });
          //parsed.forEach((item, index) => { omasiistijat.push(item['id']); });
        }

        // Kohteen vaihdon/initialisaation yhteydessä tarkistetaan omasiistijät.
        omasiistijatOverride['kohde'] = valittuKohde;
        omasiistijaTarkistus();
      }
    });
  };

  // Vaihdetaan omasiistijälistan tila aina kun kohde vaihdetaan.
  $('#<?= ($toistuva ? 'ToistuvatTyovuorot' : 'Tyovuoroot'); ?>_kohde').on('change', function(e) {
    kohteenVaihto();
  });

  // Asetetaan kohde omasiistijälistalle heti työvuoroa avatessa.
  kohteenVaihto();

  // Omasiistijät ilmoita -painike.
  <?php if (isset($model->kohteet->asiakas_id)): ?>
  $('#omasiistijat-ilmoita').on('click', function(e) {
    e.preventDefault();
    if (!confirm(`Haluatko varmasti lähettää ilmoituksen asiakkaan sähköpostiin? Huom. sivu päitivvyy lähettämisen jälkeen, jossa saattaa mennä hetki.`)) {
      return false;
    }

    if ($('#is_toistuva').bootstrapSwitch('state') === true) {
      alert('Omasiistijäilmoitus on tehtävä työvuorokohtaisesti, jolloin vuoro poistuu ketjusta. Ota pois valinta kohdasta "Toistuva Työvuoro".');
      return false;
    }

    // Build list of worker names for the notification.
    let workers = [];
    workers.push($('#tekijanVaihdo option:selected').text());
    $('#tyopari-container .multiselect-container li.active a label').each(function() {
      workers.push($(this).text());
    });

    // Just check in case there are some changes to the form.
    if (workers.length == 0) {
      alert("Siistijöiden listan rakentaminen ilmoitusta varten epäonnistui. Ota yhteys ylläpitoon.");
      return false;
    }
    let workersJson = JSON.stringify(workers);

    // Perform notification.
    $.ajax(`${location.protocol}//${location.host}/index.php/tyovuoroot/omasiistijat_ilmoitus`, {

      type: 'POST',
      data: {
        'customer_id': <?= $model->kohteet->asiakas_id; ?>,
        'names': workersJson
      },

      // Error handling just in case.
      error: function (xhr, status, error) {
        alert(`Omasiistijäilmoituksen lähetyksessä tapahtui sisäinen virhe: ${xhr.responseText}`);
        console.log(xhr.responseText);
      },

      // Success, parse received JSON.
      success: function (data) {
        console.log(data);

        // Try parse response JSON.
        let parsed = null;
        try {
          parsed = JSON.parse(data);
        } catch (e) {
          console.log(`Failed to parse response JSON. Error: ${e}\nResponse data: ${data}`);
          alert("Omasiistijäilmoituksen lähetyksessä tapahtui virhe: palvelin palautti viallisen tuloksen.");
          return false;
        }

        // Check if parsing failed. Notify log and let it go.
        if (typeof (parsed) != "object") {
          console.log("Parsed data is unusable (not an object).");
          alert("Omasiistijäilmoituksen lähetyksessä tapahtui virhe: palvelin palautti viallisen tuloksen.");
          return false;
        }

        // Check if data is empty, which means possible server error.
        if (parsed.length == 0) {
          console.log("Empty response received.");
          alert("Omasiistijäilmoituksen lähetyksessä tapahtui virhe: tyhjä vastaus vastaanotettu palvelimelta.");
          return false;
        }

        // Check if empty message, meaning logical fault.
        if (!('message' in parsed) || parsed.message.length == 0) {
          alert(`Omasiistijäilmoituksen lähetyksessä tapahtui virhe: palvelin ei palauttanut vastausta.`);
          return false;
        }

        // Check if operation failed.
        if (!('success' in parsed) || parsed.success != true) {
          alert(`Omasiistijäilmoituksen lähetyksessä tapahtui virhe: ${parsed.message}`);
          return false;
        }

        // Everything is normal; notification has been sent. Notify the user
        // with the returned result message, update the selection box and
        // disable the button for sending the notification.
        console.log(parsed.message);
        // alert(parsed.message);
        $('#<?= $java_prefix ?>_omasiistijailmoitus').val(1);
        $('#omasiistijat-ilmoita').attr('disabled', 'disabled');

        // The model needs to be saved, whether it is cyclic or not. If cyclic,
        // the shift must be removed from it (toistuvasta irroittaminen).
        // Set flag for submit so it knows to redirect BACK to this form.
        submitRedirectBack = true;
        $('#tyovuoroot-form').submit();
      }
    })
  });
  <?php endif; ?>
</script>
<?php endif; ?>
<!-- #endregion -->
