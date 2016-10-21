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
   $as = Asiakkaat::model()->find(array("order"=>"id DESC"));

   if(isset($as->id) and !isset($model->id) and $asetukset->lasku_asiakasnumero == 0)
   $asnum = array('value'=>($as->id+1),'class'=>'form-control');
   else
   $asnum = array('class'=>'form-control');

if(isset($model->id))
$model->hinta = str_replace(",",".",$model->hinta);
?>

<style>
.hidd,.ashidd,.ashidd_a{
	display:none;
}
</style>

<div class="row">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakkaat-form',
	'enableAjaxValidation'=>true,
)); ?>


	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Asiakkaan tiedot'); ?></h3></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakasnumero'); ?>
		<?php echo $form->numberField($model,'asiakasnumero',$asnum); ?>
		<?php echo $form->error($model,'asiakasnumero'); ?>
	</div>

	<div class="section fill mb5 tyyppi">
		<?php echo $form->labelEx($model,'tyyppi'); ?>
		<?php
		$list = array('yritys'=>Yii::t('main', 'Yritys'),'henkilo'=>Yii::t('main', 'Yksityishenkilö'));
        	echo $form->dropDownList($model, 'tyyppi', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'tyyppi'); ?>
	</div>

	<div class="section fill mb5 yritys ashidd">
		<?php echo $form->labelEx($model,'yrityksen_nimi'); ?>
		<?php echo $form->textField($model,'yrityksen_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yrityksen_nimi'); ?>
	</div>

	<div class="section fill mb5 y_tunnus ashidd">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section fill mb5 nimi ashidd">
		<?php echo $form->labelEx($model,'yhteyshenkilo'); ?>
		<?php echo $form->textField($model,'yhteyshenkilo',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yhteyshenkilo'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(1=>Yii::t('main', 'Kyllä'),0=>Yii::t('main', 'Ei'));
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">

		<?php echo $form->labelEx($model,'myyja'); ?>
		<?php echo $form->dropDownList($model, 'myyja', CHtml::listData(Administrators::model()->findAll(), 'id', 'adm_nimi'), 
		array('empty'=>'Valitse', 'class'=>'form-control')); 
		?>
		<?php echo $form->error($model,'myyja'); ?>
	</div>

<?php if(in_array('5',$tas)) : ?>
	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'ryhma'); ?>

	   <div class="input-group">
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type"));

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
			<span class="btn btn-primary myBgColors muokaValiko" for="asiakas_ryhma"><i class="fa fa-pencil-square-o"></i></span>
		</span>
	   </div>

		<?php echo $form->error($model,'ryhma'); ?>
	</div>
<?php endif ; ?>

<?php if(in_array('5',$tas)) : ?>
	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'salasana'); ?>
		<?php echo $form->textField($model,'salasana',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'salasana'); ?>
	</div>
<?php endif ; ?>


  </div><div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Laskutusosoite'); ?></h3></legend>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('class'=>'form-control','maxlength'=>5)); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<br>
	<legend><?php echo Yii::t('main', 'Käyntiosoite'); ?> <input type="checkbox" data-toggle="collapse" data-target="#kosoiteet"></legend>

	<div id="kosoiteet" class="collapse">

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'k_osoite'); ?>
		<?php echo $form->textField($model,'k_osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'k_osoite'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'k_postinumero'); ?>
		<?php echo $form->textField($model,'k_postinumero',array('class'=>'form-control','maxlength'=>5)); ?>
		<?php echo $form->error($model,'k_postinumero'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'k_kaupunki'); ?>
		<?php echo $form->textField($model,'k_kaupunki',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>

		<?php echo $form->error($model,'k_kaupunki'); ?>
	</div>

	</div>

  </div>

<!-- Laskutus-->
<div class="col-sm-3">
	
	<legend><h3><?php echo Yii::t('main', 'Laskutus tiedot'); ?></h3></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus_kanava'); ?>
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
		<?php echo $form->labelEx($model,'kirjeenluokka'); ?>
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
		<?php echo $form->labelEx($model,'muistutuslasku_auto'); ?>
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
		<?php echo $form->labelEx($model,'ovt_tunnus'); ?>
		<?php echo $form->textField($model,'ovt_tunnus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'ovt_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'verkkolaskuosoite'); ?>
		<?php echo $form->textField($model,'verkkolaskuosoite',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'verkkolaskuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'valittajan_tunnus'); ?>
		<?php echo $form->textField($model,'valittajan_tunnus',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'valittajan_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->numberField($model,'viivastyskorko',array('size'=>60,'maxlength'=>20,'class'=>'form-control', 'step'=>'0.01')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'maksuehto'); ?>
		<?php echo $form->numberField($model,'maksuehto',array('size'=>60,'maxlength'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'maksuehto'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv'); ?>
		<?php
		$list = array();
		for ($i = 0; $i <= 36 ; $i++) {
		    $list[$i] = $i;
		}

        	echo $form->dropDownList($model, 'alv', $list,
		array('empty'=>'Valitse','class'=>'form-control',
		'options' => array('24'=>array('selected'=>true))
		));
        	?>
		<?php echo $form->error($model,'alv'); ?>
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


<?php if(in_array('5',$tas)) : ?>
	<legend><h3><?php echo Yii::t('main', 'Allennukset'); ?></h3></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'vinkki_tunnit'); ?>
		<?php echo $form->numberField($model,'vinkki_tunnit',array('size'=>10,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'vinkki_tunnit'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'vinkki_prosentti'); ?>
		<?php echo $form->numberField($model,'vinkki_prosentti',array('size'=>10,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'vinkki_prosentti'); ?>
	</div>
<?php endif; ?>


  </div>
<!-- Laskutus loppu -->


<div class="col-sm-3">

	<?php if(isset($model->id)): ?>
	<legend><h3><?php echo Yii::t('main', 'Asiakkaaseen liittyviä kohteita'); ?></h3></legend>
	<br>
	<div class="section fill mb5">
		<?php echo CHtml::link(' +','/index.php/kohteet/createfromasiakas?id='.$model->id,array('class'=>'btn btn-default glyphicon glyphicon-home')); ?>
	</div>

	<div class="section fill mb5">		
		<?php
		$k = Kohteet::model()->findAll("asiakas_id='".$model->id."'");
        	foreach($k as $v)
		{
		   echo '<div class="section fill mb5">
		   <h3 class="glyphicon glyphicon-home"></h3>&nbsp;&nbsp;&nbsp; 
		   '.CHtml::link($v->osoite,'/index.php/kohteet/update?id='.$v->id,array('class'=>'link')).'	       	  
		   </div>';
		}	
        	?>
	</div>
	<?php endif; ?>

  </div>
</div><!-- form -->
<br>

	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('main', 'Luo') : Yii::t('main', 'Tallenna'),array('class'=>'btn btn-primary myBgColors luoTallennaAsiakas')); ?>
	</div>

  </div>
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


/*
$("#Asiakkaat_asiakasnumero").keyup(function() {
    var checkLastAsiakasID = $(this).val();
        $.ajax({
           url: "checkLastAsiakasID",
	   type:'POST',
	   data: { "checkLastAsiakasID" : checkLastAsiakasID },
           success: function(data){
		console.log(data)
		if(parseInt(data) == 1)
		{
		  $("input").prop("disabled", true);
		  $("select").prop("disabled", true);
		  $("#Asiakkaat_asiakasnumero").prop("disabled", false);
		} else {
		  $("input").prop("disabled", false);
		  $("select").prop("disabled", false);
		}
           }
        });
});
*/

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
});


});
</script>


