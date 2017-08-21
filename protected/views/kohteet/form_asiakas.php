<?php
/* @var $this KohteetController */
/* @var $model Kohteet */
/* @var $form CActiveForm */

     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);


if(isset($model->id))
$model->hinta = str_replace(",",".",$model->hinta);


$site = Yii::app()->createController('Site');
$ismobile = $site[0]->check_user_agent();

if($ismobile and !empty($model->puh_nro)) {

}
?>
<div class="row">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'kohteet-form',
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">

		<?php echo $form->hiddenField($model,'gps_sijainti',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>




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
		<?php echo $form->labelEx($model,'puh_nro'); ?> <?php if(!empty($model->puh_nro)): ?><a href="tel:<?php echo $model->puh_nro; ?>">***soita***</a><?php endif; ?>
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
		array('empty'=>Yii::t('main', 'Valitse'),'class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'hinta_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv'); ?>
		<?php
        	$l = array(0=>0,10=>10,14=>14,24=>24);

        	echo $form->dropDownList($model, 'alv', $l,
		array('empty'=>'Valitse','class'=>'form-control'
		));
        	?>
		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->numberField($model,'hinta',array('size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'verot'); ?>
		<?php echo $form->numberField($model,'verot',array('size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'verot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_sis_alv'); ?>
		<?php echo $form->numberField($model,'hinta_sis_alv',array('size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'hinta_sis_alv'); ?>
	</div>

<script type="text/javascript">
$(document).ready(function(){

  laskurin();

  $("#Kohteet_alv").change(function() {
	laskurin();
  });
  $("#Kohteet_hinta").keyup(function() {
	laskurin();
  });
  $("#Kohteet_hinta_sis_alv").keyup(function() {
	var hinta_sis_alv = parseFloat($(this).val());
	var alv = parseFloat($("#Kohteet_alv").val());
	var result = hinta_sis_alv/(1+(alv/100));
	$("#Kohteet_hinta").val(result.toFixed(2));
	$("#Kohteet_verot").val((hinta_sis_alv-result).toFixed(2));
  });

  function laskurin()
  {
	var alv = parseFloat($("#Kohteet_alv").val());
	var hinta = parseFloat($("#Kohteet_hinta").val());
	var hinta_sis_alv = ((alv/100)*hinta)+hinta;
	$("#Kohteet_hinta_sis_alv").val(hinta_sis_alv.toFixed(2));
	$("#Kohteet_verot").val((hinta_sis_alv-hinta).toFixed(2));
  }

});
</script>

	<?php endif; ?>



  </div><div class="col-sm-3">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tag_id'); ?>
		<?php echo $form->textField($model,'tag_id',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tag_id'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'siivous'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id."//".$v->value] = $v->value;

        	echo $form->dropDownList($model, 'siivous', $list,
		array('empty'=>'','class'=>'form-control form-group'));
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="siivous"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'siivous'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(1=>'Kyllä',0=>'Ei');
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('empty'=>'Valitse', 'class'=>'form-control'));
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

	<div class="section fill mb5 kenella_on_avain">
		<?php echo $form->labelEx($model,'kenella_on_avain'); ?>

		<?php
      		$l = Tyontekijat::model()->findAll(array('order' => "tekijan_nimi"));
		foreach($l as $v)
		$listt[$v->id."//".$this->etuSukunimi($v->id)] = $this->etuSukunimi($v->id);

        	echo $form->dropDownList($model, 'kenella_on_avain', $listt,
		array('empty'=>'','class'=>'form-control'));
        	?>

		<?php echo $form->error($model,'kenella_on_avain'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ryhma'); ?>
	   <div class="input-group">

		<?php

		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'ryhma', $list,
		array('empty'=>Yii::t('main', 'Valitse'),'class'=>'form-control'));
		} else {
		echo 'Luo Valikko tietokannassa "Select Type = asiakas_ryhma"';
		}		
        	?>
		<span class="input-group-btn">
			<span class="btn btn-primary myBgColors muokaValiko" for="asiakas_ryhma"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'ryhma'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tarvittavien_tyontekijoiden_maara'); ?>
		<?php echo $form->numberField($model,'tarvittavien_tyontekijoiden_maara',array('maxlength'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tarvittavien_tyontekijoiden_maara'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'arvioitu_kesto'); ?>
		<?php echo $form->numberField($model,'arvioitu_kesto',array('maxlength'=>5,'class'=>'form-control', 'step' => "any")); ?>
		<?php echo $form->error($model,'arvioitu_kesto'); ?>
	</div>

<?php /*
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	  $('#Kohteet_arvioitu_kesto').mask('00:00',{
	        placeholder: "__:__"
	  });
	});
	</script>
*/ ?>

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
		<?php echo $form->labelEx($model,'tietoja'); ?>
		<?php echo $form->textArea($model,'tietoja',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tietoja'); ?>
	</div>

  </div>
</div><!-- form -->

<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors luoTallennaKohde')); ?>
	</div>

<?php $this->endWidget(); ?>


<hr>

<?php


   echo '<div class="section fill mb5">';

	$criteria = new CDbCriteria();
	$criteria->condition = " kohde_id='".$model->id."'  ";
	$kuvk = KuviaKohteesta::model()->findAll($criteria);

	$i = 0;

	foreach($kuvk as $data) {
	$i++;

	 //if(file_exists(Yii::app()->request->baseUrl.'img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$data->tiedosto))
	 //{
 	 echo '
	 <div class="col-sm-3">
	  <div class="link poistaKuva" this="'.Yii::app()->request->baseUrl.'img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$data->tiedosto.'" kuva_id="'.$data->id.'">'.Yii::t('main','poista').'</div>
	  <label>'.$data->tekijan_nimi.'<br><b>'.date("d.m.Y H:i", strtotime($data->time)).'</b></label><br>
	  <a href="../../img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$data->tiedosto.'" target="_blank">
	  <img src="../../img/uploadedfromphone/'.Yii::app()->user->domain.'/'.$data->tiedosto.'" class="img-responsive thumbnail" style="height:200px">
	  </a>';

	   if(!empty($data->kuvaus))
	   {
	    echo '
	      <label>'.Yii::t('main','Kuvaus').'</label><br>
	      '.$data->kuvaus;
	   }

	 echo '</div>';
	 //}
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
	var kuva_id = $(this).attr("kuva_id");
        $.ajax({
           url: "update?id=<?php echo $model->id; ?>",
	   type:'POST',
	   data: { "poistaTamaKuva" : forThis, kuva_id : kuva_id },
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



	var avainOn = $( "#Kohteet_avaimen_sijainti option:selected" ).val();
	if(avainOn !== '3')
	$('.kenella_on_avain').hide();


$("#Kohteet_avaimen_sijainti").change(function() {
    var thisVal = $(this).val();
	if(thisVal !== '3')
	{
		$('.kenella_on_avain').hide('slow');
		$('#Kohteet_kenella_on_avain').val('');
	} else {
		$('.kenella_on_avain').show('slow');
	}
});


});
</script>



