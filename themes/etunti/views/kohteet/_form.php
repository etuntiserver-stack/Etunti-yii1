<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

if(isset($model->id))
$model->hinta = str_replace(",",".",$model->hinta);
?>
<div class="row">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kohteet-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakas_id'); ?>
    		<?php 
       		$criteria = new CDbCriteria();
		//$criteria->select = " COALESCE(NULLIF(yhteyshenkilo,yhteyshenkilo),'gg') AS yht ";
		$criteria->order = " yhteyshenkilo ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="Kohteet[asiakas_id]" class="form-control" id="Kohteet_asiakas_id">';

		if(isset($model->asiakas_id) and !empty($model->asiakas_id))
		{
        	  $aon = Asiakkaat::model()->findbypk($model->asiakas_id);
		  if(isset($aon->id))
		  {
		  if(!empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->id.'">'.$aon->yrityksen_nimi.'</option>';
		  elseif(empty($aon->yhteyshenkilo) and empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->id.'">nimet puutuu '.$aon->id.'</option>';
		  else
		    echo '<option value="'.$aon->id.'">'.$aon->yhteyshenkilo.' ID:'.$aon->id.'</option>';
		  }

		} else {
	        echo '<option></option>';
		}

		foreach($a as $aa)
		{
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->id.'">'.$aa->yrityksen_nimi.'</option>';
		  elseif(empty($aa->yhteyshenkilo) and empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->id.'">nimet puutuu '.$aa->id.'</option>';
		  else
		    echo '<option value="'.$aa->id.'">'.$aa->yhteyshenkilo.' ID:'.$aa->id.'</option>';
		}
		echo '</select>';
		?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'etu_suku_nimet'); ?>
		<?php echo $form->textField($model,'etu_suku_nimet',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'etu_suku_nimet'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'pnumero'); ?>
		<?php echo $form->textField($model,'pnumero',array('size'=>7,'maxlength'=>7,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>72,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puh_nro'); ?>
		<?php echo $form->textField($model,'puh_nro',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puh_nro'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'toimipaikka'); ?>
	</div>


	<?php if(in_array('3',$tas)) : ?>
	<div class="section fill mb5">
	<legend><?php echo Yii::t('main','Laskutus'); ?></legend>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_tyyppi'); ?>
		<?php
		$list = array(1=>'tunti',2=>'kk',3=>'kpl');
        	echo $form->dropDownList($model, 'hinta_tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'hinta_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->numberField($model,'hinta',array('size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>
	<?php endif; ?>



  </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tag_id'); ?>
		<?php echo $form->textField($model,'tag_id',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tag_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php 

	    function getlatlong($address)
	    {
	        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=true';
	        $json = @file_get_contents($url);
	        $data = json_decode($json);
	        if ($data->status == "OK")
	            return $data;
	        else
	            return false;
	    }
	
	        $latAuto = '';
	        $lngAuto = '';
	    	$coordinates = getlatlong($model->osoite);
		if(isset($coordinates->results[0]->geometry->location->lat))
	        $latAuto = '('.$coordinates->results[0]->geometry->location->lat.',';
		if(isset($coordinates->results[0]->geometry->location->lng))
	        $lngAuto = $coordinates->results[0]->geometry->location->lng.')';
	
    		//print_r($coordinates);

		echo '<label>GPS-sijainti '.$latAuto.$lngAuto.'</label>';
		?>
		<?php echo $form->textField($model,'gps_sijainti',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'gps_sijainti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'siivous'); ?>

	   <div class="form-inline">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id."//".$v->value] = $v->value;

        	echo $form->dropDownList($model, 'siivous', $list,
		array('empty'=>'','class'=>'form-control form-group'));
        	?>
		<span class="btn btn-primary myBgColors muokaValiko" for="siivous"><i class="fa fa-pencil-square-o"></i></span>
	   </div>

		<?php echo $form->error($model,'siivous'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(1=>'Kyllä',0=>'Ei');
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'avain'); ?>
		<?php echo $form->textField($model,'avain',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'avain'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'avaimen_sijainti'); ?>

		<?php
		$list = array(
			1=>Yii::t('main', 'Asiakas'),
			2=>Yii::t('main', 'Toimisto'),
			3=>Yii::t('main', 'Työntekijä')
		);
        	echo $form->dropDownList($model, 'avaimen_sijainti', $list,
		array('empty'=>'','class'=>'form-control'));
        	?>

		<?php echo $form->error($model,'avaimen_sijainti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kenella_on_avain'); ?>

		<?php
      		$l = Tyontekijat::model()->findAll(array('order' => "tekijan_nimi"));
		foreach($l as $v)
		$listt[$v->id."//".$v->tekijan_nimi] = $v->tekijan_nimi;

        	echo $form->dropDownList($model, 'kenella_on_avain', $listt,
		array('empty'=>'','class'=>'form-control'));
        	?>

		<?php echo $form->error($model,'kenella_on_avain'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ryhma'); ?>
	   <div class="form-inline">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'ryhma', $list,
		array('empty'=>'Valitse toimialue','class'=>'form-control'));
		} else {
		echo 'Luo Valikko tietokannassa "Select Type = asiakas_ryhma"';
		}		
        	?>
		<span class="btn btn-primary myBgColors muokaValiko" for="asiakas_ryhma"><i class="fa fa-pencil-square-o"></i></span>
	   </div>

		<?php echo $form->error($model,'ryhma'); ?>
	</div>

  </div><div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aikataulu'); ?>
		<?php echo $form->textArea($model,'aikataulu',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'aikataulu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinnoittelu'); ?>
		<?php echo $form->textArea($model,'hinnoittelu',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'hinnoittelu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'muut'); ?>
		<?php echo $form->textArea($model,'muut',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'muut'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'toimenpiteet'); ?>
		<?php echo $form->textArea($model,'toimenpiteet',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'toimenpiteet'); ?>
	</div>

	<div class="section fill mb5">
    <?php
	$i = 0;
	foreach(array_reverse(glob('tiedostot/kohteet/'.Yii::app()->user->domain.'/tyonkuvaukset/'.$model->id.'_*.*')) as $file) {
	$i++;
	$explNimi = explode("/",$file);
 	echo '
	<div class="row">
 	  <div class="col-sm-4">
	<label>'. Yii::t('main', 'Työnkuvaus').'</label>
	<div class="form-inline" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTyonkuvaus" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div>
	  &nbsp;&nbsp;&nbsp;<a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
	 </div>
	</div>
	<br>
	';
	$kuvat[$i] = $file;
	}
   ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

  </div>
</div><!-- form -->

<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>

<br>


<div class="row">
  <div class="col-sm-6 pull-right">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Työnkuvaus'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_tyonkuvaus" id="tiedostoUP" placeholder="Valitse tiedosto..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="Lataa" class="btn btn-primary myBgColors" />
	</span>
    </div>
  </form>
 </div>
</div>



<hr>

<?php

   if(isset($_POST['poistaTamaKuva']))
   {
	unlink($_POST['poistaTamaKuva']);
   }

   echo '<div class="section fill mb5">';
	$i = 0;
	foreach(array_reverse(glob(Yii::app()->basePath."/../img/uploadedfromphone/".Yii::app()->user->domain."/".$model->id."_*.*")) as $file) {
	$i++;
	$explNimi = explode("/",$file);
	$exlEndNimi = explode("_",end($explNimi));
	$tekija = '';
	if(isset($exlEndNimi[1]))
	{
		$tt = Tyontekijat::model()->findbypk($exlEndNimi[1]);
		if(isset($tt->id))
		$tekija = $tt->tekijan_nimi;
	}
 	echo '
	<div class="col-sm-3">
	  <div class="link poistaKuva" this="'.$file.'">'.Yii::t('main','poista').'</div>
	  <label>'.$tekija.'</label><br>
	  <a href="../../img/uploadedfromphone/'.Yii::app()->user->domain.'/'.end($explNimi).'" target="_blank">
	  <img src="../../img/uploadedfromphone/'.Yii::app()->user->domain.'/'.end($explNimi).'" class="img-responsive thumbnail" style="height:200px">
	  </a>
	</div>
	';
	}
   echo '</div>';
?>

</div></div>

<script type="text/javascript">
$(document).ready(function(){


// Send form by ajax
$('#Kohteet_asiakas_id').change(function(){

   var thisVal = $(this).val();

   $.ajax({
      url: 'autotaytaminen?id='+thisVal,
      //type: "POST",
      //data: { index_ajax : "true" },
      success: function(data){
	  console.log(data);
	  var sp = data.split("//");
	  $("#Kohteet_etu_suku_nimet").val(sp[0]);
	  //$("#Kohteet_kaupunki").val(sp[2]);
	  //$("#Kohteet_pnumero").val(sp[3]);
	  $("#Kohteet_email").val(sp[3]);
	  $("#Kohteet_puh_nro").val(sp[4]);

	  if(sp[6] != 0)
	  {
	  var ryhma = sp[5].split("-");
	  $("#Kohteet_ryhma option:selected").val(ryhma[0]);
	  $("#Kohteet_ryhma option:selected").text(ryhma[1]);
	  }
      }
   });

});



$(".poistaKuva").click(function(){
	var forThis = $(this).attr("this");
        $.ajax({
           url: "update?id=<?php echo $model->id; ?>",
	   type:'POST',
	   data: { "poistaTamaKuva" : forThis },
           success: function(data){
		//console.log(data)
	    	window.location.reload();
           }
        });
});



});
</script>


<?php
    $lat = '';
    $lng = '';

    if(!empty($model->gps_sijainti))
    {
	$ex = explode(",", $model->gps_sijainti);
	if(isset($ex[1]))
	{
	        $lat = $ex[0];
	        $lng = $ex[1];
	}

    }

?>

    <input type="hidden" id="lat" value="<?php echo $lat; ?>">
    <input type="hidden" id="lng" value="<?php echo $lng; ?>">

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
    var lat = parseFloat(document.getElementById('lat').value);
    var lng = parseFloat(document.getElementById('lng').value);

    var myLatlng = new google.maps.LatLng(lat, lng);
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


});
</script>



