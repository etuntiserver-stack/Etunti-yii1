<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */

if(isset($_POST['pvm']))
  $model->pvm = date("d.m.Y",strtotime($_POST['pvm']));
else
  $model->pvm = date("d.m.Y",strtotime($model->pvm));


if(isset($_POST['tid']))
  $model->tid = $_POST['tid'];


		  $ohje = '';
if(isset($model->id))
{

	$m = Kohteet::model()->findbypk($model->kohde);
		
	if(isset($m->id))
	{

		$k = explode("//",$m->kenella_on_avain);

		  $ohje = '';
		if(isset($k[1]))
		  $ohje .= Yii::t('main', 'Avain on: ')." ".$k[1]."\n";
		if(!empty($m->avain))
		  $ohje .= Yii::t('main', 'Avain: ')." ".$m->avain."\n\n";
		if(!empty($m->aikataulu))
		  $ohje .= "\nAikataulu: ".$m->aikataulu;
		if(!empty($m->toimenpiteet))
		  $ohje .= "\nToimenpiteet: ".str_replace("\n","<br>",$m->toimenpiteet)."<br>";
		if(!empty($m->tietoja))
		  $ohje .= "\nTietoja: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut: ".$m->muut;

	}

	echo '<input type="hidden" id="updateMuoto" value="true">';
} else {
	echo '<input type="hidden" id="updateMuoto" value="false">';
}


$ov = Onlinevaraus::model()->findbypk($model->onlinevaraus_id);
if(isset($ov->id) and !empty($ov->kohde_id) and empty($model->kohde)){
	Tyovuoroot::model()->updatebypk($model->id, array('kohde'=>$ov->kohde_id));
	$model->kohde = $ov->kohde_id;
}

?>

	<?php if(isset($ov->id)) : ?>
	<div class="section alert bg-warning">
	<?php echo Yii::t('main', 'Tämä kohde on onlinevarauksesta.'); ?>
	</div>
	<?php endif; ?>


<div class="section">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
	'enableAjaxValidation'=>false,

)); ?>


	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'id',array('id'=>$model->id)); ?>
	<?php echo $form->hiddenField($model,'tid'); ?>
	<?php echo $form->hiddenField($model,'ruokatauko'); ?>
	<?php echo $form->hiddenField($model,'alku_r'); ?>
	<?php echo $form->hiddenField($model,'pituus'); ?>
	<?php echo $form->hiddenField($model,'kesto'); ?>
	<?php echo $form->hiddenField($model,'osoiteOnline'); ?>
	<?php echo $form->hiddenField($model,'time'); ?>
	<?php echo $form->hiddenField($model,'toistuva_id'); ?>
	<?php echo $form->error($model,'tid'); ?>

<div class="row">

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'pvm'); ?>
		<?php echo $form->textField($model,'pvm',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepickerFI'));//,'readonly'=>'yes' ?>
		<?php echo $form->error($model,'pvm'); ?>
  </div>

  <div class="col-sm-3">
		<label><?php echo Yii::t('main', 'Asiakas tai kohteen yhteyshenkilö'); ?></label><br>
		<input type="text" id="asiakas" class="form-control" AUTOCOMPLETE="off">
		<div id="asiakasAutocompleteResult"></div>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'kohde'); ?>
		<?php
        		$list = CHtml::listData(Kohteet::model()->findAll(array('order' => 'osoite')), 'id', 'osoite');
        		echo $form->dropDownList($model, 'kohde', $list,array('empty'=>'Valitse','class'=>'form-control kohde'));
        	?>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
        	$l = $this->tilanteet();
		echo $form->dropDownList($model,'status', $l, 
		array('empty'=>Yii::t('main', 'Valitse'), 'class'=>'form-control')) ?>

  </div>

</div>

<div class="row">

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'alku'); ?>
		<input type="text" name="Tyovuoroot[alku]" class="form-control laske timeVuorot" id="alku" value="<?php echo $model->alku; ?>" autofocus>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?>
		<input type="text" name="Tyovuoroot[loppu]" class="form-control laske timeVuorot" id="loppu" value="<?php echo $model->loppu; ?>">
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
        	$tal = Valikkoot::model()->findAll(" select_type='tyoajanmerkinta' ", array('order' => 'select_type'));
		echo '<select name="Tyovuoroot[tyoajanmerkinta]" class="form-control" id="Tyovuoroot_tyoajanmerkinta">';

		 if(!empty($model->tyoajanmerkinta)){
		   $expl = explode("/",$model->tyoajanmerkinta);
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   echo '<option value="'.$model->tyoajanmerkinta.'">'.$value.'</option>';
		 }

		   echo '<option style="color:" value="Normaali/">Normaali</option>';
		   echo '<option style="color:red" value="Ei lasketa/red">Ei lasketa</option>';

		 foreach($tal as $v)
		 {
		   $expl = explode("/",$v->value);
		   $color = (isset($expl[1])) ? $expl[1] : '';
		   $value = (isset($expl[0])) ? $expl[0] : '';
		   if($v->value != 'Normaali/' and $v->value != 'Ei lasketa/red')
		   echo '<option style="color:'.$color.'" value="'.$v->value.'">'.$value.'</option>';
		 }
		echo '</select>';
        	?>

		<span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="tyoajanmerkinta"><i class="fa fa-pencil-square-o"></i></span>
		</span>
		</div>
  </div>

</div>


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
		//console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */

 $('#Tyovuoroot_status').change(function(){
	if($(this).val() == '10')
		$('#Tyovuoroot_tyoajanmerkinta').val('Ei lasketa/red');
	else
		$('#Tyovuoroot_tyoajanmerkinta').val('Normaali/');
 });

});
</script>

<div class="row">

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tuoteID'); ?>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->condition = " is_active=1 ";
		echo $form->dropDownList($model,'tuoteID', CHtml::listData(LaskutusTuotteet::model()->findAll($criteria), 'id', 'tuotenimi'), 
		array('empty'=>'Valitse','class'=>'form-control'));
		?>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'peruutettu'); ?>
		<?php
		$list = array(0=>'Ei', 1=>'Peruutettu', 2=>'Peruutettu laskutettava');
		echo $form->dropDownList($model,'peruutettu', $list, 
		array('empty'=>'Valitse','class'=>'form-control'));
		?>
  </div>

</div>

<div class="row">
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php 
		echo $form->textarea($model,'tietoja',array('rows'=>4,'class'=>'form-control', 'placeholder'=>'Esim. Avainten tiedot tai kohteesa olevat rajoitukset.')); 
		?>
		<?php echo $form->error($model,'tietoja'); ?>
  </div>
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'ohje'); ?>
		<div style="height:100px; overflow: scroll; overflow-x:hidden;">
		<div class="ohje"><?php echo $ohje; ?></div>
		</div>
  </div>
</div>



<div class="row">
  <div class="col-sm-6">
		<label><?php echo Yii::t('main', 'Työpari'); ?></label><br>
		<?php 
		$tyopaari = json_decode($model->tyopaari, true);

		$criteria=new CDbCriteria;
		$criteria->order =" tekijan_nimi ";
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
  <div class="col-sm-6">



    <div class="pull-right">
    <br>
  	<div class="section">





		<?php echo $form->labelEx($model,'piilota_mobiilista'); ?>
		<?php 
        	$l = array(0=>'Kyllä',1=>'Ei');
		echo $form->dropDownList($model,'piilota_mobiilista', $l, 
		array('class'=>'form-control')) ?>

	</div>
	<?php 
	$t = Tyontekijat::model()->findbypk($model->tid);
	if(!empty($t->gcm_reg_id)) :
	?>
	<br>
  	<div class="section">
		<label><?php echo Yii::t('main','Ilmoita työntekijää viestillä'); ?></label><br>
		<input type="checkbox" name="Tyovuoroot[PushNotify]" class="sw" id="Tyovuoroot_PushNotify">
	</div>
	<?php endif; ?>
    </div>



  </div>
</div>

<br>

<?php
    $pfrom = '';
    $pto = '';
    $viikkoja = '';
    $viikko_paivat = array();
    $classCol = 'collapse';
    $toistuvaID =  '<span id="toistuvaID"></span>';

  if(isset($model->id) and $model->toistuva_id != 0)
  {
    $toistuva = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id);
    if(isset($toistuva->id))
    {
    	$pfrom = $toistuva->pfrom;
    	$viikkoja = $toistuva->viikkoja;
    	$viikko_paivat = json_decode($toistuva->viikko_paivat, true);
    	$pto = $toistuva->pto;
    	$classCol = 'collapse in';
    	$toistuvaID =  '<span id="toistuvaID">'.$model->toistuva_id.'</span>';
    }

  } else {
    $pfrom = $model->pvm;
  }
?>
<hr>

<div id="toistuvaAllsijaan"></div>

<div id="toistuvaAll">
<div class="row">
 <div class="col-sm-12">

	<?php //if((isset($model->id) and $model->toistuva_id != 0) or !isset($model->id)) : ?>
  	<a href="#" class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"> <?php echo Yii::t('main','Toistuva työvuoro'); ?></a>
	<?php //endif; ?>

	<div class="<?php echo $classCol; ?>" id="collapseExample">
	<br>
	<p>
	<b><?php echo Yii::t('main','Muokkaa toistuvaa työvuoroa. Jos valintaa ei ole tehtynä, muokataan vain kyseisen päivän työvuoroa.'); ?></b> <br> 
	<input type="checkbox" class="sw" name="ToistuvatTyovuorot[toistuva_aktiivinen]" id="toistuva_aktiivinen">
	</p>
	<br>

<div class="row">
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Alkaen'); ?></label>
	<input type="text" class="form-control datepickerFI" name="ToistuvatTyovuorot[pfrom]" id="pfrom" value="<?php echo date('d.m.Y', strtotime($pfrom)); ?>">
  </div>
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Loppuen'); ?></label>
	<input type="text" class="form-control datepickerFI" name="ToistuvatTyovuorot[pto]" id="pto" value="<?php if(!empty($pto)) echo date('d.m.Y', strtotime($pto)); ?>">
  </div>
  <div class="col-sm-4">
	<label><?php echo Yii::t('main', 'Työvuorojen viikkoväli'); ?></label>
	<select class="form-control" name="ToistuvatTyovuorot[viikkoja]">
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

<br>
<div class="row" id="vikoPvm">
  <div class="col-sm-12 col-sm-offset-1">
  <label><?php echo Yii::t('main', 'Ma'); ?></label>

  <?php if(in_array(1, $viikko_paivat)): ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[1]" id="ma" value="1" checked>
  <?php else: ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[1]" id="ma" value="1">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'Ti'); ?></label>

  <?php if(in_array(2, $viikko_paivat)): ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[2]" id="ti" value="2" checked>
  <?php else: ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[2]" id="ti" value="2">
  <?php endif; ?>


  <label><?php echo Yii::t('main', 'Ke'); ?></label>

  <?php if(in_array(3, $viikko_paivat)): ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[3]" id="ke" value="3" checked>
  <?php else: ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[3]" id="ke" value="3">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'To'); ?></label>

  <?php if(in_array(4, $viikko_paivat)): ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[4]" id="to" value="4" checked>
  <?php else: ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[4]" id="to" value="4">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'Pe'); ?></label>

  <?php if(in_array(5, $viikko_paivat)): ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[5]" id="pe" value="5" checked>
  <?php else: ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[5]" id="pe" value="5">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'La'); ?></label>

  <?php if(in_array(6, $viikko_paivat)): ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[6]" id="la" value="6" checked>
  <?php else: ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[6]" id="la" value="6">
  <?php endif; ?>

  <label><?php echo Yii::t('main', 'Su'); ?></label>

  <?php if(in_array(7, $viikko_paivat)): ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[7]" id="su" value="7" checked>
  <?php else: ?>
  <input type="checkbox" class="sw vkopvmswitch" name="P[7]" id="su" value="7">
  <?php endif; ?>

  </div>
</div>

	</div>
 </div>
</div>

<div id="sopivatPaivat" style="display:none"></div>
<input type="hidden" name="ToistuvatTyovuorot[sopivatPaivat]" id="sopivatPaivatInput" value="0">
</div>

</div><!-- toistuvaAll -->



		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->

<br>

	<div class="panel-footer text-right">
		<?php 
	   	$site = Yii::app()->createController('Site');

	   	$checkPoista = "tyovuorot_3_".Yii::app()->user->adminStatus;
	   	$poista = $site[0]->checkOikeusFields($checkPoista);

			if(isset($model->id) and $poista == 1)
			{
			$doit = date("Ymd",strtotime($model->pvm))."_".$model->tid; 
			echo CHtml::Button('Poista',array('class'=>'btn btn-danger', 'id'=>'poistaTv', 'for'=>$doit, 'model'=>$model->id, 'data-dismiss'=>'modal'));
			}
		?>
		<?php echo CHtml::Button('Sulje',array('class'=>'btn btn-default','data-dismiss'=>'modal')); ?>
		<?php 


	   	$checkLuo = "tyovuorot_1_".Yii::app()->user->adminStatus;
	   	$luo = $site[0]->checkOikeusFields($checkLuo);

	   	$checkTallenna = "tyovuorot_2_".Yii::app()->user->adminStatus;
	   	$tallenna = $site[0]->checkOikeusFields($checkTallenna);

		if(!isset($model->id) and $luo == 1)
		echo CHtml::submitButton('Luo',array('class'=>'btn btn-primary','id'=>'submitButton'));
		elseif(isset($model->id) and $tallenna == 1)
		echo CHtml::submitButton('Tallenna',array('class'=>'btn btn-primary','id'=>'submitButton')); 
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
				$('#Tyovuoroot_kohde').html(data);
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



  $('.timeVuorot').mask('00:00',{
        placeholder: "__:__"
  });

  $(".sw").bootstrapSwitch({
	size: "mini",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

/* ei toimi kun haluan luoda toistuva olevasta tyovuorosta
  if( $('#updateMuoto').val() == "true" )
  {
    $('.vkopvmswitch').on('switchChange.bootstrapSwitch', function () {
    	$('#pfrom').attr('readonly', 'yes');
    	$('#pto').attr('readonly', 'yes');
    });

    $('#pfrom, #pto').on('blur', function () {
    	$(".vkopvmswitch").bootstrapSwitch('toggleDisabled',true,true);
    });
  }
*/

	var pfrom = '';
	var pto = '';

	$('#submitButton').click(function(){

		$('#tyovuoroot-form').submit();
		return false;
	});



	$('#tyovuoroot-form').on('submit',function(e) {

	if( ($('#submitButton').val() === 'Luo') || ($('#submitButton').val() === 'Tallenna') ) 
		$('#submitButton').hide();

	// <-- tarkistetaan tietoja pituus
	var leng = $('#Tyovuoroot_tietoja').val().length;

	var raja = 10000;
	if(leng > raja)
	{
		alert('Tietoja mobiilisovellukseen kentän merkkimäärä ei voi ylittää '+raja+' rajaa');
		return false;
	}
	// tarkistetaan tietoja -->

	// <-- tarkistetaan ajaat päällekäin
	if( e.target[0].value === '')
	{
	var tid		= $('#Tyovuoroot_tid').val();
	var pvm		= $('#Tyovuoroot_pvm').val();
	var alku 	= $("#alku").val();
	var loppu 	= $("#loppu").val();
	var count	= 0;
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/check_paallekkain',
		  data:{ tid : tid, pvm : pvm, alku : alku, loppu : loppu },
		  type:'POST',
		  async: false,
		  success:function(data){
			data = JSON.parse(data);
			console.log(data);
			if(data > 0)
			count = data;
	   	},
		error:function(data){
			console.log(data);
	    	}
	  });

	  if(count > 0){
		var r = confirm('Aika päällekkäin, haluatko jatkaa');
		if(!r){
			$('#submitButton').show();
			return false;
		}
	  }
	}
	//     tarkistetaan ajaat päällekäin -->


	if(($('#toistuva_aktiivinen').bootstrapSwitch('state') === true) && ($('#pto').val() === ''))
	{
		$('#pto').addClass('bg-danger');
		return false;
	}

	if( ($('#toistuva_aktiivinen').bootstrapSwitch('state') === true) && 
	(	$('#ma').bootstrapSwitch('state') === false & 
		$('#ti').bootstrapSwitch('state') === false & 
		$('#ke').bootstrapSwitch('state') === false & 
		$('#to').bootstrapSwitch('state') === false & 
		$('#pe').bootstrapSwitch('state') === false & 
		$('#la').bootstrapSwitch('state') === false & 
		$('#su').bootstrapSwitch('state') === false
	) )
	{
		$('#vikoPvm').addClass('alert alert-danger');
		return false;
	}

  	if($('#pfrom').val() !== ''){
		pfrom = $('#pfrom').val().split(".");
		pfrom = parseInt(pfrom[2]+''+pfrom[1]+''+pfrom[0]);
	}
  	if($('#pto').val() !== ''){
		pto = $('#pto').val().split(".");
		pto = parseInt(pto[2]+''+pto[1]+''+pto[0]);
	}

	if(pto !=='' & pto < pfrom)
	{
		alert('Toistuvan työvuoron lopetuspäivämäärä ei voi olla ennen toistuvan työvuoron aloituspäivämäärä');
		return false;
	}


	var str = '';
	var thisDataReturn = [];

	if( e.target[0].value != '')
	{

	// paivita vanhat
	  var toistuva_aktiivinen = $('#toistuva_aktiivinen').is(':checked');
	  if((toistuva_aktiivinen === true) && ($('#sopivatPaivatInput').val() == 1))
	  {
	  //alert(e.target[7].value);
	  $.ajax({
		  url: 'paivita_laatikot',
		  data:{ toistuva_id : $('#Tyovuoroot_toistuva_id').val() },
		  type:'POST',
		  success:function(data){
			data = JSON.parse(data);
			console.log('paivita laatikot > ' +data);
			laatikonPaivays(data);

	   	},
		error:function(data){
		console.log(data);

	    	}
	  });
  	  }
	// paivita vanhat


	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update?id='+e.target[0].value,
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			thisDataReturn = JSON.parse(data);
			console.log(thisDataReturn);
			paivaysTarkistus(thisDataReturn);
	   	},
		error:function(data){
			console.log(data);
			window.location.href=location.protocol + "//" + location.host + '/index.php';
	    	}
	  });

	} else {


	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/create',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			thisDataReturn = JSON.parse(data);
			console.log(thisDataReturn);
			paivaysTarkistus(thisDataReturn);
	   	},
		error:function(data){
			console.log(data);
			window.location.href=location.protocol + "//" + location.host + '/index.php';
	    	}
	  });



	}




  function paivaysTarkistus(thisDataReturn){

	var onkosama = '';

			if( $('#toistuva_aktiivinen').bootstrapSwitch('state') === true )
			{

				var isSaved = false;

				$(thisDataReturn).each(function( iarr, arr ) {
				 $(arr).each(function( i, d ) {
					if( d['isSaved'] === true )
					isSaved = true;
				 });
				});




				if( isSaved === true )
				{
					laatikonPaivays(thisDataReturn);

				} else {

					//$('input').attr('readonly','yes');


					$('#sopivatPaivat').html('<br><h3>Toistuvien työvuorojen päivämäärät</h3><div class="col-sm-offset-1">').show('slow');
					$(thisDataReturn).each(function( iarr, arr ) {
					 $(arr).each(function( i, d ) {

					   if(d['onkosama'])
					   {
					   	$('#sopivatPaivat').append('<div class="row"><b class="text-danger"><div class="col-sm-3">'+d['pvm']+'</div><div class="col-sm-3">'+d['vkopvm']+'</div><div class="col-sm-3">'+d['tekijan_nimi']+'</div><div class="col-sm-3">Ei muutoksia</div></b></div>');
					   } 
					   else if(d['poistetaan'])
					   {
					   	$('#sopivatPaivat').append('<div class="row"><b class="text-warning"><div class="col-sm-3">Kaikki</div><div class="col-sm-3">Kaikki</div><div class="col-sm-3">'+d['tekijan_nimi']+'</div><div class="col-sm-3">Pois taulusta</div></b></div>');
					   }
					   else if(d['uusi']) {
					   	$('#sopivatPaivat').append('<div class="row"><b class="text-success"><div class="col-sm-3">'+d['pvm']+'</div><div class="col-sm-3">'+d['vkopvm']+'</div><div class="col-sm-3">'+d['tekijan_nimi']+'</div><div class="col-sm-3">Uusi</div></b></div>');
					   }
					   else if(d['muokkaus']) {
					   	$('#sopivatPaivat').append('<div class="row"><b class="text-warning"><div class="col-sm-3">'+d['pvm']+'</div><div class="col-sm-3">'+d['vkopvm']+'</div><div class="col-sm-3">'+d['tekijan_nimi']+'</div><div class="col-sm-3">Muokkaus</div></b></div>');
					   }
					   else if(d['poistaminen']) {
					   	$('#sopivatPaivat').append('<div class="row"><b class="text-danger"><div class="col-sm-3">'+d['pvm']+'</div><div class="col-sm-3">'+d['vkopvm']+'</div><div class="col-sm-3">'+d['tekijan_nimi']+'</div><div class="col-sm-3">Poistetaan</div></b></div>');
					   }
					   else if(d['poistaminenVkoPvm']) {
					   	$('#sopivatPaivat').append('<div class="row"><b class="text-danger"><div class="col-sm-3">'+d['pvm']+'</div><div class="col-sm-3">'+d['vkopvm']+'</div><div class="col-sm-3">'+d['tekijan_nimi']+'</div><div class="col-sm-3">Poistetaan viikkon pvm</div></b></div>');
					   }
					   else if(d['lisaaminenVkoPvm']) {
					   	$('#sopivatPaivat').append('<div class="row"><b class="text-success"><div class="col-sm-3">'+d['pvm']+'</div><div class="col-sm-3">'+d['vkopvm']+'</div><div class="col-sm-3">'+d['tekijan_nimi']+'</div><div class="col-sm-3">Lisätään viikkon pvm</div></b></div>');
					   }
					   else if(d['ketjunMuutos']) {
					   	$('#sopivatPaivat').append('<div class="row"><b class="text-warning"><div class="col-sm-3">'+d['pvm']+'</div><div class="col-sm-3">'+d['vkopvm']+'</div><div class="col-sm-3">'+d['tekijan_nimi']+'</div><div class="col-sm-3">Ketjun muutos</div></b></div>');
					   }
					   else if(d['ERROR']) {
						   	$('#sopivatPaivat').append(d['ERROR']);
					   }



					 });
					});
					$('#sopivatPaivat').append('<br><span class="btn btn-success sopiiSopivat">Hyväksy valitut päivät</span></div>');

				}


			} else {

					laatikonPaivays(thisDataReturn);
			}


			if( $('#submitButton').attr('pvmTarkistus') !== "true" ) 
					$('#showres').modal('hide');
  }



		// <-- Viikko update total
	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/viikko',
			type:'GET',
			data: { "tid" : "<?php echo $model->tid; ?>", "viikko" : "<?php echo date('W',strtotime($model->pvm)); ?>", "year" : "<?php echo date('Y',strtotime($model->pvm)); ?>" },
			  success:function(data){
			  //console.log(data);
			  $('#vk_<?php echo date("W",strtotime($model->pvm))."_".$model->tid; ?>').html(data);
			  return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});
		//     Viikko update total -->




	e.preventDefault();

	});


// Poistaminen
$('#poistaTv').click(function(){

	var thisID = 'checkThis_'+$(this).attr('for');
	var model = $(this).attr('model');
	var toistuva_aktiivinen = $('#toistuva_aktiivinen').is(':checked');

	var r = confirm('Haluatko varmasti poistaa?');
	if(r)
	{

	// paivita vanhat
/*
	  if(toistuva_aktiivinen === true)
	  {
	  var toistuva_id = $('#Tyovuoroot_toistuva_id').val();
	  $.ajax({
		  url: 'poista_toistuva',
		  data:{ toistuva_id : toistuva_id },
		  type:'POST',
		  success:function(data){
			data = JSON.parse(data);
			console.log('paivita laatikot, poisto > ' +data);
			laatikonPaivays(data);

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  	  }
*/
	// paivita vanhat


        $.ajax({
           url: 'poistaTv',
	   type:'POST',
	   data: { "poistaTv" : model, toistuva_aktiivinen : toistuva_aktiivinen, pfrom : $('#pfrom').val(), pto : $('#pto').val() },
           success: function(data){
		data = JSON.parse(data);
		console.log('paivita laatikot, poisto > ' +data);
		laatikonPaivays(data);
		//parent.postMessage( "doit//"+thisID, "*");

    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    		console.log(XMLHttpRequest);
			window.location.href=location.protocol + "//" + location.host + '/index.php';
 	   }
        });
	}

});



function laatikonPaivays(thisDataReturn){

		var splDID = [];
		var did = '';
		var ilmoitus = '';
		$(thisDataReturn).each(function( iarr, arr ) {
		 $(arr).each(function( i, d ) {
		 //console.log(d['pvm']);

	  	    $.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
			type:'GET',
			data: { "pvm" : d['pvm'], "tid" : d['tid'], "from" : "ajax" },
			  success:function(data){
			  //console.log(data);


			  if( $('#'+d['ymd']+'_'+d['tid']).length )
			  {
			    $('#'+d['ymd']+'_'+d['tid']).html(JSON.parse(data));
			    if(parent.location.href.match(/index/))
			    {
				var ThisHeight = $('#'+d['ymd']+'_'+d['tid']).height();
				var FirstHeight = $('#first_'+d['tid']).height(ThisHeight);
			    }
			    if(parent.location.href.match(/tv2/))
			    {
				var ThisHeight = $('#'+d['ymd']+'_'+d['tid']).height();
				var FirstHeight = $('#first_'+d['ymd']).height(ThisHeight);
			    }


			  }


			  },
			  error:function(data){
			  	console.log(data);
				window.location.href=location.protocol + "//" + location.host + '/index.php';
			  }
	 	    });

		 });
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


  
  if( $('#Tyovuoroot_kohde').val() !== '' ){
	var thisID = $('#Tyovuoroot_kohde option:selected').val();
	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/showohje?id='+thisID,
		  success:function(data){
			//console.log(data);
			var d = JSON.parse(data);

			if(d[2] !== '')
				$('#arvioitu_kesto').html(d[2]);
			else
				$('#arvioitu_kesto').html('00:00');

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  }

  $('#Tyovuoroot_kohde').change(function(){

	var thisID = $(this).val();

	  $.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/showohje?id='+thisID,
		  success:function(data){
			//console.log(data);
			var d = JSON.parse(data);


			$('.ohje').html(d[0]);
			if( $('#Tyovuoroot_tietoja').val() === '' )
			$('#Tyovuoroot_tietoja').val(d[1]);

			if(d[2] !== '')
				$('#arvioitu_kesto').html(d[2]);
			else
				$('#arvioitu_kesto').html('00:00');

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  });


  $('#tekijanVaihdo').change(function(){
	var thisId = $('#tekijanVaihdo option:selected').val();
	$('#Tyovuoroot_tid').val(thisId);
  });

  $(document).delegate(".sopiiSopivat","click",function(){
	$(this).remove();
	$('#sopivatPaivatInput').val(1);
	$('#toistuvaAll').hide('slow');
	$('#submitButton').val('Tallenna').removeAttr( "pvmTarkistus" );
	$('#toistuvaAllsijaan').html('<h3 class="alert alert-success">Toistuvien työvuorojen päivät tallennettu.<br>Paina Luo-painikketta lisätäksesi työvuorot työvuorolistaan.</h3>').show('slow');
  });

  $('#pto').blur(function(){
  	$(this).removeClass('bg-danger').addClass('bg-success');
  });


  $('#toistuva_aktiivinen').on('switchChange.bootstrapSwitch', function(event, state) {
	if(state === true){

		if( $('#pto').val() === '' )
		$('#pto').removeClass('bg-success').addClass('bg-danger');

		$('#submitButton').val('Tarkista päivämäärät').attr("pvmTarkistus",true);
	} else {
		$('#submitButton').val('Tallenna').removeAttr( "pvmTarkistus" );
	}
	switchesPvm();
  });

  $('#ma,#ti,#ke,#to,#pe,#la,#su').on('switchChange.bootstrapSwitch', function(event, state) {
	switchesPvm();
  });

  function switchesPvm(){

	if( ($('#toistuva_aktiivinen').bootstrapSwitch('state') === true) && 
	(	$('#ma').bootstrapSwitch('state') === true | 
		$('#ti').bootstrapSwitch('state') === true | 
		$('#ke').bootstrapSwitch('state') === true | 
		$('#to').bootstrapSwitch('state') === true | 
		$('#pe').bootstrapSwitch('state') === true | 
		$('#la').bootstrapSwitch('state') === true | 
		$('#su').bootstrapSwitch('state') === true
	) )
	{
		$('#vikoPvm').removeClass('alert alert-danger').addClass('alert alert-success');
	} else {
		$('#vikoPvm').removeClass('alert alert-success').addClass('alert alert-danger');
	}

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


});
</script>
