<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */

$asiakkaat_model = new Asiakkaat;
$asetukset = Asetukset::model()->findbypk(1);
$tietoja = $asetukset->tyovuoro_tietoja_mobiilisovellukseen;
if(empty($model->tietoja))
	$model->tietoja = $tietoja;
?>

<style>
.ashidd{
	display:none;
}
</style>

<div class="sectionfill mb5">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
	'enableAjaxValidation'=>false,

)); ?>


	<?php echo $form->errorSummary($model); ?>

<div class="row">
  <div class="col-sm-3">
	<br>
	<span class="btn btn-primary myBgColors" data-toggle="collapse" data-target="#uusi_asiakas">
	  <?php echo Yii::t('main', 'Uusi asiakas'); ?> <i class="caret"></i>
	</span>
  </div>
  <div class="col-sm-3">
	<label><?php echo Yii::t('main', 'Asiakas tai kohteen yhteyshenkilö'); ?></label><br>
	<input type="text" name="oleva_asiakas" id="asiakas" class="form-control" AUTOCOMPLETE="off">
	<div id="asiakasAutocompleteResult"></div>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
       		$criteria = new CDbCriteria();
	        $criteria->order = " osoite ";

		// <-- TyoryhmatHelper
		$site = Yii::app()->createController('Site');
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->condition = " tyoryhma IN ($ids) ";
		}
		//     TyoryhmatHelper -->

        		$list = CHtml::listData(Kohteet::model()->findAll($criteria), 'id', 'osoite');
        		echo $form->dropDownList($model, 'kohde', $list,array('empty'=>'Valitse','class'=>'form-control kohde'));
        	?>
  </div>
</div>
 	
<hr>

<div class="collapse" id="uusi_asiakas">
<div class="row">
  <div class="col-sm-3">
    <div class="sectionfill mb5">
		<label>Asiakkaan tyyppi</label>
		<select name="Asiakkaat[tyyppi]" id="Asiakkaat_tyyppi" class="form-control tyyppi">
		<option value="henkilo"><?php echo Yii::t('main', 'Henkilö'); ?></option>
		<option value="yritys"><?php echo Yii::t('main', 'Yritys'); ?></option>
		</select>
    </div>
    <div class="section">
		<label><?php echo Yii::t('main', 'Henkilötunnus'); ?></label>
		<input type="text" name="Asiakkaat[henkilotunnus]" id="henkilotunnus" class="form-control">
		<div id="yrityksen_nimi_error"></div>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Onko kokeilusiivous?'); ?> </label><br>
		<input type="radio" name="onkoKokeilusiivous" class="onkoKokeilusiivous" value="Kyllä"> <?php echo Yii::t('main', 'Kyllä'); ?><br>
		<input type="radio" name="onkoKokeilusiivous" class="onkoKokeilusiivous" checked value="Ei"> <?php echo Yii::t('main', 'Ei'); ?>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Onko lahjakortti?'); ?> </label><br>
		<input type="radio" name="onkoLahjakortti" class="onkoLahjakortti" value="kylla"> <?php echo Yii::t('main', 'Kyllä'); ?><br>
		<input type="radio" name="onkoLahjakortti" class="onkoLahjakortti" checked value="Ei"> <?php echo Yii::t('main', 'Ei'); ?>
    </div>
    <div class="section collapse LahjakortinNumero">
		<label><?php echo Yii::t('main', 'Lahjakortin numero'); ?> </label>
		<input type="text" name="LahjakortinNumero" id="LahjakortinNumero" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Työryhmä'); ?> </label>
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
		<?php echo $form->dropDownList($asiakkaat_model, 'tyoryhma', CHtml::listData($listData, 'id', 'value'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Asiakasryhmä'); ?> </label>

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
			echo '<select name="Asiakkaat[ryhma][]" class="ryhmat form-control" multiple title="Valitse">';
			foreach($l as $data)
			{
		    		echo '<option value="'.$data->id.'">'.$data->value.'</option>';
			}
			echo '</select>';
		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="asiakas_ryhma_real"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>
    </div>
  </div>

<script type="text/javascript">
$(document).ready(function(){

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

  // <-- onkoAsOsoiteSamaKunKohde
  $(".onkoLahjakortti").change(function() {
    var value = $(this).val();
    if(value == 'kylla')
    {
	$('.LahjakortinNumero').addClass('in');
    } else {
	$('#LahjakortinNumero').val('');
	$('.LahjakortinNumero').removeClass('in');
    }
  });

});
</script>

  <div class="col-sm-3">
    <div class="section yritys ashidd">
		<label><?php echo Yii::t('main', 'Yrityksen nimi'); ?></label>
		<input type="text" name="Asiakkaat[yrityksen_nimi]" id="yrityksen_nimi" class="form-control">
		<div id="yrityksen_nimi_error"></div>
    </div>
    <div class="section y_tunnus ashidd">
		<label><?php echo Yii::t('main', 'Y-tunnus'); ?></label>
		<input type="text" name="Asiakkaat[y_tunnus]" class="form-control">
    </div>
    <div class="section">
		<label><?php echo Yii::t('main', 'Etunimi'); ?> <span class="required">*</span></label>
		<input type="text" name="Asiakkaat[etunimi]" id="Asiakas_etunimi" class="form-control" required>
		<div id="etunimi_error"></div>
    </div>
    <div class="section">
		<label><?php echo Yii::t('main', 'Sukunimi'); ?> <span class="required">*</span></label>
		<input type="text" name="Asiakkaat[sukunimi]" id="Asiakas_sukunimi" class="form-control" required>
		<div id="sukunimi_error"></div>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Asiakkaan osoite'); ?> <span class="required">*</span></label>
		<input type="text" name="Asiakkaat[osoite]" id="Asiakas_osoite" class="form-control" required>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Asiakkaan postinumero'); ?> <span class="required">*</span></label>
		<input type="number" name="Asiakkaat[postinumero]" id="Asiakas_postinumero" class="form-control" required>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Asiakkaan postitoimipaikka'); ?> <span class="required">*</span></label>
		<input type="text" name="Asiakkaat[kaupunki]" id="Asiakas_kaupunki" class="form-control" required>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Asiakkaan puhelin'); ?></label>
		<input type="text" name="Asiakkaat[puhelin]" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Asiakkaan sähköposti'); ?></label>
		<input type="text" name="Asiakkaat[sahkoposti]" id="Asiakas_sahkoposti" class="form-control">
		<div id="sahkoposti_error"></div>
    </div>
  </div>

<script type="text/javascript">
$(document).ready(function(){

  $("#yrityksen_nimi").blur(function() {
    var value = $(this).val();
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/is_asiakas',
		  data:{ yrityksen_nimi : value },
		  type:'POST',
		  success:function(data){
			data = JSON.parse(data);
			//console.log(data);
			if( data !== '')
				$('#yrityksen_nimi_error').addClass('errorMessage').show().html(data);
			else
				$('#yrityksen_nimi_error').removeClass('errorMessage').hide().html('');
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  });

  $("#Asiakas_sahkoposti").blur(function() {
    var value = $(this).val();
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/is_asiakas',
		  data:{ sahkoposti : value },
		  type:'POST',
		  success:function(data){
			data = JSON.parse(data);
			//console.log(data);
			if( data !== ''){
				$('#sahkoposti_error').addClass('errorMessage').show().html(data);
				$("#Asiakas_sahkoposti").focus();
				$("#submitButton").addClass('disabled').hide();
				return false;
			} else {
				$('#sahkoposti_error').removeClass('errorMessage').hide().html('');
				$("#submitButton").removeClass('disabled').show();
				return false;
			}
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  });

  $("#Asiakas_yhteyshenkilo").blur(function() {
    var value = $(this).val();
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/is_yhteyshenkilo',
		  data:{ yhteyshenkilo : value },
		  type:'POST',
		  success:function(data){
			data = JSON.parse(data);
			//console.log(data);
			if( data !== '')
				$('#yhteyshenkilo_error').addClass('errorMessage').show().html(data);
			else
				$('#yhteyshenkilo_error').removeClass('errorMessage').hide().html('');
	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  });


  // <-- onkoAsOsoiteSamaKunKohde
  $(".onkoAsOsoiteSamaKunKohde").change(function() {
    var value = $(this).val();
    if(value == 'ei')
    {
	$('.kohteenOsoite').addClass('in');
    } else {
	$('#kohteenOsoite').val('');
	$('.kohteenOsoite').removeClass('in');
    }
  });

});
</script>

  <div class="col-sm-3">
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Onko kohteen osoite sama kuin asiakkaan osoite?'); ?> </label><br>
		<input type="radio" name="onkoAsOsoiteSamaKunKohde" class="onkoAsOsoiteSamaKunKohde" checked value="kylla"> <?php echo Yii::t('main', 'Kyllä'); ?><br>
		<input type="radio" name="onkoAsOsoiteSamaKunKohde" class="onkoAsOsoiteSamaKunKohde" value="ei"> <?php echo Yii::t('main', 'Ei'); ?>
    </div>
    <div class="section collapse kohteenOsoite">
		<label><?php echo Yii::t('main', 'Kohteen osoite'); ?> </label>
		<input type="text" name="kohteenOsoite" id="kohteenOsoite" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Oven avaaminen'); ?></label>
		<select name="oven_avaaminen" class="form-control tyyppi">
		<option value=""><?php echo Yii::t('main', 'Valitse'); ?></option>
		<option value="<?php echo Yii::t('main', 'Asiakas on kotona avaamassa oven'); ?>"><?php echo Yii::t('main', 'Asiakas on kotona avaamassa oven'); ?></option>
		<option value="<?php echo Yii::t('main', 'Avain on piilossa'); ?>"><?php echo Yii::t('main', 'Avain on piilossa'); ?></option>
		<option value="<?php echo Yii::t('main', 'Avain toimitetaan toimistolle'); ?>"><?php echo Yii::t('main', 'Avain toimitetaan toimistolle'); ?></option>
		</select>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Mihin avain palautetaan?'); ?> </label>
		<input type="text" name="mihin_avain_palautetaan" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Mihin työntekijä voi pysäköidä auton?'); ?> </label>
		<textarea name="mihin_pysakoida_auto" class="form-control"></textarea>
    </div>
  </div>

<script type="text/javascript">
$(document).ready(function(){

  // <-- onkoAsOsoiteSamaKunKohde
  $(".onkoMaksajanTiedotSama").change(function() {
    var value = $(this).val();
    if(value == 'Ei')
    {
	$('.MaksajanTiedot').addClass('in');
    } else {
	$('#MaksajanTiedot').val('');
	$('.MaksajanTiedot').removeClass('in');
    }
  });

});
</script>

  <div class="col-sm-3">
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Onko laskun maksajan tiedot sama kuin tilaaja?'); ?> </label><br>
		<input type="radio" name="onkoMaksajanTiedotSama" class="onkoMaksajanTiedotSama" checked value="Kyllä"> <?php echo Yii::t('main', 'Kyllä'); ?><br>
		<input type="radio" name="onkoMaksajanTiedotSama" class="onkoMaksajanTiedotSama" value="Ei"> <?php echo Yii::t('main', 'Ei'); ?>
    </div>
    <div class="section collapse MaksajanTiedot">
		<label><?php echo Yii::t('main', 'Anna laskun maksajan tiedot'); ?> </label>
		<input type="text" name="MaksajanTiedot" id="MaksajanTiedot" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Laskutus kanava'); ?> </label>
		<?php
		$list = array(	'posti'=>Yii::t('main','Posti'),
				'verkkolasku'=>Yii::t('main','Verkkolasku'),
				'sahkoposti'=>Yii::t('main','Sähköposti')
				);
        	echo CHtml::dropDownList('Asiakkaat[laskutus_kanava]', 'laskutus_kanava', $list,
		array('class'=>'form-control'));
        	?>
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Yrityksen OVT-tunnus'); ?> </label>
		<input type="text" name="Asiakkaat[ovt_tunnus]" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Verkkolaskuosoite'); ?> </label>
		<input type="text" name="Asiakkaat[verkkolaskuosoite]" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Operaattorin välittäjän tunnus'); ?> </label>
		<input type="text" name="Asiakkaat[valittajan_tunnus]" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Viivästyskorko'); ?> </label>
		<input type="number" name="Asiakkaat[viivastyskorko]" class="form-control">
    </div>
    <div class="sectionfill mb5">
		<label><?php echo Yii::t('main', 'Maksuehto'); ?> </label>
		<input type="number" name="Asiakkaat[maksuehto]" class="form-control">
    </div>
  </div>
</div>
</div><!--olemassa-->



<legend><h2><?php echo Yii::t('main', 'Työvuoro'); ?></h2></legend>
<?php echo $form->hiddenField($model,'uusi_tilaus', array('value' => 1)); ?>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('value'=>date("d.m.Y"),'size'=>20,'maxlength'=>20,'class'=>'form-control datepickerFI'));//,'readonly'=>'yes' ?>
		<?php echo $form->error($model,'pvm'); ?>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'alku'); ?>
		<input type="text" name="Tyovuoroot[alku]" class="form-control laske timeVuorot" id="alku">
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<input type="text" name="Tyovuoroot[loppu]" class="form-control laske timeVuorot" id="loppu">
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pituus'); ?>
		<div id="tvPituus"><?php echo $model->pituus; ?></div>
  </div>

</div>


<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tid'); ?>


				<?php
				$list = array(0=>Yii::t('main', 'VARAUS'));
		   		$site = Yii::app()->createController('Site');
		   		$returnList = $site[0]->tyontekiatArrayList(1);
				array_push($list, $returnList);
				?>

        	<?php echo $form->dropDownList($model, 'tid', $list,array('empty'=>'Valitse','class'=>'form-control'));	?>
  </div>
  <div class="col-sm-3">
		<label><?php echo Yii::t('main', 'Työpari'); ?></label><br>
		<?php 
		$tyopaari = json_decode($model->tyopaari, true);

		$criteria=new CDbCriteria;
		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->
		$criteria->condition =" aktiivinen=1 and id!='".$model->tid."' ";

 		$tt = Tyontekijat::model()->findAll($criteria);
		if(isset($tt[0]))
		{
			echo '<select name="tyopaari[]" id="tyopaari" class="mult" multiple>';
			foreach($tt as $tekija)
			{
			  if(is_array($tyopaari) and in_array($tekija->id,$tyopaari, true))
			    echo '<option value="'.$tekija->id.'" selected>'.$this->etuSukunimi($tekija->id).'</option>';
			  else
			    echo '<option value="'.$tekija->id.'">'.$this->etuSukunimi($tekija->id).'</option>';
			}
			echo '</select>';
		}
		?>


  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
        	$l = $this->tilanteet();
		echo $form->dropDownList($model,'status', $l, 
		array('class'=>'form-control')) ?>

  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tyoajanmerkinta'); ?>
		<?php
        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanmerkinta' ", array('order' => 'select_type'));
		echo '<select name="Tyovuoroot[tyoajanmerkinta]" class="form-control">';

		 if(!empty($model->tyoajanmerkinta)){
		   $expl = explode("/",$model->tyoajanmerkinta);
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   echo '<option value="'.$model->tyoajanmerkinta.'">'.$value.'</option>';
		 }

		 foreach($tal as $v)
		 {
		   $expl = explode("/",$v->value);
		   $color = (isset($expl[1])) ? $expl[1] : '';
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   echo '<option style="color:'.$color.'" value="'.$v->value.'">'.$value.'</option>';
		 }
		echo '</select>';
        	?>

  </div>
</div>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tuoteID'); ?>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 AND yksikko='h' AND nayta_vain_onlinevarauksessa=0";
		$tp = TuotteetPalvelut::model()->findAll($criteria);
		$oletus = TuotteetPalvelut::model()->find("oletustuote=2");
		if( !isset($model->id) and isset($oletus->id) ){
			$model->tuoteID = $oletus->id;
		}
		echo $form->dropDownList($model,'tuoteID', CHtml::listData($tp, 'id', 'nimike'), 
		array('empty'=>'Valitse','class'=>'form-control'));
		?>
  </div>
</div>

<br>
<div class="row" id="lisapalvelut_valinta">
  <div class="col-sm-3">
  <label><?=Yii::t('main','Valitse tuotteet ja lisäpalvelut')?></label>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->condition = " aktiivinen=1 AND hinta_alv_0!=0 AND nayta_vain_onlinevarauksessa=0";
		$tp = TuotteetPalvelut::model()->findAll($criteria);
		echo '<select name="lisapalvelu_tuote" id="lisapalvelu_tuote" class="form-control">';
		echo '<option value=>Valitse</option>';
		foreach($tp as $item){
			echo '<option value="'.$item->id.'" yksikko="'.$item->yksikko.'">'.$item->nimike.'</option>';
		}
		echo '</select>';
		?>
  </div>
  <div class="col-sm-3">
	<label><?=Yii::t('main','Lisää painamalla plussa')?></label>
	<div class="row">
	 <div class="col-sm-10">
		<?php echo CHtml::numberField('lisapalvelu_maara','lisapalvelu_maara',array('class'=>'form-control', 'placeholder' => 'määrä')); ?>
	 </div>
	 <div class="col-sm-2">
        	<button class="btn btn-primary plus_lisapalvelu myBgColors pull-right" type="button"><i class="fa fa-plus"></i></button>
	 </div>
	</div>
  </div>
</div>

<br>
<p id="lisapalvelu_lista"></p>


<script type="text/javascript">
$(document).ready(function(){
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
		
	$('#lisapalvelu_lista').append('' +
	'<div class="row">' +
	 '<div class="col-sm-3">' +
		$('#lisapalvelu_tuote option:selected').text() +
		'<input type="hidden" name="Tyovuoroot[lisa_tuotteet][tuote][]" value="'+ $('#lisapalvelu_tuote option:selected').val() +'">' +
	 '</div>' +
	 '<div class="col-sm-3">' +
		'<div class="text-center">' + 
			'<i class="link pull-right text-danger fa fa-trash poista_lisa"></i>' +
			'<b>' + $('#lisapalvelu_maara').val() + ' '+ lisapalvelu_yksikko +'</b>' + 
		'</div>' +
		'<input type="hidden" name="Tyovuoroot[lisa_tuotteet][maara][]" value="'+ $('#lisapalvelu_maara').val() +'">' +
	 '</div>' +
	'</div>' );

	$('#lisapalvelu_tuote').css({'border' : '1px green solid'}).val('');
	$('#lisapalvelu_maara').css({'border' : '1px green solid'}).val('');
  });

  $(document).delegate(".poista_lisa","click",function(){
	$(this).closest('.row').remove();
  });

});
</script>

<div class="row">
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'toimenpiteet'); ?>
		<?php echo $form->textarea($model,'toimenpiteet',array('rows'=>4,'class'=>'form-control', 
		'placeholder'=>Yii::t('main', 'Kirjoita tähän toimenpiteet, jotka näytetään sekä asiakkaalle että kohteen kortilla toimenpite kentäällä')
		)); ?>
		<?php echo $form->error($model,'toimenpiteet'); ?>

		<?php echo $form->labelEx($model,'tilausviesti'); ?>
		<?php echo $form->textarea($model,'tilausviesti',array('rows'=>4,'class'=>'form-control', 
		'placeholder'=>Yii::t('main', 'Kirjoita tähän viesti')
		)); ?>
		<?php echo $form->error($model,'tilausviesti'); ?>

  </div>
  <div class="col-sm-6">
		<i class="pull-left fa fa-star text-danger"></i>
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textarea($model,'tietoja',array('rows'=>4,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
  </div>
</div>

		<br>
		<input type="checkbox" name="vieposti"> <?php echo Yii::t('main','Ilmoita asiakkaalle sähköpostilla'); ?> <br>
		<input type="checkbox" name="vie_hintatietoja" checked> <?php echo Yii::t('main','Näytä asiakkaalle hintatietoja'); ?>

</div>

		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->

<br>

	<div class="panel-footer text-right">
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php 
		echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn  btn-primary','id'=>'submitButton')); 
		?>
	</div>		




<?php $this->endWidget(); ?>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>


<script type="text/javascript">
$(document).ready(function(){


  if($('#Tyovuoroot_kohde').val() !== '')
  {
	var kohdeOn = $('#Tyovuoroot_kohde option:selected').val();
	  	 $.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/getAsiakasByKohde',
			type:'GET',
			data: { "id" : kohdeOn },
			  success:function(data){
			     if(data)
			     {
				data = JSON.parse(data);
			  	console.log(data);
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

  $(document).delegate("#Tyovuoroot_kohde","change",function(){

	var thisID = $(this, 'option:selected').val();

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/showohje?id='+ thisID ,
		  success:function(data){
			//console.log(data);
			var d = JSON.parse(data);

			$('#Tyovuoroot_tietoja').val(d[1]);
			$('#Tyovuoroot_osoite').val(d[3]);
			$('#Tyovuoroot_postinumero').val(d[4]);
			$('#Tyovuoroot_postitoimipaikka').val(d[5]);


			if(d[2] !== ''){
				$('#arvioitu_kesto').html(d[2]);
			} else {
				$('#arvioitu_kesto').html('00:00');
			}

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  });

  $('#asiakas').keyup(function(){
	var thisVal = $(this).val();

	if( thisVal.length >= 2 )
	{

	  	 $.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/asiakas_autocomplete',
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
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/getKohdeByAsiakas',
			type:'GET',
			data: { "id" : thisVal },
			  success:function(data){
				data = JSON.parse(data);
			  	//console.log(data);
				if(data['options'])
					$('#Tyovuoroot_kohde').html(data['options']);
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
			type:'GET',
			data: { "id" : thisVal },
			  success:function(data){
				data = JSON.parse(data);
			  	//console.log(data);
				$('#Tyovuoroot_kohde').html(data);
				$('#asiakasAutocompleteResult').html('').hide();
				$('#asiakas').val(thisAsiakas);

			  },
			  error:function(data){
			  	console.log(data);
			  }
	 	});
     });

  });

  $('#uusi_asiakas').on('show.bs.collapse', function (e) {
	$("#asiakas").val("").hide();
	$("#Tyovuoroot_kohde").val("").hide();
  });

  $('#uusi_asiakas').on('hidden.bs.collapse', function (e) {
	$("#asiakas").val("").show();
	$("#Tyovuoroot_kohde").val("").show();
  });

  // <-- Yritys vai henkilo
  $("#Asiakkaat_tyyppi").change(function() {
    var value = $(this).val();
    asiakasTyyppi(value);
  });

  $("#Asiakkaat_tyyppi").each(function() {
    var value = $(this).val();
    asiakasTyyppi(value);
  });

  function asiakasTyyppi(value){

    if(value == 'yritys'){
	$(".ashidd").hide('slow');
	$(".yritys").show('slow');
	$(".y_tunnus").show('slow');
	$(".nimi").show('slow');
    }
    if(value == 'henkilo'){
	$(".ashidd").hide('slow');
	$(".nimi").show('slow');
    }

  }
  // Yritys vai henkilo -->


  $('.timeVuorot').mask('00:00',{
        placeholder: "__:__"
  });

  $(".sw").bootstrapSwitch({
	size: "small",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

  $('#submitButton').click(function(){
	   if( $("#uusi_asiakas").hasClass("in") ){
		if( $('#Asiakas_etunimi').val() === '' )
		{
			$('#Asiakas_etunimi').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Asiakas_osoite').val() === '' )
		{
			$('#Asiakas_osoite').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Asiakas_postinumero').val() === '' )
		{
			$('#Asiakas_postinumero').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Asiakas_kaupunki').val() === '' )
		{
			$('#Asiakas_kaupunki').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Tyovuoroot_alku').val() === '' )
		{
			$('#Tyovuoroot_alku').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Tyovuoroot_loppu').val() === '' )
		{
			$('#Tyovuoroot_loppu').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Tyovuoroot_tid option:selected').val() === '' )
		{
			$('#Tyovuoroot_tid').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Tyovuoroot_tuoteID option:selected').val() === '' )
		{
			$('#Tyovuoroot_tuoteID').css({"border":"2px red solid"}).focus();
			return false;
		}

		var r = confirm('Olet myös luomassa uuden asiakkaan ja kohteen.\n Haluatko jatkaa?');
		if(r)
		{
			$(this).remove();
			$('#tyovuoroot-form').submit();
		} else {
			return false;
		}

	   } else {

		if( $('#Tyovuoroot_kohde').val() === '' )
		{
			$('#Tyovuoroot_kohde').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Tyovuoroot_alku').val() === '' )
		{
			$('#Tyovuoroot_alku').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Tyovuoroot_loppu').val() === '' )
		{
			$('#Tyovuoroot_loppu').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Tyovuoroot_tid option:selected').val() === '' )
		{
			$('#Tyovuoroot_tid').css({"border":"2px red solid"}).focus();
			return false;
		}
		if( $('#Tyovuoroot_tuoteID option:selected').val() === '' )
		{
			$('#Tyovuoroot_tuoteID').css({"border":"2px red solid"}).focus();
			return false;
		}
			$(this).remove();
			$('#tyovuoroot-form').submit();
	   }
	});

	$('#tyovuoroot-form').on('submit',function(e) {

	//console.log( $( this ).serializeArray() );
	//console.log( e.target[0].value );
	var str = '';


	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/uusitilaus',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			thisDataReturn = JSON.parse(data);
			console.log(data);
			if(getUrlVars()["mode"]){ // Kun olet TV taulussa
				laatikonPaivays();
			}
			if( thisDataReturn['sahkoposti'] ){
				alert(thisDataReturn['sahkoposti']);
				return false;
			}
			$('#showres').modal('hide');

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
	e.preventDefault(); 
  });

  function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
    function(m,key,value) {
      vars[key] = value;
    });
    return vars;
  }

  function laatikonPaivays(){
	$.ajax({
		url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did4?from=<?=$haku_from?>&to=<?=$haku_to?>',
		type: 'POST',
		data: { tids : getAllTids() },
		success:function(data){
			data = JSON.parse(data);
			//console.log(data);
			$.tv_arr_update(data);
			$.vkolaskenta(getAllTids());
		  	$('#showres').modal('hide');
		},error:function(data){
		  	console.log(data);
			//window.location.href=location.protocol + "//" + location.host + '/index.php';
		}
	});
  }

  function getAllTids(){
	var tids = [];
	tids.push($('#Tyovuoroot_tid option:selected').val());
	/* Työpari */
	var tyopaari = $('#tyopaari').val();
	if(tyopaari !== null){
		//console.log('Uudet työparit: ' + tyopaari);
		$(tyopaari).each(function( index, val ) {
			tids.push(val);
		});
	}
	/* Työpari */
	//console.log('Tids joille päivitetään laatikko: ' + tids);
	return tids;
  }

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


  $('#alku').blur(function(){
	var alku = $("#alku").val().split(':');
	if(!alku[1] & $("#alku").val() !== '')
	{
		var h = $("#alku").val() ^ 0 ;
		var m = 0 ^ 0 ;
		$("#alku").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
		laskePituus();
	}
  });

  $('#loppu').blur(function(){
	var alku = $("#loppu").val().split(':');
	if(!alku[1])
	{
		var h = $("#loppu").val() ^ 0 ;
		var m = 0 ^ 0 ;
		$("#loppu").val((h<10?"0"+h:h)+":"+(m<10?"0"+m:m));
		laskePituus();
	}
  });


  $('#alku').keyup(function(){
	laskePituus();
  });

  $('#loppu').keyup(function(){
	laskePituus();
  });

  $('#alku').change(function(){

	laskePituus();
  });

  $('#loppu').change(function(){
	laskePituus();
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

});
</script>
