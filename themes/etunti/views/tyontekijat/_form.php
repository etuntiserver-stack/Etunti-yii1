<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */
/* @var $form CActiveForm */

$position = '';
$viimeinenAika = '';
if(!isset($model->id)){
	$model->app_naytta_osoitekenta = 1;
}
if(isset($model->position) and isset($model->id)){

  $si = explode("//",trim($model->position));

  	if(isset($si[0]) and !empty($si[0]))
     	  $position = $si[0];

  	if(isset($si[1]) and !empty($si[1]))
     	  $viimeinenAika = $si[1];
}

if(empty($model->position) and isset($model->id))
{
	$criteria=new CDbCriteria;
	$criteria->order = " id DESC ";
	$criteria->condition = " tid='".$model->id."' AND my_location!='' AND my_location!='GPS disabled' ";
	$m = Mobile::model()->find($criteria);

	if(isset($m->my_location))
	{
	  $explLoc = explode("**",$m->my_location);

	    if(isset($explLoc[1]))
 	    {
	   	$position = $explLoc[1];
     	  	$viimeinenAika = $m->loppui;
	 
	    } elseif(isset($explLoc[0]) and !isset($explLoc[1])){
	  	$position = $explLoc[0];
     	  	$viimeinenAika = $m->aloitan;
 	    }
	
	}
}
?>

<!-- Piilotetaan -->
<?php if( isset($from) and $from == 'tyovuorot' ) : ?>
<style>
.piilotetaan{ display: none; }
</style>
<?php endif; ?>
<!-- Piilotetaan -->


<input type="hidden" id="position" value="<?php echo $position; ?>">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyontekijat-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

<div class="row">
   <div class="col-sm-3">



	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sukunimi'); ?>
		<?php echo $form->textField($model,'sukunimi',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sukunimi'); ?>
	</div>
	
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_email'); ?>
	</div>
<?php /*
	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'salasana'); ?>
		<?php echo $form->textField($model,'salasana',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'salasana'); ?>
	</div>
*/ ?>

	<?php if(isset($model->id)) : ?>
	<div class="section fill mb5 ashidd_a" id="tunnus_lahettaminen">
		<?php echo CHtml::link(Yii::t('main', 'Lähetä tunnukset työntekijälle'), 
				array('update', 'id'=>$model->id, 'laheta_tunnukset'=>true), 
				array(
					'class' => 'btn btn-primary btn-block myBgColors',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä'),
					//'target' => '_blank'
				)
			); 
		?>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#Tyontekijat_tekijan_email').on('focus blur keydown', function() {
			$("#tunnus_lahettaminen").remove();
		});
	});
	</script>
	<?php endif; ?>

	<?php if(isset($model->id) and $model->mobiili == 0) : ?>
	<div class="section fill mb5 ashidd_a">
		<?php echo CHtml::link(Yii::t('main', 'Avaa mobiilisovellus'), 
				array('update', 'id'=>$model->id, 'avaa_mobiili'=>true), 
				array(
					'class' => 'btn btn-danger btn-block',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä'),
					//'target' => '_blank'
				)
			); 
		?>
	</div>
	<?php endif; ?>

	<?php if($laaja == 1) : ?>
	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_katuosoite'); ?>
	</div>

	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tekijan_pnumero'); ?> 
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_pnumero'); ?>
	</div>

	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_ptoimipaikka'); ?>
	</div>
	<?php endif; ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ammattinimike'); ?>
		<?php echo $form->textField($model,'ammattinimike',array('size'=>50,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'ammattinimike'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>

	   <div class="input-group">
		<?php 
		$a = Valikkoot::model()->findAll(" select_type='aktiivinen' ");
        	$tal = array();
		foreach($a as $v){
		$exV = explode("/",$v->value);
		   if(isset($exV[0]) and isset($exV[1]))
		   $tal[$exV[1]] = $exV[0];
		}

		echo $form->dropDownList($model,'aktiivinen', $tal, array('class'=>'form-control'));
		?>
		<span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="aktiivinen"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<?php
	$hasShifts = false;
	if(isset($model->id)){
		$hasShifts = $this->hasUpcomingShifts($model->id);
	}
	?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'naytta_tyovuorossa'); ?>
		<?php 
        	$tal = array(
			1=>'Kyllä'
		);
		if($hasShifts === false or !isset($model->id)) {
			$tal[0] = "Ei";
		}

		echo $form->dropDownList($model,'naytta_tyovuorossa', $tal, 
		array('class'=>'form-control')) ?>
		<?=($hasShifts === true)? '<p class="text-danger">Työntekijällä on työvuoroja.</p>' : ''?>
		<?php echo $form->error($model,'naytta_tyovuorossa'); ?>
	</div>

<?php if(isset($model->id)) : ?>
<script type="text/javascript">
$(document).ready(function(){

 $('#Tyontekijat_aktiivinen').change( function() {
  	var thisVal = $(this).val();
	if( thisVal != 1)
	{
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/tyontekijat/check_tyovuorot?id=<?php echo $model->id; ?>",
           success: function(data){
		data = JSON.parse(data);
		console.log(data);
		if(data !== '')
		alert('Työntekijällä on tulevaisuudessa merkittyjä työvuoroja. Oletko varma, että haluat muuttaa työntekijän passiiviseksi?');
           }
        });
	}
 });

});
</script>
<?php endif; ?>



   </div>
   <div class="col-sm-3">


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laiten_puh'); ?> <?php if(!empty($model->laiten_puh)): ?><a href="tel:<?php echo $model->laiten_puh; ?>">***soita***</a><?php endif; ?>
		<?php echo $form->textField($model,'laiten_puh',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'laiten_puh'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_puh'); ?> <?php if(!empty($model->tekijan_puh)): ?><a href="tel:<?php echo $model->tekijan_puh; ?>">***soita***</a><?php endif; ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_puh'); ?>
	</div>

	<?php if($laaja == 1) : ?>
	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); 	?>
		<?php echo $form->error($model,'tekijan_henkilotunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'online_varauksen_valmina'); ?>
		<?php 
        	$tal = array(0=>'Ei',1=>'Kyllä');
		echo $form->dropDownList($model,'online_varauksen_valmina', $tal, 
		array('class'=>'form-control','id'=>'osoite')) ?>
		<?php echo $form->error($model,'online_varauksen_valmina'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'onlinevaraus_tuotteet'); ?>

		<?php
		$list = array();
	       	$criteria = new CDbCriteria();
	       	$criteria->condition = " nayta_sivuilla=1 and kategoria LIKE '%onlinevaraus%' ";
	       	$criteria->order = " nimike ";
		$onlineTuotteet = TuotteetPalvelut::model()->findAll($criteria);

		echo '<select class="mult" name="Tyontekijat[onlinevaraus_tuotteet][]" multiple>';
		foreach($onlineTuotteet as $item){
		    if(is_array(json_decode($model->onlinevaraus_tuotteet, true)) and in_array($item->id, json_decode($model->onlinevaraus_tuotteet, true)))
			echo '<option value="'.$item->id.'" selected>'.$item->nimike.'</option>';
		    else
			echo '<option value="'.$item->id.'">'.$item->nimike.'</option>';
		}
		echo '</select>';
        	?>
		<?php echo $form->error($model,'onlinevaraus_tuotteet'); ?>
	</div>
	<?php endif; ?><!--Laaja-->

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyo_toimialue'); ?>

	   <div class="input-group">
		<?php
		$exists = Valikkoot::model()->find(" select_type='tyo_toimialue' ");
		if(!isset($exists->id))
		{
		    $valiko = new Valikkoot;
		    $valiko->select_type = 'tyo_toimialue';
		    $valiko->value = 'Test';
		    $valiko->save();
		}

      		$l = Valikkoot::model()->findAll(" select_type='tyo_toimialue' ",array('order' => "select_type"));
		$arr = json_decode($model->tyo_toimialue);

		echo '<select name="tyo_toimialue[]" class="mult form-control" multiple title="Valitse">';
		foreach($l as $val)
		{
			if(is_array($arr) and in_array($val->value,$arr))
		    		echo '<option value="'.$val->value.'" selected>'.$val->value.'</option>';
			elseif(!is_array($arr) and $val->value == $model->tyo_toimialue)
		    		echo '<option value="'.$val->value.'" selected>'.$val->value.'</option>';
			else
		    		echo '<option value="'.$val->value.'">'.$val->value.'</option>';
		}
		echo '</select>';
/*
		foreach($l as $v)

		$list[$v->value] = $v->value;

        	echo $form->dropDownList($model, 'tyo_toimialue', $list,
		array('empty'=>'','class'=>'selectpicker col-sm-9', 'multiple'=>'yes'));
*/
        	?>
		<span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="tyo_toimialue"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'tyo_toimialue'); ?>
	</div>

	<?php
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
	?>

	<?php /* if( $site[0]->checkOikeusFields($checkOikeus) == 1 ) : */ ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoryhma'); ?>

		<?php

	       	$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = "select_type='tyoryhma'";
		$l = Valikkoot::model()->findAll($criteria);

		$ryhmat = array();
		if(is_array(json_decode($model->tyoryhma)))
			$ryhmat = json_decode($model->tyoryhma);
		else
			array_push($ryhmat, $model->tyoryhma);


		if(isset($l[0]))
		{
			echo '<select class="mult" name="Tyontekijat[tyoryhma][]" multiple id="Tyontekijat_tyoryhma">';
			foreach($l as $ryhma){
			    if(in_array($ryhma->value, $ryhmat))
				echo '<option value="'.$ryhma->value.'" selected>'.$ryhma->value.'</option>';
			    else
				echo '<option value="'.$ryhma->value.'">'.$ryhma->value.'</option>';
			}
			echo '</select>';
		}
        	?>
		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>
	<?php /* endif; */ ?>

	<?php if(isset($model->id) and $model->mobiili == 1) : ?>
	<legend><?=Yii::t('main', 'APP mobiilisovellus')?></legend>
	<div class="section fill mb5 ashidd_a">
		<?php echo CHtml::link(Yii::t('main', 'Sulje mobiilisovellus'), 
				array('update', 'id'=>$model->id, 'sulje_mobiili'=>true), 
				array(
					'class' => 'btn btn-primary btn-block myBgColors',
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Lähetä'),
					//'target' => '_blank'
				)
			); 
		?>
	</div>
	<?php endif; ?>
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
   <div class="col-sm-3">

<!--
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoehtosopimus'); ?>
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='tyoehtosopimus' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->value] = $v->value;

        	echo $form->dropDownList($model, 'tyoehtosopimus', $list,
		array('empty'=>'','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'tyoehtosopimus'); ?>
	</div>
-->

	<?php if($laaja == 1) : ?>
	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tekijan_pankkitili'); ?>
		<?php echo $form->textField($model,'tekijan_pankkitili',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_pankkitili'); ?>
	</div>

	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tekijan_konttori'); ?>
		<?php echo $form->textField($model,'tekijan_konttori',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_konttori'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kortit'); ?>

	   <div class="input-group">
		<?php
		$a = Valikkoot::model()->findAll(" select_type='kortit' ");
		$check = explode("##***",$model->kortit);

		echo '<select name="kortit[]" id="kortit" class="form-control mult" multiple title="Valitse">';
		  foreach($a as $val){
		    	$on = false;
		   foreach($check as $c)
		   {
		     if(trim($c) == trim('kortti_'.$val->value))
		     {
		    	echo '<option value="kortti_'.$val->value.'" selected>'.$val->value.'</option>';
		    	$on = true;
		     }
		   }
		    if($on == false)
		    echo '<option value="kortti_'.$val->value.'">'.$val->value.'</option>';
		  }
		echo '</select>';
		?>
		<span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="kortit"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'kortit'); ?>
	</div>

	<br>
	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'ayjasenyys'); ?>
		<?php echo $form->checkbox($model,'ayjasenyys',array('size'=>10,'maxlength'=>10,'class'=>'sw')); ?>
		<?php echo $form->error($model,'ayjasenyys'); ?>
	</div>
	<?php endif; ?><!--Laaja-->

	<?php 
	if(!empty($model->kortit_voimassaolo))
		echo '<textarea id="tallennettuVoimassaolot" style="display:none">'.$model->kortit_voimassaolo.'</textarea>';

	?>
	<div id="kortitVoimassaolot"></div>


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/select_valiko.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>

<script type="text/javascript">
$(document).ready(function(){

 kortit();

 function kortit()
 {
	try
	{
	   var tallennettuVoimassaolot = JSON.parse($('#tallennettuVoimassaolot').val());
	}
	catch(e)
	{
	   var tallennettuVoimassaolot = [];
	}


	var data	= '';
	var value 	= '';
	var $el=$("#kortit");
	$el.find('option:selected').each(function(){

	value 	= '';
	if(tallennettuVoimassaolot[$(this).val()])
	{
	    value 	= tallennettuVoimassaolot[$(this).val()];
	    var dString = value.split(".");
	    var d1 	= new Date(parseInt(dString[2]), parseInt(dString[1]), parseInt(dString[0]));
	    var d2 	= new Date();
	    var thisClass = '';

	    if( d2.getTime() > d1.getTime() )
	    thisClass = "btn-danger";
	}

	    data += '<div class="section fill mb5"><label>'+$(this).text()+' voimassaoloaika' +
		     '</label><input type="text" name="kortitVoimassaolo['+$(this).val()+']" class="form-control mask '+thisClass+'" value="'+value+'">'+
		    '</div>';
	});

	$('#kortitVoimassaolot').html(data);

	$('.mask').mask('00.00.0000',{
        	placeholder: "pp.kk.vvvv"
	});
 }


 $(".mult").multiselect({

	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
 }); 


 $('#kortit').change( function() {
  	kortit();
 });


});
</script>




   </div><div class="col-sm-3">

	<div class="section fill mb5">
   <div class="pull-right">
	<?php if(isset($model->id)): ?>
	<div class="section fill mb5">
		<?php
		$filepath = dirname(Yii::app()->getBasePath())."/img/tekijat/".Yii::app()->user->domain."/".$model->id.".jpg";
		if (file_exists($filepath)){
		   $imageData = base64_encode(file_get_contents($filepath));
		   $src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;
		   echo '<img src="'.$src.'" class="img-thumbnail"><br>
			<p><span class="link poistaKuva text-warning" link="'.Yii::app()->basePath.'/../img/tekijat/'.Yii::app()->user->domain.'/'.$model->id.'.jpg" request="update?id='.$model->id.'">'.Yii::t("main", "poista kuva").'</span></p>';
		} else {
		   echo '<img src="'.Yii::app()->request->baseUrl.'/img/tekijat/noname.jpg" class="img-thumbnail">';
		}
		?>		
	</div>
	<?php endif; ?>
   </div>
	</div>

	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tyontekijan_numero'); ?>
		<?php echo $form->numberField($model,'tyontekijan_numero',array('size'=>60,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyontekijan_numero'); ?>
	</div>

	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'gcm_reg_id'); ?>
		<?php echo $form->textField($model,'gcm_reg_id',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'gcm_reg_id'); ?>

	</div>

	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'position'); ?>
		<?php echo $form->textField($model,'position',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'position'); ?>
	</div>

   </div>



</div><!-- form -->

<hr>

<div class="row form">
   <div class="col-sm-6">

	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tekijan_tietoja'); ?>
		<?php echo $form->textArea($model,'tekijan_tietoja',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_tietoja'); ?>
	</div>

	<div class="section fill mb5 piilotetaan">
		<?php echo $form->labelEx($model,'tekijan_muisti'); ?>
		<?php echo $form->textArea($model,'tekijan_muisti',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_muisti'); ?>
	</div>

<?php
	$tas = '';
	if(isset(Yii::app()->user->adminPaketti))
	$tas = explode(",",Yii::app()->user->adminPaketti);
?>
	<?php if(isset(Yii::app()->user->adminID) and in_array('4',$tas)) : ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tietoja_onlinevarauksen'); ?>
		<?php echo $form->textArea($model,'tietoja_onlinevarauksen',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja_onlinevarauksen'); ?>
	</div>
	<?php endif; ?>

   </div><div class="col-sm-6">

<label><?php echo Yii::t('','Viimeinen sijainti kartalla').' '.$viimeinenAika; ?> </label>




<!DOCTYPE html>
<html>
  <head>
    <style>
      #map {
        width: 100%;
        height: 400px;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
      function initMap() {
	var Mypos = document.getElementById("position").value.split("/");
	console.log(Mypos)
	if(!Mypos[0]){ return false; }
        var uluru = {lat: parseFloat(Mypos[0]), lng: parseFloat(Mypos[1])};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,

          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCq7M2XrDo8cr43lu1wohJ4rZAEGIyAdsw&callback=initMap">
    </script>
  </body>
</html>



   </div>
</div>

<hr>
<!-- Muistiinpanot -->
<div class="row">
  <div class="col-sm-6">
	<div class="section fill mb5">
	    <div class="input-group">
	      <span class="form-control"><?php echo Yii::t('main','Muistiinpano'); ?></span>
	      <span class="input-group-btn">
	        <button class="btn btn-primary uusimuistinpanno" type="button"><i class="fa fa-plus"></i></button>
	      </span>
	    </div>  
	    <br>
		<div id="muistiinpanolista">
		 <?php if(is_array(json_decode($model->muistiinpano, true))): ?>
		 <div class="row"><div class="col-sm-12 muistiinpanolista_laatiko">
		 <legend><?php echo Yii::t('main','Muistiinpanot'); ?></legend>
		 <?php foreach(json_decode($model->muistiinpano, true) as $k => $v): ?>
		 <div class="row">
		  <div class="col-sm-11">
		   <?php if( isset($mobile->id) ) : ?>
		    <textarea name="Tyontekijat[muistiinpano][]" class="form-control" readonly><?=$v?></textarea>
		   <?php else: ?>
		    <textarea name="Tyontekijat[muistiinpano][]" class="form-control"><?=$v?></textarea>
		   <?php endif; ?>
		  </div>
		  <div class="col-sm-1 text-right">
			<span class="link text-danger fa fa-trash pois_muistiinpano"></span>
		  </div>
		 </div>
		 <?php endforeach; ?>
		 </div></div><!--row-->
		 <?php endif; ?>
		</div>

	</div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function(){

  $(".uusimuistinpanno").click(function(){
    var mp_lista = $("#muistiinpanolista").text().trim();
    if( mp_lista == '' ){
    $("#muistiinpanolista").append('<div class="row"><div class="col-sm-12 muistiinpanolista_laatiko"><legend>Muistiinpanot</legend>');
    }

    $(".muistiinpanolista_laatiko").append('' +
		 '<div class="row">' +
		  '<div class="col-sm-11">' +
		   '<textarea name="Tyontekijat[muistiinpano][]" class="form-control"></textarea>' +
		  '</div>' +
		  '<div class="col-sm-1 text-right">' +
		   '<span class="link text-danger fa fa-trash pois_muistiinpano"></span>' +
		  '</div>' +
 		 '</div>'
    );
    if( mp_lista == '' ){
    $(".muistiinpanolista_laatiko").append('</div></div>');
    }
    $(".muistiinpanolista_laatiko textarea:last").val('<?=date("d.m.Y H:i")?> - <?=Yii::app()->user->nimi?>:\n').focus();
  });

  $(document).delegate(".pois_muistiinpano","click",function(){
   $(this).closest(".row").remove();
  });

});
</script>
<!-- Muistiinpanot //-->

<br>

	<div class="buttons">
	<?php if(!isset($model->id)): ?>
		<?php echo CHtml::submitButton('Luo' ,array('class'=>'btn btn-primary myBgColors')); ?>
	<?php endif; ?>
	</div>

<?php $this->endWidget(); ?>


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

  $('#Tyosuhdet_loppu').blur(function() {
	alert('Olet merkinnyt työsuhteen päättyväksi. Viimeisen palkanmaksun jälkeen tulisi poistaa kaikki työntekijän tiedot, joita ei lain mukaan tarvitse säilyttää. Näitä tietoja ovat muun muassa palkka- ja verotiedot.');
  });

  $('#Tyontekijat_aktiivinen').change(function() {
	alert('Muista täyttää työsuhteen päättymispäivämäärä.');
  });
  $(".sw").bootstrapSwitch({
	size: "small",
	onColor: "success",
	offColor: "warning",
	onText: "Kyllä",
	offText: "Ei"
  });

$(".poistaKuva").click(function() {

    var request = $(this).attr("request");
    var thisLink = $(this).attr("link");

	if( confirm('Haluatko varmaasti poista?') )
	{
        $.ajax({
           url: request,
	   type:'POST',
	   data: { "poista_kuva" : true, link : thisLink },
           success: function(data){
		window.location.href=request;
           }
        });
	}
});


});

</script>




<?php
/*

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_lanka_puh'); ?>
		<?php echo $form->textField($model,'tekijan_lanka_puh',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_lanka_puh'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_kulunvalvonta'); ?>
		<?php echo $form->textField($model,'tekijan_kulunvalvonta',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_kulunvalvonta'); ?>
	</div>



*/

