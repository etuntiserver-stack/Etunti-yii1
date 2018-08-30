<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */
/* @var $form CActiveForm */
/*
$asiakasnumero = '';
if(!isset($model->id)){
$nextnum = Asiakkaat::model()->find(array('order'=>'id DESC'));
$asiakasnumero = 'nro. '.($model->id+1).' on vapaa';
} else {
$asiakasnumero = 'voidaan käyttää oleva ID numero';
}
*/
     $tas = array();
   if(isset(Yii::app()->user->adminPaketti)) 
     $tas = explode(",",Yii::app()->user->adminPaketti);

   $asetukset = Asetukset::model()->findbypk(1);

?>

<style>
.hidd,.ashidd,.ashidd_a{
	display:none;
}
</style>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakkaat-form',
	'enableAjaxValidation'=>false, // ala laita true, saat monta asiakaita update aikana netvisorissa
)); ?>

<h2>Suodattimet</h2>

<div class="row">
  <div class="col-sm-3">
	<div class="section fill mb5">
		<?php
		$arr = array('henkilo' => 'Yksityishenkilö', 'yritys' => 'Yritys', 'kaikki' => 'Kaikkki');
		?>
		<?php echo $form->labelEx($model,'filter_tyyppi'); ?>
		<?php echo $form->dropDownList($model, 'filter_tyyppi', $arr, 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'filter_tyyppi'); ?>
	</div>
  </div>
  <div class="col-sm-3">
	<div class="section fill mb5">
		<?php
		$criteria=new CDbCriteria;
		$criteria->group = "kaupunki";
		$criteria->condition = " 
			kaupunki!=''
		";
		?>
		<?php echo $form->labelEx($model,'filter_postitoimipaikka'); ?>
		<?php echo $form->dropDownList($model, 'filter_postitoimipaikka', CHtml::listData(Asiakkaat::model()->findAll($criteria), 'kaupunki', 'kaupunki'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'filter_postitoimipaikka'); ?>
	</div>
  </div>
  <div class="col-sm-3">
	<div class="section fill mb5">
		<?php
		$criteria=new CDbCriteria;
		$criteria->group = "tyoryhma";
		$criteria->condition = " 
			tyoryhma!='' and tyoryhma IS NOT NULL
		";
		?>
		<?php echo $form->labelEx($model,'filter_tyoryhma'); ?>
		<?php echo $form->dropDownList($model, 'filter_tyoryhma', CHtml::listData(Asiakkaat::model()->findAll($criteria), 'tyoryhma', 'valikkotyoryhma'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'filter_tyoryhma'); ?>
	</div>
  </div>
  <div class="col-sm-3">
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'filter_asiakasryhma'); ?>
		<?php
		$criteria=new CDbCriteria;
		$criteria->order = "value";
		$criteria->condition = " 
			select_type='asiakas_ryhma_real'
		";
		?>
		<?php echo $form->labelEx($model,'filter_asiakasryhma'); ?>
		<?php echo $form->dropDownList($model, 'filter_asiakasryhma', CHtml::listData(Valikkoot::model()->findAll($criteria), 'id', 'value'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'filter_asiakasryhma'); ?>
	</div>
  </div>
</div>

<hr>

<div class="row">
  <div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Asiakkaan tiedot'); ?></h3></legend>


	<div class="section fill mb5 tyyppi">
		<?php echo $form->labelEx($model,'tyyppi'); ?> <input type="checkbox" name="Check[tyyppi]">
		<?php
		$list = array('henkilo'=>Yii::t('main', 'Yksityishenkilö'), 'yritys'=>Yii::t('main', 'Yritys'));
        	echo $form->dropDownList($model, 'tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'tyyppi'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'aktiivinen'); ?> <input type="checkbox" name="Check[aktiivinen]">
		<?php
		$list = array(1=>Yii::t('main', 'Kyllä'),0=>Yii::t('main', 'Ei'));
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">

		<?php echo $form->labelEx($model,'myyja'); ?> <input type="checkbox" name="Check[myyja]">
		<?php echo $form->dropDownList($model, 'myyja', CHtml::listData(Administrators::model()->findAll(), 'id', 'adm_nimi'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'myyja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoryhma'); ?> <input type="checkbox" name="Check[tyoryhma]">
		<?php
	       	$criteria = new CDbCriteria();
		$criteria->order = " value ";
		$criteria->condition = "select_type='tyoryhma'";
		$listData = Valikkoot::model()->findAll($criteria);
		?>
		<?php echo $form->dropDownList($model, 'tyoryhma', CHtml::listData($listData, 'id', 'value'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'tyoryhma'); ?>
	</div>

<?php if(in_array('5',$tas)) : ?>
	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'ryhma'); ?> <input type="checkbox" name="Check[ryhma]">

	   <div class="input-group">
		<?php
		$list = array();
		$criteria=new CDbCriteria;
		$criteria->order = "value";
		$criteria->condition = " 
			select_type='asiakas_ryhma_real'
		";
      		$l = Valikkoot::model()->findAll($criteria);
		if(count($l) == 0)
      		{
			$new_val = new Valikkoot;
			$new_val->select_type = "asiakas_ryhma_real";
			$new_val->value = "Testi ryhmä";
			if($new_val->save()){
				$criteria=new CDbCriteria;
				$criteria->order = "value";
				$criteria->condition = " 
					select_type='asiakas_ryhma_real'
				";
	      			$l = Valikkoot::model()->findAll($criteria);
			} else {
				var_dump($new_val->getErrors());
			}
		}

			$arr = json_decode($model->ryhma);
			echo '<select name="Asiakkaat[ryhma][]" class="ryhmat form-control" multiple title="Valitse">';
			foreach($l as $data)
			{
				if(is_array($arr) and in_array($data->id, $arr))
			    		echo '<option value="'.$data->id.'" selected>'.$data->value.'</option>';
				elseif(!is_array($arr) and $model->ryhma == $data->id)
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

		<?php echo $form->error($model,'ryhma'); ?>
	</div>
<?php endif ; ?>

</div><div class="col-sm-3">
	
	<legend><h3><?php echo Yii::t('main', 'Laskutus tiedot'); ?></h3></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus_kanava'); ?> <input type="checkbox" name="Check[laskutus_kanava]">
		<?php
		$list = array(	'posti'=>Yii::t('main','Posti'),
				'verkkolasku'=>Yii::t('main','Verkkolasku'),
				'sahkoposti'=>Yii::t('main','Sähköposti')
				);
        	echo $form->dropDownList($model, 'laskutus_kanava', $list,
		array('empty'=>'Valitse','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'laskutus_kanava'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'kirjeenluokka'); ?> <input type="checkbox" name="Check[kirjeenluokka]">
		<?php
		$list = array(	'1'=>Yii::t('main','Luokka 1'),
				'2'=>Yii::t('main','Luokka 2')
				);
        	echo $form->dropDownList($model, 'kirjeenluokka', $list,
		array('empty'=>'Valitse','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'kirjeenluokka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'muistutuslasku_auto'); ?> <input type="checkbox" name="Check[muistutuslasku_auto]">
		<?php
		$list = array(	'0'=>Yii::t('main','Kyllä'),
				'1'=>Yii::t('main','Ei')
				);
        	echo $form->dropDownList($model, 'muistutuslasku_auto', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'muistutuslasku_auto'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?> <input type="checkbox" name="Check[viivastyskorko]">
		<?php echo $form->numberField($model,'viivastyskorko',array('size'=>60,'maxlength'=>20,'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'maksuehto'); ?> <input type="checkbox" name="Check[maksuehto]">
		<?php echo $form->numberField($model,'maksuehto',array('size'=>60,'maxlength'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'maksuehto'); ?>
	</div>

	<!-- Tuotteet palvelut -->
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv'); ?> <input type="checkbox" name="Check[alv]">
		<?php
		$model->alv = 24;
        	$l = array(0=>0,10=>10,14=>14,24=>24);

        	echo $form->dropDownList($model, 'alv', $l,
		array('empty'=>'Valitse','class'=>'form-control'
		));
        	?>
		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_tyyppi'); ?> <input type="checkbox" name="Check[hinta_tyyppi]">
		<?php
		$list = array(1=>'tunti',2=>'kk',3=>'kpl');
        	echo $form->dropDownList($model, 'hinta_tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'hinta_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?> <input type="checkbox" name="Check[hinta]">
		<?php echo $form->numberField($model,'hinta',array('value' => 0, 'size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'verot'); ?> <input type="checkbox" name="Check[verot]">
		<?php echo $form->numberField($model,'verot',array('value' => 0, 'size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'verot'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_sis_alv'); ?> <input type="checkbox" name="Check[hinta_sis_alv]">
		<?php echo $form->numberField($model,'hinta_sis_alv',array('value' => 0, 'size'=>10,'maxlength'=>100,'class'=>'form-control', 'step'=>'any')); ?>
		<?php echo $form->error($model,'hinta_sis_alv'); ?>
	</div>



<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	//size: "large",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });

  $("#Asiakkaat_alv").change(function() {
	laskurin();
  });
  $("#Asiakkaat_hinta").keyup(function() {
	laskurin();
  });
  $("#Asiakkaat_hinta_sis_alv").keyup(function() {
	var hinta_sis_alv = parseFloat($(this).val());
	var alv = parseFloat($("#Asiakkaat_alv").val());
	var result = hinta_sis_alv/(1+(alv/100));
	$("#Asiakkaat_hinta").val(result.toFixed(2));
	$("#Asiakkaat_verot").val((hinta_sis_alv-result).toFixed(2));
  });

  function laskurin()
  {
	var alv = parseFloat($("#Asiakkaat_alv").val());
	var hinta = parseFloat($("#Asiakkaat_hinta").val());
	var hinta_sis_alv = ((alv/100)*hinta)+hinta;
	$("#Asiakkaat_hinta_sis_alv").val(hinta_sis_alv.toFixed(2));
	$("#Asiakkaat_verot").val((hinta_sis_alv-hinta).toFixed(2));
  }

});
</script>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinnasto_id'); ?> <input type="checkbox" name="Check[hinnasto_id]">
		<?php echo $form->dropDownList($model, 'hinnasto_id', CHtml::listData(Hinnastot::model()->findAll(), 'id', 'hinnaston_otsikko'), 
		array('empty'=>'Valitse hinnasto', 'class'=>'form-control')); ?> 
		<?php echo $form->error($model,'hinnasto_id'); ?>
	</div>
	<!-- Tuotteet palvelut -->


  </div>
<!-- Laskutus loppu -->

</div><!-- form -->
<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Muokkaa kaikkia') : Yii::t('main', 'Muokkaa kaikkia'),array('class'=>'btn btn-primary myBgColors luoTallennaAsiakas')); ?>
	</div>


<?php $this->endWidget(); ?>


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

  $(".sw").bootstrapSwitch({
	//size: "large",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });


  $(".luoTallennaAsiakas").click(function(e) {
    e.preventDefault();

    var countChecked = function() {
	var n = $( "#asiakkaat-form input:checked" ).length;
	if( n > 0){ return true; } else { return false; }
    };
    if(!countChecked()){ 
	alert('Valitse kentä.');	
	return false; 
    }

    var tyyppi = $('#Asiakkaat_tyyppi option:selected').val();
    if( tyyppi == 'henkilo' && $('#Asiakkaat_yhteyshenkilo').val() == '' ){
	$('#Asiakkaat_yhteyshenkilo').focus();
	alert('Yksityisasiakkaalle yhteyshenkilö on pakollinen tieto.');
	return false;
    }
    if( tyyppi == 'yritys' && $('#Asiakkaat_yrityksen_nimi').val() == '' ){
	$('#Asiakkaat_yrityksen_nimi').focus();
	alert('Yrityksen nimi ei saa olla tyhjänä!');
	return false;
    }
    $('#asiakkaat-form').submit();
  });


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

$("#Asiakkaat_tyyppi").each(function() {
    var value = $(this).val();
    if(value !== '')
      laskutusTyyppi(value);
    else
      openAll();

});

$("#Asiakkaat_tyyppi").change(function() {
    var value = $(this).val();
    laskutusTyyppi(value);
});


function openAll(){
	$(".ashidd_a").show('slow');
	$(".ashidd").show('slow');
}

function laskutusTyyppi(value){

	$(".ashidd_a").show('slow');
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


