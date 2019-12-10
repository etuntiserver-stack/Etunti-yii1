<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */
/* @var $form CActiveForm */


// <-- Check tunnit jos ilmainen
$site = Yii::app()->createController('Site');
$checkPoista = "tyovuorot_3_".Yii::app()->user->adminStatus;
$poista = $site[0]->checkOikeusFields($checkPoista);

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
// <-- on CREATE
if(!isset($model->id)){
	if(isset($pvm))
		$model->pvm = $pvm;
	if(isset($tid))
		$model->tid = $tid;
}
$tyopaari = json_decode($model->tyopaari, true);

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
		  $ohje .= "\nTietoja: ".$m->tietoja;
		if(!empty($m->muut))
		  $ohje .= "\nMuut: ".$m->muut;

	}

	echo '<input type="hidden" id="updateMuoto" value="true">';
} else {
	echo '<input type="hidden" id="updateMuoto" value="false">';
}

if($toistuva){
	$java_prefix = 'ToistuvatTyovuorot';
	$model->pvm = $laatikko_pvm;
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

	<?php if(isset($ov->id) and $model->osoiteOnline == 2) : ?>
	<div class="section alert bg-warning">
	<?php echo Yii::t('main', 'Tämä kohde on onlinevarauksesta.'); ?>
	</div>
	<?php elseif(isset($ov->id) and $model->osoiteOnline == 3): ?>
	<div class="section alert bg-warning">
	<?php echo Yii::t('main', 'Tämä kohde on eDicosta.'); ?>
	</div>
	<?php endif; ?>


<div class="section">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyovuoroot-form',
	'enableAjaxValidation'=>false,

)); ?>


	<?php echo $form->errorSummary($model); ?>
	<?php if(!$toistuva){ echo $form->hiddenField($model,'toistuva_id'); } ?>
	<?php echo $form->hiddenField($model,'tid'); ?>
	<?php echo $form->error($model,'tid'); ?>

<div id="1_tila">
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
       		$criteria = new CDbCriteria();
	        $criteria->order = " osoite ";
		$criteria->condition = " aktiivinen=1 ";

		// <-- TyoryhmatHelper
		$arr = $site[0]->TyoryhmatHelper();
		$ids = implode(",", $arr);
		if( count($arr) > 0 ){
			$criteria->addCondition(" tyoryhma IN ($ids) ");
		}
		//     TyoryhmatHelper -->

        		$list = CHtml::listData(Kohteet::model()->findAll($criteria), 'id', 'osoite');
        		echo $form->dropDownList($model, 'kohde', $list,array('empty'=>'Valitse','class'=>'form-control kohde'));
        	?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php 
        	$l = $this->tilanteet();
		echo $form->dropDownList($model,'status', $l, 
		array('class'=>'form-control')) ?>
  </div>
</div>

<div class="row">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>20,'maxlength'=>255,'class'=>'form-control ')); ?>
		<?php echo $form->error($model,'osoite'); ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>20,'maxlength'=>255,'class'=>'form-control ')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>20,'maxlength'=>255,'class'=>'form-control ')); ?>
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
			
			echo '<select name="'.$java_prefix.'[tyoajanlaatu]" class="form-control" id="'.$java_prefix.'_tyoajanlaatu">';
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
		<input type="text" name="<?=$java_prefix?>[alku]" class="form-control laske timeVuorot" id="alku" value="<?php echo $model->alku; ?>" autofocus>
  </div>

  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'loppu'); ?> <span style="color:red">*</span>
		<input type="text" name="<?=$java_prefix?>[loppu]" class="form-control laske timeVuorot" id="loppu" value="<?php echo $model->loppu; ?>">
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
		echo '<select name="'.$java_prefix.'[tyoajanmerkinta]" class="form-control" id="'.$java_prefix.'_tyoajanmerkinta">';

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

  $(document).delegate(".muokaTaulunLatiko","click",function(){

    $(this).css({"background" : "#ccc"});

    var thisID = $(this).attr("id");
    var thisDate = $(this).attr("thisdate");
    var thisTid = parseInt($(this).attr("thistid"));
    var thisTXT = $(this).text();
    var id = $(this).attr("method");
    var thisStatus = $(".valikot input:radio:checked").val();
    var lat1 = thisStatus.split("//");
    var lat = '('+lat1[0]+') '+lat1[2]+'/'+lat1[1];
    var vapaateksti = $('.vapaateksti').val();

    var postdata = {
	tid 	: thisTid,
	pvm 	: thisDate,
	status 	: thisStatus,
	tietoja	: vapaateksti,
	tyoajanlaatu : lat,
    }

        $.ajax({
           url: 'vlupdater?id='+id+'&txt='+thisTXT,
	   type: 'POST',
	   data: { Vuosilomat : postdata },
           success: function(data){
		console.log(data);

		var spData  = data.split("//");

		if(spData[3] != '' && data != 'removed'){
		   $("#"+thisID).attr("method",spData[0]);
		   $("#"+thisID).removeClass("myBgColors bg-info");
		   $("#"+thisID).attr("style","background:"+spData[4]+";color:white;");
		   $("#"+thisID).html('<div class="link laatikot">'+ spData[3] +'</div>');
		}

		if(data == 'removed')
		{
		   $("#"+thisID).attr("method", "new");
		   $("#"+thisID).html('<div class="link laatikot"></div>');
		}
    

           },
	   error:function(data){
		console.log(data);
		/*window.location.href=location.protocol + "//" + location.host + "/index.php/site/index";*/
	   }
        });


  });
/* valikot */

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

<div class="row">
  <div class="col-sm-6">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php 
		echo $form->textarea($model,'tietoja',array('rows'=>5,'class'=>'form-control', 'placeholder'=>'Esim. Avainten tiedot tai kohteesa olevat rajoitukset.')); 
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


<div class="row">
  <div class="col-sm-3">
		<label><?php echo Yii::t('main', 'Työpari'); ?></label><br>
		<?php 
		$criteria=new CDbCriteria;
		// <-- Return order etu ja sukunimella
		$site = Yii::app()->createController('Site');
		$criteria = $site[0]->etuSukunimiCriteria($criteria);
		//     Return order etu ja sukunimella -->

		$criteria->condition =" aktiivinen=1 and id!='".$model->tid."' ";

		// <-- Tyoryhmat
		$tt = Yii::app()->createController('Tyontekijat');
		$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
		$ids = implode(",", $tt_arr);
		if( count($tt_arr) > 0 ){
	       		$criteria->addCondition (" id IN ($ids) and id!='".$model->tid."' ");
		}
		//     Tyoryhmat -->


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
		<?php echo $form->labelEx($model,'peruutettu'); ?>
		<?php
		$list = $this->peruutettuArray();
		echo $form->dropDownList($model,'peruutettu', $list, 
		array('empty'=>'Valitse','class'=>'form-control'));
		?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'piilota_mobiilista'); ?>
		<?php 
        	$l = array(0=>'Kyllä',1=>'Ei');
		echo $form->dropDownList($model,'piilota_mobiilista', $l, 
		array('class'=>'form-control')) ?>
  </div>
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'laskutettu'); ?>
		<?php 
        	$l = array(0 => 'Ei laskutettu', 1 => 'Laskutettu');
		echo $form->dropDownList($model,'laskutettu', $l, 
		array('class'=>'form-control')) ?>
  </div>
</div>
<br>

<div class="row" id="lisapalvelut_valinta">
  <div class="col-sm-3">
		<?php echo $form->labelEx($model,'tuoteID'); ?>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->order = " nimike ";
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
  <div class="col-sm-3">
		<label><?=Yii::t('main','Valitse tuotteet ja lisäpalvelut')?></label>
		<?php
		$criteria = new CDbCriteria();
       		$criteria->order = " nimike ";
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
		<label><?=Yii::t('main','Lisäpalvelun määrä')?></label>
    		<div class="input-group">
		      <?php echo CHtml::numberField('lisapalvelu_maara','lisapalvelu_maara',array('class'=>'form-control', 'placeholder' => 'määrä')); ?>
		      <span class="input-group-btn">
		        <button class="btn btn-primary plus_lisapalvelu" type="button"><i class="fa fa-plus"></i></button>
		      </span>
		</div>
  </div>
  <div class="col-sm-3">
		<?php 
		$t = Tyontekijat::model()->findbypk($model->tid);
		if(!empty($t->gcm_reg_id)) :
		?>
  		<div class="section">
		<label><?php echo Yii::t('main','Ilmoita työntekijää viestillä'); ?></label><br>
			<input type="checkbox" name="<?=$java_prefix?>[PushNotify]" class="sw" id="<?=$java_prefix?>_PushNotify">
	    	</div>
		<?php endif; ?>
  </div>
</div>

<?php
	$criteria = new CDbCriteria();
        $criteria->order = " id DESC ";
	$criteria->condition = " tv_id!=0 AND tv_id='".$model->id."' AND tid='".$model->tid."' ";
	$mobile = Mobile::model()->find($criteria);
?>
<p>
<div class="row">
  <div class="col-sm-3">
    <div class="input-group">
      <span><?php echo Yii::t('main','Toistuva työvuoro'); ?></span>
      <span class="input-group-btn">
        <input type="checkbox" name="is_toistuva" class="sw" id="is_toistuva">
      </span>
    </div>  
  </div>
  <div class="col-sm-3">
    <div class="input-group">
      <span class="form-control"><?php echo Yii::t('main','Työerittely'); ?></span>
      <span class="input-group-btn">
        <button class="btn btn-primary uusierittely <?=(isset($mobile->id) and is_array(json_decode($mobile->tyo_erittelyt, true)))?'disabled':''?>" type="button"><i class="fa fa-plus"></i></button>
      </span>
    </div>  
  </div>
  <div class="col-sm-3">
    <div class="input-group">
      <span class="form-control"><?php echo Yii::t('main','Muistiinpano'); ?></span>
      <span class="input-group-btn">
        <button class="btn btn-primary uusimuistinpanno" type="button"><i class="fa fa-plus"></i></button>
      </span>
    </div>  
  </div>
</div>
</p>

<hr>

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

	var lp_lista = $("#lisapalvelu_lista").text().trim();
	if( lp_lista == '' ){
		$("#lisapalvelu_lista").append('<div class="col-sm-4 lisapalvelu_laatiko"><legend>Lisäpalvelut</legend>');
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
});
</script>

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
    $classCol = 'collapse';
    $toistuvaID =  '<span id="toistuvaID"></span>';

    if($toistuva){
	$tvt = ToistuvatTyovuorot::model()->findByPk($model->toistuva_id); // Toistuva modelissa on GETtoistuva_id
	if(isset($tvt->id)){
    	$pfrom = $tvt->pfrom;
    	$viikkoja = $tvt->viikkoja;
    	$viikko_paivat = json_decode($tvt->viikko_paivat, true);
    	$pto = $tvt->pto;
    	$classCol = 'collapse in';
    	$toistuvaID =  '<span id="toistuvaID">'.$model->toistuva_id.'</span>';
	}

    } else {
    	$pfrom = $today;
    }
?>

<div id="toistuvaAllsijaan"></div>
<br>
<div id="toistuvaAll">
 <div class="row">
  <div class="col-sm-12">
   <div class="<?php echo $classCol; ?> panel-footer" id="toistuva_aktiivinen">
	<legend><?php echo Yii::t('main','Toistuva työvuoro'); ?></legend>

	<?=($toistuva)? 'Ketju: '.$model->id.', Toistuva tid: '.$model->tid.', pfrom: '.$pfrom:''?>

	<div class="row" id="alkaen_loppuen">
	  <div class="col-sm-4">
		<label><?php echo Yii::t('main', 'Alkaen'); ?> </label>
		<input type="text" class="form-control datepickerFI" name="ToistuvatTyovuorot[pfrom]" id="pfrom" value="<?php echo date('d.m.Y', strtotime($pfrom)); ?>">
	  </div>
	  <div class="col-sm-4">
		<label><?php echo Yii::t('main', 'Loppuen'); ?></label>
		<input type="text" class="form-control datepickerFI" name="ToistuvatTyovuorot[pto]" id="pto" value="<?php if(!empty($pto)) echo date('d.m.Y', strtotime($pto)); ?>">
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

	<?php 
	if(isset($model->id) and $toistuva and $poista == 1 and !empty($laatikko_tid)){
		echo '<hr>
		<center><h3 class="text-danger">Poistaminen</h3></center>
		<div class="row">
		 <div class="col-sm-4">
			<legend><h4>Vain tämä päivä/henkilö</h4></legend>
			<div class="row">
			 <div class="col-sm-3">
				<div class="pull-right" style="margin-top: 7px">Pvm: </div>
			 </div>
			 <div class="col-sm-9">
				<input type="text" id="poista_tama_paiva" class="form-control datepickerFI" value="'.$laatikko_pvm.'">
			 </div>
			</div>
			<span class="btn btn-block btn-danger tvpoisto" tilanne="poista_pvm">
				Poista '.$laatiko_etusukunimi.'
			</span>
		 </div>
		 <div class="col-sm-4">
			<legend><h4>Alkaen - '.$model->pto.'</h4></legend>
			<div class="row">
			 <div class="col-sm-3">
				<div class="pull-right" style="margin-top: 7px">Alkaen: </div>
			 </div>
			 <div class="col-sm-9">
				<input type="text" id="poista_henkilo_alkaen" class="form-control datepickerFI" value="'.$today.'">
			 </div>
			</div>
			<span class="btn btn-block btn-danger tvpoisto" tilanne="poista_ketjusta_henkilo">
				Poista ketjusta '.$laatiko_etusukunimi.'<br>
			</span>
		 </div>
		 <div class="col-sm-4">
			<legend><h4>Poista ketju</h4></legend>
			<div class="row">
			 <div class="col-sm-6">
				<input type="text" class="form-control readonly" value="'.$model->pfrom.'">
			 </div>
			 <div class="col-sm-6">
				<input type="text" class="form-control readonly" value="'.$model->pto.'">
			 </div>
			</div>
			<span class="btn btn-block btn-danger tvpoisto" tilanne="poista_ketju_kokonaan">
				Poista kaikki. '.( (is_array($tyopaari) and count($tyopaari) > 0)? 'Työparit - '.(count($tyopaari)-1).'kpl' : '' ).'
			</span>
		 </div>
		</div>';
	}
	?>

	<div id="sopivatPaivat" style="display:none"></div>
   </div>
  </div>
 </div>
</div><!-- toistuvaAll -->



		</div> <!-- end modal-content -->
	</div> <!-- end modal-dialog -->

<br>

	<div class="panel-footer text-right">
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

		<div id="virheilmoitus" class="alert bg-danger" style="display:none"></div>
	</div>		



<?php $this->endWidget(); ?>


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>


<script type="text/javascript">
$(document).ready(function(){

  if($('#<?=$java_prefix?>_kohde').val() !== '')
  {
	var kohdeOn = $('#<?=$java_prefix?>_kohde option:selected').val();
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
				$('#<?=$java_prefix?>_kohde').html(data);
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


  var pfrom = '';
  var pto = '';

  $('#submitButton').click(function(){
	$('#tyovuoroot-form').submit();
	return false;
  });

  if( ('<?=$model->id?>') !== '' && ('<?=$model->toistuva_id?>') !== '0' ){
		var edellinen_pfrom = ('<?=$pfrom?>').split('.');
		var old_pfrom = new Date(+edellinen_pfrom[1]+"/"+edellinen_pfrom[0]+"/"+edellinen_pfrom[2]); //"11/21/2011"
		var todaysDate = new Date();
		if(old_pfrom.setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0)) {
			$('#pfrom').val('<?=date("d.m.Y")?>')
			$('#pfrom').after('<p class="text-danger">Ketjun Alkupäivämäärä muuttuu. Sitä aikaisemmat päivät muuttuvat yksittäisiksi työvuoroiksi.</p>');
		}
		$("#Toistuva_viikkoja").replaceWith('<input type="number" name="ToistuvatTyovuorot[viikkoja]" id="Toistuva_viikkoja" class="form-control" value="'+ $("#Toistuva_viikkoja option:selected").val() +'" readonly>');
  }

  /* on submit */
  $('#tyovuoroot-form').on('submit',function(e) {
	var pvmTarkistus = $('#submitButton').attr('pvmTarkistus');
	var toistuva = ($('#is_toistuva').bootstrapSwitch('state') === true)? true : false;

	/* <-- Tarkistetaan Aloitus/Lopetus Klo ja status */
	if( $('#<?=$java_prefix?>_status option:selected').val() === '' )
	{
		$('#<?=$java_prefix?>_status').addClass('bg-danger').focus();
		return false;
	}
	if( $('#alku').val() === '' ){
		$('#alku').addClass('bg-danger').focus();
		return false;
	}
	if( $('#loppu').val() === '' ){
		$('#loppu').addClass('bg-danger').focus();
		return false;
	}
	/*     Tarkistetaan Aloitus/Lopetus Klo ja status --> */

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
			alert('Toistuvan työvuoron lopetuspäivämäärä ei voi olla ennen toistuvan työvuoron aloituspäivämäärä');
			return false;
		}
		if( $('#pfrom').val() === '' ){
			$('#pfrom').addClass('bg-danger').focus();
			return false;
		}
		if( $('#pto').val() === '' ){
			$('#pto').addClass('bg-danger').focus();
			return false;
		}
		var valinnut_pfrom = $('#pfrom').val().split('.');
		var new_pfrom = new Date(+valinnut_pfrom[1]+"/"+valinnut_pfrom[0]+"/"+valinnut_pfrom[2]); //"11/21/2011"
		var todaysDate = new Date();
		if(new_pfrom.setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0)) {
			alert('Toistuvan työvuoron aloitus päivämäärä ei voida muokata alkamaan menneisyydestä.');
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
			return false;
		}

		// <-- Check PVM lista
		if( pvmTarkistus == 'true' ){
			var pvmTarkistus_lista = '';
			$.ajax({
			  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/pvmTarkistus_lista',
			  data:$(this).serialize(),
			  type:'POST',
			  success:function(data){
				data = JSON.parse(data);
				console.log(data);
				$('#sopivatPaivat').html('<p>' + data + '</p>').show('slow');
		   	},
			error:function(data){
				console.log(data);
		    	}
			});
			return false;
		}
	}

	// <-- tarkistetaan tietoja pituus
	var leng = $('#<?=$java_prefix?>_tietoja').val().length;

	var raja = 10000;
	if(leng > raja){
		alert('Tietoja mobiilisovellukseen kentän merkkimäärä ei voi ylittää '+raja+' rajaa');
		return false;
	}
	// tarkistetaan tietoja -->

	// <-- tarkistetaan ajaat päällekäin
	if( e.target[0].value === '')
	{
	var tid		= $('#<?=$java_prefix?>_tid').val();
	var pvm		= $('#<?=$java_prefix?>_pvm').val();
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


	var str = '';
	$('#virheilmoitus').html('').hide();

	if( '<?=$create_update?>' == 'update'){
		$.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/update4?this_id=<?=$this_id?>',
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			console.log(data);
			laatikonPaivays();
			return false;
			//window.location.reload();
	   	  },
		  error: function(xhr, status, error) {
			$('#virheilmoitus').html('Virheilmoitus: \n\n' + xhr.responseText).show();
	    	  }
		});
	}
	if( '<?=$create_update?>' == 'create'){
		$.ajax({
		  url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/create4?toistuva=' + toistuva,
		  data:$(this).serialize(),
		  type:'POST',
		  success:function(data){
			console.log(data);
			//laatikonPaivays();
			return false;
	   	  },
		  error: function(xhr, status, error) {
			$('#virheilmoitus').html('Virheilmoitus: \n\n' + xhr.responseText).show();
	    	  }
		});
	}

	e.preventDefault();

  }); /* on submit */


  function laatikonPaivays(){

	var didlink = 'did4';
	//if(parent.location.href.match(/tv3/)){ didlink = 'did3'; }
	$.ajax({
		url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/' + didlink,
		type: 'GET',
		data: { haku_from : '<?=$haku_from?>', haku_to : '<?=$haku_to?>', haku_tids : '<?=json_encode($haku_tids)?>' },
		success:function(data){
			console.log(data);
		  	
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


  // Poistaminen
  $('.tvpoisto').click(function(){
	var tilanne = $(this).attr('tilanne');
	var this_id = '<?=$this_id?>';
	var toistuva_aktiivinen = '<?=$toistuva?>';

	var todaysDate 		= new Date();

	var poista_tama_paiva 	= $("#poista_tama_paiva").val().split('.');
	poista_tama_paiva 	= new Date(+poista_tama_paiva[1]+"/"+poista_tama_paiva[0]+"/"+poista_tama_paiva[2]);
	if( tilanne == 'poista_pvm' && poista_tama_paiva.setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0) ){
		alert('Ei saa poista menneisyydestä.');
		return false;
	}
	var poista_henkilo_alkaen 	= $("#poista_henkilo_alkaen").val().split('.');
	poista_henkilo_alkaen 		= new Date(+poista_henkilo_alkaen[1]+"/"+poista_henkilo_alkaen[0]+"/"+poista_henkilo_alkaen[2]);
	if( tilanne == 'poista_ketjusta_henkilo' && poista_henkilo_alkaen.setHours(0,0,0,0) < todaysDate.setHours(0,0,0,0) ){
		alert('Ei voida olla alkamaan menneisyydestä.');
		return false;
	}

	if(toistuva_aktiivinen == true){
		if( tilanne == 'poista_pvm' )
			var r = confirm('Haluatko varmasti poistaa tämä päivä ketjusta?');
		if( tilanne == 'poista_ketjusta_henkilo' )
			var r = confirm('Haluatko varmasti poistaa alkaen: ' + $("#poista_alkaen").val() + '?');
		if( tilanne == 'poista_ketju_kokonaan' )
			var r = confirm('Poistaa kaikki ketjun kuluvat työvuorot ja työparit.');
	} else {
		var r = confirm('Haluatko varmasti poistaa?');
	}
	if(r)
	{
        $.ajax({
           url: 'poistaTv?this_id=' + this_id,
	   type:'POST',
	   data: { tilanne : tilanne, pfrom : $('#pfrom').val(), pto : $('#pto').val() },
           success: function(data){
		data = JSON.parse(data);
		window.location.reload();
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

  $('#pto, #pfrom').blur(function(){

  });

  $('#Toistuva_viikkoja').change(function(){

  });

  $('#Toistuva_viikkoja, #tyopaari').change(function(){

  });
  
  $('#ma,#ti,#ke,#to,#pe,#la,#su').on('switchChange.bootstrapSwitch', function(event, state) {

  });


  if( $('#<?=$java_prefix?>_kohde').val() !== '' ){
	var thisID = $('#<?=$java_prefix?>_kohde option:selected').val();
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

			$(".kohteen_lisatiedot").html('<span class="pull-right link avataan_lisatiedot" data-toggle="collapse" data-target="#open_kohde_'+ thisID +'">Kohteen listätietoja </span><div class="collapse" id="open_kohde_'+ thisID +'">Puh.: '+ d[7] +'<br>Sähköposti: '+ d[8] +'</div>');

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  }

  $(document).delegate("#<?=$java_prefix?>_kohde","change",function(){

	$('#<?=$java_prefix?>_status').val('3').css({"border" : "1px green solid"});
	$('#<?=$java_prefix?>_tyoajanlaatu').val('');
	$(this).removeClass('bg-danger');
	var thisID = $(this, 'option:selected').val();
	var tyo_erittelyt = '';
	var tv_id = '<?php if(isset($model->id)){ echo $model->id; } ?>';
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
			if($.isArray(d[6])){
			$.each(d[6], function( index, value ) {
			  tyo_erittelyt += '' +
				 '<div class="row">' +
				  '<div class="col-sm-11">' +
				   '<input type="text" name="Tyovuoroot[tyo_erittelyt][]" class="form-control input-sm" value="'+ value +'">' +
				  '</div>' +
				  '<div class="col-sm-1 text-right">' +
				   '<span class="link text-danger fa fa-trash poislistasta"></span>' +
				  '</div>' +
		 		 '</div>';
			});
			}
			$("#erittelynlista").html('<div class="col-sm-6 erittelynlista_laatiko"><legend>Työerittelyt</legend>' + tyo_erittelyt + '</div>');
			//    tyo_erittelyt -->

			if(d[2] !== ''){
				$('#arvioitu_kesto').html(d[2]);
			} else {
				$('#arvioitu_kesto').html('00:00');
			}

			$(".kohteen_lisatiedot").html('<span class="pull-right link avataan_lisatiedot" data-toggle="collapse" data-target="#open_kohde_'+ thisID +'">Kohteen listätietoja </span><div class="collapse" id="open_kohde_'+ thisID +'">Puh.: '+ d[7] +'<br>Sähköposti: '+ d[8] +'</div>');

	   	},
		error:function(data){
		console.log(data);
	    	}
	  });
  });
  $(".uusierittely").click(function(){
    var er_lista = $("#erittelynlista").text().trim();
    if( er_lista == '' ){
    $("#erittelynlista").append('<div class="col-sm-6 erittelynlista_laatiko"><legend>Työerittelyt</legend>');
    }
    $(".erittelynlista_laatiko").append('' +
		 '<div class="row">' +
		  '<div class="col-sm-11">' +
		   '<input type="text" name="Tyovuoroot[tyo_erittelyt][]" class="form-control input-sm">' +
		  '</div>' +
		  '<div class="col-sm-1 text-right">' +
		   '<span class="link text-danger fa fa-trash poislistasta"></span>' +
		  '</div>' +
 		 '</div>'
    );
    if( er_lista == '' ){
    $(".erittelynlista_laatiko").append('</div>');
    }
    $(".erittelynlista_laatiko input:last").focus();
  });
  $(document).delegate(".poislistasta","click",function(){
   $(this).closest(".row").remove();
  });

  $(".uusimuistinpanno").click(function(){
    var mp_lista = $("#muistiinpanolista").text().trim();
    if( mp_lista == '' ){
    $("#muistiinpanolista").append('<p><div class="row panel-footer"><div class="col-sm-12 muistiinpanolista_laatiko"><legend>Muistiinpanot</legend>');
    }

    $(".muistiinpanolista_laatiko").append('' +
		 '<div class="row">' +
		  '<div class="col-sm-11">' +
		   '<textarea name="Tyovuoroot[muistiinpano][]" class="form-control"></textarea>' +
		  '</div>' +
		  '<div class="col-sm-1 text-right">' +
		   '<span class="link text-danger fa fa-trash pois_muistiinpano"></span>' +
		  '</div>' +
 		 '</div>'
    );
    if( mp_lista == '' ){
    $(".muistiinpanolista_laatiko").append('</div></div></p>');
    }
    $(".muistiinpanolista_laatiko textarea:last").val('<?=date("d.m.Y H:i")?> - <?=Yii::app()->user->nimi?>:\n').focus();
  });
  $(document).delegate(".pois_muistiinpano","click",function(){
   $(this).closest(".row").remove();
  });

  linkkiKohteeseen();

  function linkkiKohteeseen(){
	var thisID = $('#<?=$java_prefix?>_kohde option:selected').val();
	var thisText = $('#<?=$java_prefix?>_kohde option:selected').text();
	var url = location.protocol + "//" + location.host + '/index.php/kohteet/update?id='+ thisID;
	if(thisID !== '')
	$("#kohde_url").html('<a href="'+ url +'" target="_blank">Muokkaa '+ thisText +'</a>');
	console.log(thisID);
  }

  $('#tekijanVaihdo').change(function(){
	var thisId = $('#tekijanVaihdo option:selected').val();
	$('#<?=$java_prefix?>_tid').val(thisId);
  });

  $(document).delegate(".sopiiSopivat","click",function(){
	$(this).remove();
	$('#sopivatPaivatInput').val(1);
	$('#toistuvaAll').hide('slow');
	$('#submitButton').val('Tallenna').removeAttr( "pvmTarkistus" );
	$('#toistuvaAllsijaan').html('<h3 class="alert alert-success">Toistuvien työvuorojen päivät tallennettu.<br>Paina Tallenna-painikketta lisätäksesi työvuorot työvuorolistaan.</h3>').show('slow');
  });

  $('#pto').blur(function(){
	checkToistuvaVuosi( $(this).val() );
  	$(this).removeClass('bg-danger').addClass('bg-success');
  });


  $('#is_toistuva').on('switchChange.bootstrapSwitch', function(event, state) {
	if(state === true){
		$("#toistuva_aktiivinen").addClass('in');
		if( $('#pto').val() === '' )
		$('#pto').removeClass('bg-success').addClass('bg-danger');

		$('#submitButton').val('Tarkista päivämäärät').attr("pvmTarkistus",true);
		$('#toistuva-repair-funktio').addClass('in');

	} else {
		$("#toistuva_aktiivinen").removeClass('in');
		$('#submitButton').val('Tallenna').removeAttr( "pvmTarkistus" );
		$('#toistuva-repair-funktio').removeClass('in');
	}

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

  function checkToistuvaVuosi(value){
	var cur_year = new Date().getFullYear();
	var pto_check_year = value.split(".");
	var pto_year = parseInt(pto_check_year[2]);
	if( (pto_year-cur_year) > 8 ){
		alert('Liian pitkä aikaväli. Maksimi on 8 vuotta!');
		$('#pto').val(pto_check_year[0] + '.' + pto_check_year[1] + '.' + cur_year);
		return false;
	}
  }

});
</script>
