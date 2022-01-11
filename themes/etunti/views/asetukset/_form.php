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

<!--
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'pyhapaivat'); ?>
		<?php echo $form->textarea($model,'pyhapaivat',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pyhapaivat'); ?>
	</div>
-->

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyontekijan_etunimi_sukunimi_jarjestys'); ?>
		<?php 
        	$l = array(
			0=>'Ensin Etunimi',
			1=>'Ensin Sukunimi'
		);
		echo $form->dropDownList($model,'tyontekijan_etunimi_sukunimi_jarjestys', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'tyontekijan_etunimi_sukunimi_jarjestys'); ?>
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
		<?php $domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' "); ?>
		<?php echo $form->labelEx($model,'kirjautumistunnus'); ?>
		<?php echo $form->textField($model,'kirjautumistunnus',array('value' => ((isset($domainit->kirjautumistunnus))? $domainit->kirjautumistunnus:''), 'size'=>20,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'kirjautumistunnus'); ?>
	</div>
  </div>
</div><!-- form -->

<!-- Asiakas -->
<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#asiakasAsetukset"><h3><?php echo Yii::t('main','Asiakas'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="asiakasAsetukset">
   <div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Asiakkaan tiedot'); ?></h2></legend>
	<div class="section fill mb5 ">

		<?php echo $form->labelEx($model,'asiakas_myyja'); ?>
		<?php echo $form->dropDownList($model, 'asiakas_myyja', CHtml::listData(Administrators::model()->findAll(), 'id', 'adm_nimi'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'asiakas_myyja'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_tyoryhma'); ?>
		<?php
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
	       	$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = "select_type='tyoryhma'";
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
		$criteria->addCondition ("value2 LIKE '%\"".Yii::app()->user->adminID."\"%'");
		}

		$listData = Valikkoot::model()->findAll($criteria);
		?>
		<?php echo $form->dropDownList($model, 'asiakas_tyoryhma', CHtml::listData($listData, 'id', 'value'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'asiakas_tyoryhma'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_ryhma'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma_real' ",array('order' => "select_type"));
		if(count($l) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "asiakas_ryhma_real";
			$new_val->value = "Testi ryhmä";
			if($new_val->save())
	      			$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma_real' ",array('order' => "select_type"));
			else
				var_dump($new_val->getErrors());
		}

			$arr = json_decode($model->asiakas_ryhma);
			echo '<select name="Asetukset[asiakas_ryhma][]" class="ryhmat form-control" multiple title="Valitse">';
			foreach($l as $data)
			{
				if(is_array($arr) and in_array($data->id, $arr))
			    		echo '<option value="'.$data->id.'" selected>'.$data->value.'</option>';
				elseif(!is_array($arr) and $model->asiakas_ryhma == $data->id)
			    		echo '<option value="'.$data->id.'" selected>'.$data->value.'</option>';
				else
			    		echo '<option value="'.$data->id.'">'.$data->value.'</option>';
			}
			echo '</select>';
		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="asiakas_ryhma_real"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'asiakas_ryhma'); ?>
	</div>
   </div>

	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select_valiko.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

/* valikot */
$(".muokaValiko").click(function() {
    var thisFor = $(this).attr("for");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko",
	   type:'POST',
	   data: { "select_type" : thisFor },
           success: function(data){
		console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */

$('.ryhmat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Ryhmät"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});

});
</script>
   <div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Laskutuksen oletustiedot'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_laskutus_kanava'); ?>
		<?php 
        	$l = array(
			'posti' => 'Posti',
			'verkkolasku' => 'Verkkolasku',
			'sahkoposti' => 'Sähköposti'
		);
		echo $form->dropDownList($model,'asiakas_laskutus_kanava', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'asiakas_laskutus_kanava'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_kirjeenluokka'); ?>
		<?php
		$list = array(	'1'=>Yii::t('main','Luokka 1'),
				'2'=>Yii::t('main','Luokka 2')
				);
        	echo $form->dropDownList($model, 'asiakas_kirjeenluokka', $list,
		array('empty'=>'Valitse','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'asiakas_kirjeenluokka'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_viivastyskorko'); ?>
		<?php echo $form->numberField($model,'asiakas_viivastyskorko',array('size'=>60,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakas_viivastyskorko'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_maksuehto'); ?>
		<?php echo $form->numberField($model,'asiakas_maksuehto',array('size'=>60,'maxlength'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'asiakas_maksuehto'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_alv'); ?>
		<?php
        	$l = array(0=>0,10=>10,14=>14,24=>24);

        	echo $form->dropDownList($model, 'asiakas_alv', $l,
		array('empty'=>'Valitse','class'=>'form-control'
		));
        	?>
		<?php echo $form->error($model,'asiakas_alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_hinta_tyyppi'); ?>
		<?php
		$list = array(1=>'tunti',2=>'kk',3=>'kpl');
        	echo $form->dropDownList($model, 'asiakas_hinta_tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'asiakas_hinta_tyyppi'); ?>
	</div>
   </div>
   <div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Muut asiakkaan asetukset'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_pakkoliset'); ?>
		<?php
		$as = new Asiakkaat;
		$list = array();
		foreach($as->attributes as $k=>$v){
			$list[$k] = $as->getAttributeLabel($k);
		}
		$selected   = array();
		if( is_array(json_decode($model->asiakas_pakkoliset, true)) ){
			foreach(json_decode($model->asiakas_pakkoliset, true) as $item)
			{
				$selected[$item] = array('selected' => 'selected');
			}
		}
		unset($list['id'], $list['time'], $list['yrityksen_nimi'], $list['y_tunnus'], $list["no_email"],
				$list['aktiivinen'], $list['vinkki_tunnit'], $list['vinkki_prosentti'], $list['netvisorkey'], 
				$list['k_osoite'], $list['k_postinumero'], $list['k_kaupunki'], $list['onlinevarauksen_asiakas'], 
				$list['asiakastila'], $list['vinkki_id'], $list['alennuskoodit'], $list['app_kayttoehdot'], 
				$list['gcm_reg_id'], $list['lopetuksen_pvm'], $list['lopetuksen_syy'], $list['netvisor_dimension_item']
			);
        	echo $form->dropDownList($model, 'asiakas_pakkoliset', $list,
		array('empty'=>'Valitse kentät','class'=>'form-control selectpicker', 'multiple' => 'true', 'options' => $selected));	
        	?>
		<?php echo $form->error($model,'asiakas_pakkoliset'); ?>
	</div>
   </div>
  </div>
<!-- Asiakas -->

<!-- Työvuorot -->
<?php if(in_array('2',$tas)) : ?>
<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#tyovuoroAsetukset"><h3><?php echo Yii::t('main','Työvuorot'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="tyovuoroAsetukset">
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Työvuorot asetukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tuote_tyovuorossa'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'tuote_tyovuorossa', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'tuote_tyovuorossa'); ?>
	</div>

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

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lasketaanko_lounastauko'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'lasketaanko_lounastauko', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>
		<?php echo $form->error($model,'lasketaanko_lounastauko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyovuoro_tietoja_mobiilisovellukseen'); ?>
		<?php echo $form->textarea($model,'tyovuoro_tietoja_mobiilisovellukseen',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control', 'placeholder' => "Esim. Avainten tiedot tai kohteesa olevat rajoitukset.")); ?>
		<?php echo $form->error($model,'tyovuoro_tietoja_mobiilisovellukseen'); ?>
	</div>

   </div>

   <div class="col-sm-4">
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


   <div class="col-sm-4">
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

<?php /*
   <div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Apuaika'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'apuaika_meneeko_laskutukseen'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'apuaika_meneeko_laskutukseen', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'apuaika_meneeko_laskutukseen'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'apuaika_palkkalaji'); ?>
		<?php echo $form->textField($model,'apuaika_palkkalaji',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'apuaika_palkkalaji'); ?>
	</div>

   </div>
*/ ?>

  </div>
<?php endif; ?>
<!-- Työvuorot -->

<!-- Sähköposti-ilmoitukset -->
<?php if (!empty(Yii::app()->user->kp)): ?>

<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#sahkoposti-ilmoitukset">
  <h3><?php echo Yii::t('main', 'Sähköposti-ilmoitukset'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3>
</div>
<div class="row form collapse" id="sahkoposti-ilmoitukset">
  <div class="col-sm-6">
    <legend><h2><?php echo Yii::t('main', 'Omasiistijäjärjestelmä'); ?></h2></legend>

    <!-- Käyttööonotto (ei vielä voimassa) -->
    <div class="section fill mb10">
      <?php
      echo $form->labelEx($model, 'omasiistijat_enabled');
      echo $form->dropDownList($model, 'omasiistijat_enabled', [1 => 'Käytössä', 0 => 'Ei käytössä'], ['class' => 'form-control']);
      echo $form->error($model, 'omasiistijat_enabled');
      ?>
    </div>

    <!-- Sähköposti-ilmoituksen teksti -->
    <div class="section fill mb10">
      <?php
      /** @var CActiveForm $form */
      echo $form->labelEx($model, 'omasiistijat_email_subject');
      echo $form->textField($model, 'omasiistijat_email_subject', ['maxlength' => 120, 'class' => 'form-control']);
      echo $form->error($model, 'omasiistijat_email_subject');

      echo $form->labelEx($model, 'omasiistijat_email_body');
      echo $form->textArea($model, 'omasiistijat_email_body', ['rows' => 10, 'maxlength' => 8000, 'class' => 'form-control']);
      echo $form->error($model, 'omasiistijat_email_body');
      ?>
      <p><i>Sähköpostiteksti tulee olla HTML -muodossa. Käytä &lt;br&gt; rivien lopussa rivivaihtona. Normaalit rivivaihdot tekstissä eivät vaikuta lopulliseen sähköpostiin.</i></p>
    </div>
  </div>

  <div class="col-sm-6">
    <legend><h2><?php echo Yii::t('main','Aloitusaikailmoitukset'); ?></h2></legend>

    <div class="section fill mb10">
      <?php
      echo $form->labelEx($model, 'aloitusajat_enabled');
      echo $form->dropDownList($model, 'aloitusajat_enabled', [1 => 'Käytössä', 0 => 'Ei käytössä'], ['class' => 'form-control']);
      echo $form->error($model, 'aloitusajat_enabled');
      ?>
    </div>
    <div class="section fill mb10">
      <?php
      echo $form->labelEx($model, 'aloitusajat_email_subject');
      echo $form->textField($model, 'aloitusajat_email_subject', ['maxlength' => 120, 'class' => 'form-control']);
      echo $form->error($model, 'aloitusajat_email_subject');
      ?>
    </div>
    <div class="section fill mb10">
      <?php
      echo $form->labelEx($model, 'aloitusajat_email_body');
      echo $form->textArea($model, 'aloitusajat_email_body', ['rows' => 10, 'maxlength' => 8000, 'class' => 'form-control']);
      echo $form->error($model, 'aloitusajat_email_body');
      ?>
      <p><i>Sähköpostiteksti tulee olla HTML -muodossa. Käytä &lt;br&gt; rivien lopussa rivivaihtona. Normaalit rivivaihdot tekstissä eivät vaikuta lopulliseen sähköpostiin.
        Muuttujat: <em>%osoite%</em>, %pvm%, %aloitus%, %lopetus% (pvm tulostuu muodossa "31.01.2020" ja kellonajat "13:00")</i></p>
    </div>
  </div>
</div>

<?php endif; ?>
<!-- /// Omasiistijäjärjestelmä -->

<?php if(in_array('3',$tas)) : ?>
<br>

<div id="laskutus" class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#laskutuksenAsetukset"><h3><?php echo Yii::t('main','Laskutuksen asetukset'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>
		<!-- Open this section when authenticating on Procountor. -->
		<div class="row form collapse<?php if (isset($procountor_auth_success) && !empty($procountor_auth_message ?? '')) echo " in" ?>" id="laskutuksenAsetukset">
    <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Laskutuksen asetukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'rivien_teko'); ?>
		<?php 
        	$tal = array(
			0=>'Rivi per kohde',
			1=>'Rivi per Pvm/Kohde'
		);
		echo $form->dropDownList($model,'rivien_teko', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'rivien_teko'); ?>
	</div>

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
			$tal = array(1=>'POSTITA',2=>'Ropo24',3=>'MANUAL',4=>'NETVISOR',5=>'PROCOUNTOR');
		echo $form->dropDownList($model,'palvelu_tyyppi', $tal, 
		array('empty'=>'Valitse palvelu','class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'palvelu_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lasku_laskunumero'); ?>
		<?php 
        	$tal = array(1=>'Itse',2=>'Automaattisesti');
		echo $form->dropDownList($model,'lasku_laskunumero', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'lasku_laskunumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lasku_asiakasnumero'); ?>
		<?php 
        	$tal = array(0=>'Automaattisesti',1=>'Itse');
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
	<!-- PROCOUNTOR -->
	<br><h2><?php echo Yii::t('main','PROCOUNTOR tunnukset'); ?></h2><br>
	<div class="section fill mb5">
		<?php $redirect = Yii::createComponent('Procountor')->getRedirectUri(); ?>
		<p><?php echo CHtml::link('Kirjaudu Procountoriin', "https://api.procountor.com/login?response_type=code&client_id=etuntiClient&redirect_uri=$redirect&state=" .strtolower(Yii::app()->user->domain), ['class' => 'btn btn-lg btn-primary myBgColors']); ?></p>
		<?php 
			$is_local = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['::1', '127.0.0.1']);
			/* Testi tunnukset */ 
			//	etunti.test
			//	Elias2011!
			if($is_local)
			echo '<p>'. CHtml::link('Kirjaudu Procountoriin TEST', "https://api-test.procountor.com/login?response_type=code&client_id=etuntiTestClient&redirect_uri=$redirect&state=" .strtolower(Yii::app()->user->domain), ['class' => 'btn btn-lg btn-primary myBgColors']).'</p>';
		?>
		<!-- https://api-test.procountor.com/login?response_type=code&client_id=etuntiTestClient&redirect_uri=$redirect&state=" . strtolower(Yii::app()->user->domain) -->
	</div>
	<?php
		// Auth success message
		if (isset($procountor_auth_success) && !empty($procountor_auth_message ?? ''))
			echo "<h3 class='text-" . ($procountor_auth_success ? 'success' : 'danger') . "'>$procountor_auth_message</h3>";

		// Auth info
		if (!empty($model->procountor_access_token) && !empty($model->procountor_refresh_token)) {
			if (($model->procountor_invalid ?? 0) == 1) {
				echo "<p>Procountor kirjautumisesi on vanhentunut. Kirjauduthan uudelleen jatkaaksesi Procountor ominaisuuksien käyttämistä.</p>";
			} else {
				echo "<p>Procountor kirjautumisesi on voimassa.</p>";
				echo "<p>Pääsyavain:<br><small style='word-wrap: break-word;'>{$model->procountor_access_token}</small></p>";
				echo "<p>Päivitysavain:<br><small style='word-wrap: break-word;'>{$model->procountor_refresh_token}</small></p>";
			}
		}
	?>
  </div>
	<div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Ropo24 tunnukset'); ?></h2></legend>
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
    <?php
    echo $form->labelEx($model,'onlinevaraus_palvelu');
    echo $form->dropDownList($model,
      'onlinevaraus_palvelu',
      array(0=>'CHECKOUT.FI',1=>'BAMBORA PAYFORM'), 
      array('empty'=>'Valitse palvelu','class'=>'form-control','id'=>'osoite')
    );
    echo $form->error($model,'onlinevaraus_palvelu');
    ?>
	</div>

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

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'gtm'); ?>
		<?php echo $form->textField($model,'gtm',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'gtm'); ?>
  </div>
  

  <!-- // Bambora -->
  <br>
  <legend><h2><?php echo Yii::t('main','Bambora tunnukset'); ?></h2></legend>
  <div class="section fill mb5">
    <?php echo $form->labelEx($model,'bambora_private_key'); ?>
    <?php echo $form->textField($model,'bambora_private_key',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>
    <?php echo $form->error($model,'bambora_private_key'); ?>
  </div>
  <div class="section fill mb5">
    <?php echo $form->labelEx($model,'bambora_api_key'); ?>
    <?php echo $form->textField($model,'bambora_api_key',array('size'=>20,'maxlength'=>255,'class'=>'form-control')); ?>
    <?php echo $form->error($model,'bambora_api_key'); ?>
  </div>
  <!-- Bambora // -->

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
		<?php echo $form->labelEx($model,'onlinevaraus_aikavali'); ?>


		<?php 
        	$l = array();
		for ($i = 1; $i <= 4; $i++) {
        		$l[$i] = $i;
		}
		echo $form->dropDownList($model,'onlinevaraus_aikavali', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'onlinevaraus_aikavali'); ?>
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

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_viikonlopput'); ?>
		<?php 
        	$tal = array(
			1=>'Ei',
			0=>'Kyllä'
		);
		echo $form->dropDownList($model,'onlinevaraus_viikonlopput', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'onlinevaraus_viikonlopput'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_autoremove'); ?>


		<?php 
        	$l = array(10=>10,20=>20,30=>30,40=>40,50=>50,60=>60,70=>70,80=>80,90=>90);
		echo $form->dropDownList($model,'onlinevaraus_autoremove', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'onlinevaraus_autoremove'); ?>
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
			3=>'Tästä päivä alkaen +14pv',
			4=>'Tästä päivä alkaen +30pv'
		);
		echo $form->dropDownList($model,'sovellus_tyovuorot', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'sovellus_tyovuorot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_naytta_sairauslomat'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_naytta_sairauslomat', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_naytta_sairauslomat'); ?>
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

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_naytetaanko_kohteen_yhteyshenkilo'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_naytetaanko_kohteen_yhteyshenkilo', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_naytetaanko_kohteen_yhteyshenkilo'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_naytta_avain'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_naytta_avain', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_naytta_avain'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_naytta_osoitekenta'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_naytta_osoitekenta', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_naytta_osoitekenta'); ?>
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
		<?php echo $form->labelEx($model,'app_matka_osoite'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_matka_osoite', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_matka_osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_lounastauko_osoite'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_lounastauko_osoite', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_lounastauko_osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ilmoitus_uudesta_kuvasta_saajat'); ?>
		<?php echo $form->textarea($model,'ilmoitus_uudesta_kuvasta_saajat',array('rows'=>8,'maxlength'=>3000,'class'=>'form-control', 'placeholder' => "sähköposti1@testi.fi\nsähköposti2@testi.fi")); ?>
		<?php echo $form->error($model,'ilmoitus_uudesta_kuvasta_saajat'); ?>
	</div>

   </div>

   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','Sovelluksen asetukset'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_hyvaksytyt_tyot_vkomaara'); ?>
		<?php echo $form->numberField($model,'app_hyvaksytyt_tyot_vkomaara',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'app_hyvaksytyt_tyot_vkomaara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_naytetaanko_hyvaksyttyt_tunnit'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_naytetaanko_hyvaksyttyt_tunnit', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_naytetaanko_hyvaksyttyt_tunnit'); ?>
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
		<?php echo $form->labelEx($model,'netvisor_accountingaccountsuggestion'); ?>
		<?php echo $form->textField($model,'netvisor_accountingaccountsuggestion',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_accountingaccountsuggestion'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_mita_onkayttossa'); ?>
		<?php 
        	$tal = array(
			0=>'Laskutus',
			1=>'Tunnit',
			2=>'Kaikki',
		);
		echo $form->dropDownList($model,'netvisor_mita_onkayttossa', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'netvisor_mita_onkayttossa'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_host'); ?>
		<?php echo $form->textField($model,'netvisor_host',array('maxlength'=>500,'class'=>'form-control', 'placeholder'=>'integration.netvisor.fi')); ?>
		<?php echo $form->error($model,'netvisor_host'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_organisation_identifier'); ?>
		<?php echo $form->textField($model,'netvisor_organisation_identifier',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_organisation_identifier'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_lahetetaanko_tyontekija'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'netvisor_lahetetaanko_tyontekija', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'netvisor_lahetetaanko_tyontekija'); ?>
	</div>

   </div>
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','ID'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_customer_id'); ?>
		<?php echo $form->textField($model,'netvisor_customer_id',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_customer_id'); ?>
	</div>
	<?php /*
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_lahetyksen_muoto'); ?>
		<?php 
        	$l = array(
			0 => Yii::t('main', 'PVM summ tunnit'),
			1 => Yii::t('main', 'Joka kirjaus erikseen')
		);
		echo $form->dropDownList($model,'netvisor_lahetyksen_muoto', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'netvisor_lahetyksen_muoto'); ?>
	</div>
	*/ ?>
	
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_mita_lahetetaan'); ?>
		<?php 
        	$l = array(
			//0=>Yii::t('main', 'Ei mitään'),
			'tyotunnit'=>Yii::t('main', 'Työtunnit'),
			'tyoilta'=>Yii::t('main', 'Työtunnit ilta'),
			'matka'=>Yii::t('main', 'Matkat'),
			//'lounaat'=>Yii::t('main', 'Lounastauot'),
			'tyoyo'=>Yii::t('main', 'Työtunnit yö'),
			'tyosu'=>Yii::t('main', 'Työtunnit sunnuntai'),
			'sl'=>Yii::t('main', 'Sairausajan palkka'),
			'spl'=>Yii::t('main', 'Sairausajan palkaton'),
			'ls'=>Yii::t('main', 'Lapsen sairaus'),
			'py'=>Yii::t('main', 'Arkipyhät'),
			'el'=>Yii::t('main', 'Erikoislauantait'),
			'vl'=>Yii::t('main', 'Vuosilomat'),
			'ap'=>Yii::t('main', 'Arkipyhä'),
			'pv'=>Yii::t('main', 'Palkaton vapaa'),
		);

		$selected   = array();
		if( is_array(json_decode($model->netvisor_mita_lahetetaan, true)) ){
			foreach(json_decode($model->netvisor_mita_lahetetaan, true) as $item)
			{
				$selected[$item] = array('selected' => 'selected');
			}
		}
		$htmlOptions = array('class'=>'form-control selectpicker', 'multiple' => 'true', 'options' => $selected);
		echo $form->dropDownList( $model,'netvisor_mita_lahetetaan', $l, $htmlOptions ); ?>
		<?php echo $form->error($model,'netvisor_mita_lahetetaan'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_acceptancestatus'); ?>
		<?php 
        	$l = array(
			'accepted'=>Yii::t('main', 'Hyväksytty'),
			'confirmed'=>Yii::t('main', 'Kirjattu')
		);
		echo $form->dropDownList($model,'netvisor_acceptancestatus', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'netvisor_acceptancestatus'); ?>
	</div>

   </div>
   <div class="col-sm-4">
    <legend><h2><?php echo Yii::t('main','KEY'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_userkey'); ?>
		<?php echo $form->textField($model,'netvisor_userkey',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_userkey'); ?>
	</div>
	<?php if(Yii::app()->user->username == 'etunti' || Yii::app()->user->username == 'roman'): ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_partner_id'); ?>
		<?php echo $form->textField($model,'netvisor_partner_id',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_partner_id'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_partnerkey'); ?>
		<?php echo $form->textField($model,'netvisor_partnerkey',array('maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'netvisor_partnerkey'); ?>
	</div>
	<?php endif; ?>
   </div>
  </div>


<?php if(in_array('5',$tas)) : ?>
<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#eDico"><h3><?php echo Yii::t('main','eDico'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="eDico">

   <div class="col-sm-4">
	<legend><h2><?php echo Yii::t('main','Peruutusehdot'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'peruutusehdot'); ?>
		<?php echo $form->textarea($model,'peruutusehdot',array('rows'=>4,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'peruutusehdot'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'peruutta_paiva_ennen'); ?>
		<?php echo $form->numberField($model,'peruutta_paiva_ennen',array('maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'peruutta_paiva_ennen'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tietosuoja_vinkki_sailyttaminen'); ?>
		<?php echo $form->numberField($model,'tietosuoja_vinkki_sailyttaminen',array('maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietosuoja_vinkki_sailyttaminen'); ?>
	</div>
   </div>

   <div class="col-sm-4">
	<legend><h2><?php echo Yii::t('main','Alennuskoodit'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alennus_max_euro'); ?>
		<?php echo $form->numberField($model,'alennus_max_euro',array('maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'alennus_max_euro'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alennus_max_prosentti'); ?>
		<?php echo $form->numberField($model,'alennus_max_prosentti',array('maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'alennus_max_prosentti'); ?>
	</div>
	<legend><h2><?php echo Yii::t('main','Toteutuneet tunnit'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'edico_tehdyt_tyot'); ?>
		<?php 
        	$l = array(
			''=>'Ei mitään',
			'kirjattu'=>'Kirjattu',
			'hyvaksytty'=>'Hyväksytty',
			'laskutettu'=>'Laskutettu',
		);
		echo $form->dropDownList($model,'edico_tehdyt_tyot', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'edico_tehdyt_tyot'); ?>
	</div>
   </div>
   <div class="col-sm-4">
	<legend><h2><?php echo Yii::t('main','Palautteet'); ?></h2></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palautteet_autovastaus_hyva'); ?>
		<?php echo $form->textarea($model,'palautteet_autovastaus_hyva',array('rows'=>4,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'palautteet_autovastaus_hyva'); ?>
	</div>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'palautteet_autovastaus_huono'); ?>
		<?php echo $form->textarea($model,'palautteet_autovastaus_huono',array('rows'=>4,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'palautteet_autovastaus_huono'); ?>
	</div>
   </div>

  </div>
<?php endif; ?>

<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#tyoryhmatAsetukset"><h3><?php echo Yii::t('main','Työryhmät'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>
  <div class="row form collapse" id="tyoryhmatAsetukset">
   <div class="col-sm-3">
    <legend><h2><?php echo Yii::t('main','Työryhmät'); ?></h2></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoryhmat'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'tyoryhmat', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'tyoryhmat'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoryhmat_kohde'); ?>
		<?php 
        	$l = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'tyoryhmat_kohde', $l, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'tyoryhmat_kohde'); ?>
	</div>

   </div>
  </div>


<?php if(Yii::app()->user->domain === "kotipuhtaaksi") : ?>
<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#referenceperiod">
	<h3>
		<?= Yii::t("main", "Tasoittumisjakso");?>&nbsp; 
		<i class="fa fa-caret-square-o-down" aria-hidden="true"></i>
	</h3>
</div>
<div class="row form collapse" id="referenceperiod">
	<div class="col-sm-12">
		<legend>
			<h4>
				<?= Yii::t("main", "Tasoittumisjakso"); ?>
			</h4>
		</legend>
	</div>
	<div class="col-sm-6">
		<div class="section fill mb5">
			<?php echo $form->labelEx($model, "reference_period_start_email_subject"); ?>
			<?php echo $form->textField($model,'reference_period_start_email_subject',array('maxlength'=>255,'class'=>'form-control')); ?>
			<?php echo $form->error($model, "reference_period_start_email_subject"); ?>
		</div>
		<div class="section fill mb5">
			<?php echo $form->labelEx($model, "reference_period_start_email_body"); ?>
			<?php echo $form->textarea($model,'reference_period_start_email_body',array('rows'=>10,'class'=>'form-control')); ?>
			<p><i>Sähköpostiteksti tulee olla HTML -muodossa. Käytä &lt;br&gt; rivien lopussa rivivaihtona. Normaalit rivivaihdot tekstissä eivät vaikuta lopulliseen sähköpostiin.
			Viestin perään luodaan automaattisesti taulukko, jossa näkyy tasoittumisjakson pituuden viikkonumerot, ja suunnitellut tunnit viikoille.</i></p>
			<?php echo $form->error($model, "reference_period_start_email_body"); ?>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="section fill mb5">
			<?php echo $form->labelEx($model, "reference_period_end_email_subject"); ?>
			<?php echo $form->textField($model,'reference_period_end_email_subject',array('maxlength'=>255,'class'=>'form-control')); ?>
			<?php echo $form->error($model, "reference_period_end_email_subject"); ?>
		</div>
		<div class="section fill mb5">
			<?php echo $form->labelEx($model, "reference_period_end_email_body"); ?>
			<?php echo $form->textarea($model,'reference_period_end_email_body',array('rows'=>10,'class'=>'form-control')); ?>
			<p><i>Sähköpostiteksti tulee olla HTML -muodossa. Käytä &lt;br&gt; rivien lopussa rivivaihtona. Normaalit rivivaihdot tekstissä eivät vaikuta lopulliseen sähköpostiin.
        	Viestin perään luodaan automaattisesti taulukko, jossa näkyy tasoittumisjakson pituuden viikkonumerot, ja toteutuneet tunnit viikoille.</i></p>
			<?php echo $form->error($model, "reference_period_end_email_body"); ?>
		</div>
	</div>
	<?php /* dummy col to fix positions on error message */ ?>
	<div class="col-sm-12"></div>
	<div class="col-sm-3">
		<div class="section fill mb5">
			<?php echo $form->labelEx($model, "reference_period_enabled"); ?>
			<?php 
				$list = [0 => "Ei käytössä", 1 => "Käytössä"];
				echo $form->dropDownList($model, "reference_period_enabled", $list, ["class" => "form-control"]);
			?>
			<?php echo $form->error($model, "reference_period_enabled"); ?>
		</div>
		<div class="section fill mb5">
			<?php echo $form->labelEx($model, "reference_period_start_date"); ?>
			<?php echo $form->textField($model, "reference_period_start_date", ["class" => "form-control datepickerFI"]); ?>
			<?php if($model->reference_period_length && $model->reference_period_start_date) : ?>
				<?php 
					$freq = $model->reference_period_length;
					$date = $model->reference_period_start_date;
					$format = "d.m.Y";
					$formatted = DateTime::createFromFormat($format, $date);	
				?>
				<p>
					<i>Päivämäärä päivitetään automaattiesti kun jakso loppuu, seuraava päivämäärä: <?= (clone $formatted)->modify("+8 weeks")->format($format) ?>.</i>
					<i>Ilmoitus jakson alkamisesta lähetetään sähköpostitse työntekijöille 2vko ennen jakson alkua, seuraava lähetyspäivämäärä: <?= (clone $formatted)->modify("-2 weeks")->format($format); ?></i>
				</p>
				<?php echo $form->error($model, "reference_period_start_date"); ?>
			<?php endif; ?>
		</div>
		<?php 
			if(isset($model->reference_period_start_date)) {
				$start_date = $model->reference_period_start_date;
				$format = "d.m.Y";
				$date = DateTime::createFromFormat($format, $start_date);
	
				$dateTwoWeeks = $date->modify("-2 weeks");
			}

			if(isset($dateTwoWeeks) && $dateTwoWeeks !== false && new DateTime("now") >= $dateTwoWeeks ) : ?>
			<p>
				<i>
					<?php 
						$sent_flag = $model->reference_period_emails_sent;
						if($sent_flag == 1) {
							echo "Sähköpostit lähetetty";
						} else {
							echo "Sähköpostit eivät ole vielä lähetetty";
						}
					?>
				</i>
			</p>
			
		<?php endif; ?>
		<div class="section fill mb5">

		</div>
		<div class="section fill mb5">
			<?php echo $form->labelEx($model, "reference_period_end_send_date"); ?>
			<?php echo $form->textField($model, "reference_period_end_send_date", ["class" => "form-control datepickerFI"]); ?>
			<?php if($model->reference_period_length && $model->reference_period_end_send_date) : ?>
				<p>
					<?php 
						$freq = $model->reference_period_length;
						$date = $model->reference_period_end_send_date;
						$format = "d.m.Y";
						$formatted = DateTime::createFromFormat($format, $date);
					?>
					<i>Päivämäärä päivitetään automaattiesti kun ilmoitus lähetetään, seuraava päivämäärä: <?= $formatted->modify("+8 weeks")->format($format)?></i>
				</p>
				<?php echo $form->error($model, "reference_period_end_send_date"); ?>
			<?php endif; ?>
		</div>
		<div class="section fill mb5">
			<?php echo $form->labelEx($model, "reference_period_length"); ?>
			<?php echo $form->textField($model, "reference_period_length", ["class" => "form-control"]); ?>
			<?php echo $form->error($model, "reference_period_length"); ?>
		</div>
	
	</div>
</div>


<?php endif; ?>

<br>
<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#autohyvaksynta"><h3><?php echo Yii::t('main','Tuntien hyväksyntä'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>

  <div class="row form collapse" id="autohyvaksynta">
   <div class="col-sm-3">

	<legend><h4><?=Yii::t('main', 'Hyväksy tunnit automaattisesti.')?></h4></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_auto_hyvaksyminen'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_auto_hyvaksyminen', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_auto_hyvaksyminen'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_hyvaksynnan_peruste'); ?>
		<?php 
        	$tal = array(
			0 => 'Toteutuneen ajan mukaan',
			1 => 'Työvuoron aloitus ja lopetus mukaan',
			2 => 'Hyväksy kaikki edellisen päivän tunnit automaattisesti'
		);
		echo $form->dropDownList($model,'app_hyvaksynnan_peruste', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_hyvaksynnan_peruste'); ?>
	</div>

	<div class="section fill mb5" id="seuraavapaiva" style="display:none">
		<label><i data-toggle="tooltip" class="text-danger fa fa-2x fa-info" title="Aseta kellonaika,mihin aikaan automaattinen hyväksy toteutetaan seuraavalla päivällä"></i> Hyväksyminen aika </label>
		<?php echo $form->textField($model,'auto_hyvaksynta_klo',array('maxlength'=>10,'class'=>'auto_hyvaksynta_klo form-control')); ?>
		<?php echo $form->error($model,'auto_hyvaksynta_klo'); ?>
	</div>

	<!-- Procountor scroll to laskutus -section on authentication event. -->
	<?php if (isset($procountor_auth_success) && !empty($procountor_auth_message ?? '')): ?>
	<script type="text/javascript">
	$(document).ready(function () {
		$('html, body').animate({
			scrollTop: $("#laskutus").offset().top
		}, 20);
	});
	</script>

	<?php endif; ?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>
<script type="text/javascript">
$(document).ready(function(){

 $("#Asetukset_app_hyvaksynnan_peruste").change(function() {
	hyvperuste();
 });
	hyvperuste();
 function hyvperuste(){
    if($("#Asetukset_app_hyvaksynnan_peruste option:selected").val() == 2){
	$("#seuraavapaiva").show('slow');
    } else {
	$("#seuraavapaiva").hide('slow');
    }
 }

 $('.auto_hyvaksynta_klo').mask('00:00',{
        placeholder: "__:__"
 });

});
</script>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_auto_hyvaksyminen_aikavali'); ?>
		<?php 
        	$tal = array(
			5=>'5',
			10=>'10',
			15=>'15',
			20=>'20',
			25=>'25',
			30=>'30',
		);
		echo $form->dropDownList($model,'app_auto_hyvaksyminen_aikavali', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_auto_hyvaksyminen_aikavali'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'app_auto_hyvaksyminen_tvmukaan'); ?>
		<?php 
        	$tal = array(
			0=>'Ei',
			1=>'Kyllä'
		);
		echo $form->dropDownList($model,'app_auto_hyvaksyminen_tvmukaan', $tal, 
		array('class'=>'form-control')) ?>
		<?php echo $form->error($model,'app_auto_hyvaksyminen_tvmukaan'); ?>
	</div>

   </div>
  </div>

<br>
<br>
	<p><div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-lg btn-primary myBgColors')); ?>
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
