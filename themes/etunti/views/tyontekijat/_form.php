<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */
/* @var $form CActiveForm */

$position = '';
$viimeinenAika = '';

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

<input type="hidden" id="position" value="<?php echo $position; ?>">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyontekijat-form',
	'enableAjaxValidation'=>false,
)); ?>

<div class="row">
   <div class="col-sm-3">

	<?php echo $form->errorSummary($model); ?>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>
<!--
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sukunimi'); ?>
		<?php echo $form->textField($model,'sukunimi',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sukunimi'); ?>
	</div>
-->
	
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'salasana'); ?>
		<?php echo $form->textField($model,'salasana',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'salasana'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_katuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_pnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_ptoimipaikka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>

	   <div class="input-group">
		<?php 
		$a = Valikkoot::model()->findAll(" select_type='aktiivinen' ");
        	$tal = '';
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

   </div>
   <div class="col-sm-3">


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laiten_puh'); ?>
		<?php echo $form->textField($model,'laiten_puh',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'laiten_puh'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_henkilotunnus'); ?>
		<?php 

	// <-- Oikeudet
	   $checkOikeus = "henkilotunnukset_2_".Yii::app()->user->adminStatus;
	   $site = Yii::app()->createController('Site');
	   $vastaus = $site[0]->checkOikeusFields($checkOikeus);
	//  Oikeudet -->

		if($vastaus == 0)
		echo $form->passwordField($model,'tekijan_henkilotunnus',array('size'=>20,'maxlength'=>20,'class'=>'form-control', 'readonly'=>'yes')); 
		else
		echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tekijan_henkilotunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_puh'); ?>
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
		<?php echo $form->labelEx($model,'tyoryhma'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='tyoryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->value] = $v->value;

        	echo $form->dropDownList($model, 'tyoryhma', $list,
		array('empty'=>'','class'=>'form-control'));
        	?>
		<span class="input-group-btn">
		  <span class="btn btn-primary myBgColors muokaValiko" for="tyoryhma"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>


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

		echo '<select name="Tyontekijat[tyo_toimialue][]" class="mult form-control" multiple title="Valitse">';
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

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_pankkitili'); ?>
		<?php echo $form->textField($model,'tekijan_pankkitili',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_pankkitili'); ?>
	</div>

	<div class="section fill mb5">
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


	<?php 
	if(!empty($model->kortit_voimassaolo))
		echo '<textarea id="tallennettuVoimassaolot" style="display:none">'.$model->kortit_voimassaolo.'</textarea>';

	?>
	<div id="kortitVoimassaolot"></div>





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
		$filename = Yii::app()->request->baseUrl."/img/tekijat/".Yii::app()->user->domain."/".$model->id.".jpg";
		if (file_exists(Yii::app()->basePath."/../img/tekijat/".Yii::app()->user->domain."/".$model->id.".jpg"))
		   echo '<img src="'.$filename.'" class="img-thumbnail">';
		else
		   echo '<img src="'.Yii::app()->request->baseUrl.'/img/tekijat/noname.jpg" class="img-thumbnail">';
		?>		
	</div>
	<?php endif; ?>
   </div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'gcm_reg_id'); ?>
		<?php echo $form->textField($model,'gcm_reg_id',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'gcm_reg_id'); ?>

	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'position'); ?>
		<?php echo $form->textField($model,'position',array('size'=>60,'maxlength'=>500,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'position'); ?>
	</div>


	<br>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ayjasenyys'); ?>
		<?php echo $form->checkbox($model,'ayjasenyys',array('size'=>10,'maxlength'=>10,'class'=>'sw')); ?>
		<?php echo $form->error($model,'ayjasenyys'); ?>
	</div>

   </div>



</div><!-- form -->

<hr>

<div class="row form">
   <div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_tietoja'); ?>
		<?php echo $form->textArea($model,'tekijan_tietoja',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_tietoja'); ?>
	</div>

	<div class="section fill mb5">
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
      #map-canvas {
        width: 100%;
        height: 400px;
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script>

window.initialize = function() {
    var Mypos = document.getElementById("position").value.split("/");
    var myLatlng = new google.maps.LatLng(Mypos[0], Mypos[1]);
    var mapCanvas = document.getElementById('map-canvas');
    var mapOptions = {
        center: myLatlng,          
        zoom: 14,
    }
    var map = new google.maps.Map(mapCanvas, mapOptions);
    var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title:"123"
      });
    var latLng = marker.getPosition(); 
    map.setCenter(latLng);

  }

  google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>


   </div>
</div>


<br>

	<div class="buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>


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
		//console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */

  $(".sw").bootstrapSwitch({
	size: "small",
	onColor: "success",
	offColor: "warning",
	onText: "Kyllä",
	offText: "Ei"
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

