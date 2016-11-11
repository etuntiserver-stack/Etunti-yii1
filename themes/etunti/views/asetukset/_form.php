<?php
/* @var $this AsetuksetController */
/* @var $model Asetukset */
/* @var $form CActiveForm */



     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asetukset-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>


		<?php echo $form->hiddenField($model,'id'); ?>
		<?php echo $form->error($model,'id'); ?>

<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#jarjestAsetukset"><h3><?php echo Yii::t('main','Järjestelmän asetukset'); ?> &nbsp;<i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

<div class="row form collapse" id="jarjestAsetukset">
  <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Järjestelmän asetukset'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'logon_polkku'); ?>
		<?php echo $form->textField($model,'logon_polkku',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'logon_polkku'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'logon_korkeus'); ?>
		<?php echo $form->textField($model,'logon_korkeus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'logon_korkeus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'johtaja'); ?>
		<?php echo $form->textField($model,'johtaja',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'johtaja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aikavali_halytys'); ?>
		<?php 
        	$l = array(
			10=>10,
			20=>20,
			30=>30,
			40=>40,
			50=>50,
			60=>60
		);
		echo $form->dropDownList($model,'aikavali_halytys', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>
		<?php echo $form->error($model,'aikavali_halytys'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ilmoitus_myohastyneista_kohteesta_sahkopostiin'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'ilmoitus_myohastyneista_kohteesta_sahkopostiin', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>
		<?php echo $form->error($model,'ilmoitus_myohastyneista_kohteesta_sahkopostiin'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ilmoitus_avoimista_kohteesta_sahkopostiin'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'ilmoitus_avoimista_kohteesta_sahkopostiin', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>
		<?php echo $form->error($model,'ilmoitus_avoimista_kohteesta_sahkopostiin'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ilmoitus_merkkipaivasta'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'ilmoitus_merkkipaivasta', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>
		<?php echo $form->error($model,'ilmoitus_merkkipaivasta'); ?>
	</div>


  </div><div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Järjestelmän asetukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'pyhapaivat'); ?>
		<?php echo $form->textarea($model,'pyhapaivat',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pyhapaivat'); ?>
	</div>

<!--
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'merkkipaivailmoitukset_sahkoposti'); ?>
		<?php echo $form->textField($model,'merkkipaivailmoitukset_sahkoposti',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'merkkipaivailmoitukset_sahkoposti'); ?>
	</div>
-->

  </div><div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Järjestelmän asetukset'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'erikoislauantai'); ?>
		<?php echo $form->textarea($model,'erikoislauantai',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'erikoislauantai'); ?>
	</div>

  </div>
</div><!-- form -->



<!-- Työvuorot -->
<?php if(in_array('2',$tas)) : ?>
<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#tyovuoroAsetukset"><h3><?php echo Yii::t('main','Työvuorot'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="tyovuoroAsetukset">
   <div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Työvuorot asetukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_tyovuorossa'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'asiakas_tyovuorossa', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>
		<?php echo $form->error($model,'asiakas_tyovuorossa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'paikkakunta_tyovuorossa'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'paikkakunta_tyovuorossa', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>
		<?php echo $form->error($model,'paikkakunta_tyovuorossa'); ?>
	</div>

   </div>

   <div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Toistuvat työvuorot'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen'); ?>
		<?php echo $form->numberField($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen',array('maxlength'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta_paivat_ennen'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat'); ?>
		<?php echo $form->textarea($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control', 'placeholder' => "sähköposti1@testi.fi\nsähköposti2@testi.fi")); ?>
		<?php echo $form->error($model,'ilmoitus_toistuvien_tyovuorojen_paattymisesta_saajat'); ?>
	</div>
   </div>


   <div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Työvuorojen lähetys'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyovuorolahetys_naytetaanko_asiakas'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'tyovuorolahetys_naytetaanko_asiakas', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'tyovuorolahetys_naytetaanko_asiakas'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka'); ?>
	</div>

   </div>

  </div>
<?php endif; ?>
<!-- Työvuorot -->



<?php if(in_array('3',$tas)) : ?>
<br>

<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#laskutuksenAsetukset"><h3><?php echo Yii::t('main','Laskutuksen asetukset'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="laskutuksenAsetukset">
    <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Laskutuksen asetukset'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tilinumero'); ?>
		<?php echo $form->textField($model,'tilinumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tilinumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'iban'); ?>
		<?php echo $form->textField($model,'iban',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'iban'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'bic'); ?>
		<?php echo $form->textField($model,'bic',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'bic'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->textField($model,'viivastyskorko',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palvelu_tyyppi'); ?>
		<?php 
        	$tal = array(1=>'POSTITA',2=>'TRUST',3=>'MANUAL',4=>'NETVISOR');
		echo $form->dropDownList($model,'palvelu_tyyppi', $tal, 
		array('empty'=>'Valitse palvelu','class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'palvelu_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lasku_asiakasnumero'); ?>
		<?php 
        	$tal = array(0=>'Automaatiseesti',1=>'Itse');
		echo $form->dropDownList($model,'lasku_asiakasnumero', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'lasku_asiakasnumero'); ?>
	</div>

    </div><div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','POSTITA.FI tunnukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postita_username'); ?>
		<?php echo $form->textField($model,'postita_username',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'postita_username'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postita_password'); ?>
		<?php echo $form->textField($model,'postita_password',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'postita_password'); ?>
	</div>

    </div><div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','TRUST.FI tunnukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_url'); ?>
		<?php echo $form->textField($model,'trust_url',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_url'); ?>
	</div>




	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_cid'); ?>
		<?php echo $form->textField($model,'trust_cid',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_cid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_api'); ?>
		<?php echo $form->textField($model,'trust_api',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_api'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_ws_api_url'); ?>
		<?php echo $form->textField($model,'trust_ws_api_url',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_ws_api_url'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_ws_cid'); ?>
		<?php echo $form->textField($model,'trust_ws_cid',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_ws_cid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'trust_ws_salasana'); ?>
		<?php echo $form->textField($model,'trust_ws_salasana',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'trust_ws_salasana'); ?>
	</div>

    </div>
  </div>
<?php endif; ?>



<?php if(in_array('4',$tas)) : ?>
<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#onlinevarauksenAsetukset"><h3><?php echo Yii::t('main','Onlinevaraus'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i><h3></div>

  <div class="row form collapse" id="onlinevarauksenAsetukset">
    <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Onlinevaraus'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'checkout_id'); ?>
		<?php echo $form->textField($model,'checkout_id',array('size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'checkout_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'checkout_salasana'); ?>
		<?php echo $form->textField($model,'checkout_salasana',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'checkout_salasana'); ?>
	</div>

	<br>
	<div class="section fill mb5">

		<p><a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/asetukset/rekisteriseloste"><?php echo Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'); ?> </a></p>
		

   <?php
   foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/onlinevarausehdot.*')) as $file) 
   {
	$explNimi = explode("/",$file);
 	echo '<a href="../../'.$file.'">'.end($explNimi).'</a>';
	
   }
   ?>
		<p><a href="#onlinevarausehdot"><?php echo Yii::t('main','Onlinevarausehdot lataa'); ?> </a></p>

	</div>


    </div><div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Viikonloppulisät'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viikonloppulisa_la'); ?>
		<?php echo $form->numberField($model,'viikonloppulisa_la',array('size'=>20,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viikonloppulisa_la'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viikonloppulisa_su'); ?>
		<?php echo $form->numberField($model,'viikonloppulisa_su',array('size'=>20,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viikonloppulisa_su'); ?>
	</div>

	<legend><h2><?php echo Yii::t('main', 'Varauksen aikarajat'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_aikaisintaan_paivamaara'); ?>
		<?php echo $form->numberField($model,'onlinevaraus_aikaisintaan_paivamaara',array('maxlength'=>2,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_aikaisintaan_paivamaara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_alku'); ?>
		<?php 
        	$l = array();
		for ($i = 1; $i <= 24; $i++) {
        		$l[$i] = $i;
		}
		echo $form->dropDownList($model,'onlinevaraus_alku', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'onlinevaraus_alku'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_loppu'); ?>
		<?php 
        	$l = array();
		for ($i = 1; $i <= 24; $i++) {
        		$l[$i] = $i;
		}
		echo $form->dropDownList($model,'onlinevaraus_loppu', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'onlinevaraus_loppu'); ?>
	</div>

    </div><div class="col-sm-5">

    <legend><h2><?php echo Yii::t('main','Muut'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_laatu_luotettavuus'); ?>
		<?php echo $form->textarea($model,'onlinevaraus_laatu_luotettavuus',array('rows'=>4,'maxlength'=>5000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_laatu_luotettavuus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_takuu_turvallisuus'); ?>
		<?php echo $form->textarea($model,'onlinevaraus_takuu_turvallisuus',array('rows'=>4,'maxlength'=>5000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_takuu_turvallisuus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_asiakaspalvelu'); ?>
		<?php echo $form->textarea($model,'onlinevaraus_asiakaspalvelu',array('rows'=>4,'maxlength'=>5000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_asiakaspalvelu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_arvio_siivouksesta'); ?>
		<?php echo $form->textarea($model,'onlinevaraus_arvio_siivouksesta',array('rows'=>4,'maxlength'=>5000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'onlinevaraus_arvio_siivouksesta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tilausvahvistus'); ?>
		<?php echo $form->textarea($model,'tilausvahvistus',array('rows'=>4,'maxlength'=>5000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tilausvahvistus'); ?>
	</div>

    </div>

    <div class="col-sm-12">
    <h4><?php echo Yii::t('main','Onlinevarauksen osoite'); ?>  :</h4> <?php echo Yii::app()->getBaseUrl(true).'/index.php/onlinevaraus/index?domain='.Yii::app()->user->domain; ?>
    </div>

  </div>
<?php endif; ?>

<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#sovellusAsetukset"><h3><?php echo Yii::t('main','Mobiilisovelluksen asetukset'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="sovellusAsetukset">
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Sovelluksen asetukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sovellus_tyovuorot'); ?>
		<?php 
        	$tal = array(
			1=>'Vain tämä viikko suunnuntai asti',
			2=>'Tästä päivä alkaen +7pv',
			3=>'Tästä päivä alkaen +14pv'
		);
		echo $form->dropDownList($model,'sovellus_tyovuorot', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'sovellus_tyovuorot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'show_name'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'show_name', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'show_name'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_show_phone'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_show_phone', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_show_phone'); ?>
	</div>
   </div>

   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Sovelluksen asetukset'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_lopettaa_vain_tagilla'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_lopettaa_vain_tagilla', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_lopettaa_vain_tagilla'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ilmoitus_uudesta_kuvasta_saajat'); ?>
		<?php echo $form->textarea($model,'ilmoitus_uudesta_kuvasta_saajat',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control', 'placeholder' => "sähköposti1@testi.fi\nsähköposti2@testi.fi")); ?>
		<?php echo $form->error($model,'ilmoitus_uudesta_kuvasta_saajat'); ?>
	</div>

   </div>


  </div>


<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#netvisorAsetukset"><h3><?php echo Yii::t('main','Netvisor asetukset'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="netvisorAsetukset">
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Netvisor'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_kaytto'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'netvisor_kaytto', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'netvisor_kaytto'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_organisation_identifier'); ?>
		<?php echo $form->textField($model,'netvisor_organisation_identifier',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_organisation_identifier'); ?>
	</div>

   </div>
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','ID'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_customer_id'); ?>
		<?php echo $form->textField($model,'netvisor_customer_id',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_customer_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_partner_id'); ?>
		<?php echo $form->textField($model,'netvisor_partner_id',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_partner_id'); ?>
	</div>

   </div>
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','KEY'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_userkey'); ?>
		<?php echo $form->textField($model,'netvisor_userkey',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_userkey'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_partnerkey'); ?>
		<?php echo $form->textField($model,'netvisor_partnerkey',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_partnerkey'); ?>
	</div>

   </div>
  </div>


<?php if(in_array('5',$tas)) : ?>
<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#vinkkiAsetukset"><h3><?php echo Yii::t('main','Vinkki asetukset'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="vinkkiAsetukset">
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','VINKKI'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'vinkki_tunnit'); ?>
		<?php echo $form->numberField($model,'vinkki_tunnit',array('maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'vinkki_tunnit'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'vinkki_prosentti'); ?>
		<?php echo $form->numberField($model,'vinkki_prosentti',array('maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'vinkki_prosentti'); ?>
	</div>

   </div>
  </div>
<?php endif; ?>


<br>
<br>
	<p><div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-success')); ?>
	</div></p>

<?php $this->endWidget(); ?>


<?php
/*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'syntyrin_emails'); ?>
		<?php echo $form->textArea($model,'syntyrin_emails',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'syntyrin_emails'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'paivan_uutinen'); ?>
		<?php echo $form->textField($model,'paivan_uutinen',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'paivan_uutinen'); ?>
	</div>
*/
?>
