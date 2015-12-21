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
?>

<style>
.hidd,.ashidd,.ashidd_a{
	display:none;
}
</style>

<div class="row">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'asiakkaat-form',
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

  <div class="col-sm-3">
	<legend><h3><?php echo Yii::t('main', 'Asiakkaan tiedot'); ?></h3></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'asiakasnumero'); ?>
		<?php echo $form->textField($model,'asiakasnumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'asiakasnumero'); ?>
	</div>

	<div class="section fill mb5 tyyppi">
		<?php echo $form->labelEx($model,'tyyppi'); ?>
		<?php
		$list = array('yritys'=>Yii::t('main', 'Yritys'),'henkilo'=>Yii::t('main', 'Yksityishenkilö'));
        	echo $form->dropDownList($model, 'tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control input-sm'));	
        	?>
		<?php echo $form->error($model,'tyyppi'); ?>
	</div>

	<div class="section fill mb5 yritys ashidd">
		<?php echo $form->labelEx($model,'yrityksen_nimi'); ?>
		<?php echo $form->textField($model,'yrityksen_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'yrityksen_nimi'); ?>
	</div>

	<div class="section fill mb5 y_tunnus ashidd">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section fill mb5 nimi ashidd">
		<?php echo $form->labelEx($model,'yhteyshenkilo'); ?>
		<?php echo $form->textField($model,'yhteyshenkilo',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'yhteyshenkilo'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'kaupunki'); ?>
		<?php echo $form->textField($model,'kaupunki',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'kaupunki'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('class'=>'form-control input-sm','maxlength'=>5)); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'ryhma'); ?>
		<?php
		$list = array();
      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma' ",array('order' => "select_type"));
		foreach($l as $v)
		$list[$v->id] = $v->value;

		if(count($list) > 0)
		{
        	echo $form->dropDownList($model, 'ryhma', $list,
		array('empty'=>'Valitse ryhmä','class'=>'form-control input-sm'));
		} else {
		echo 'Luo Valikko tietokannassa "Select Type = asiakas_ryhma"';
		}		
        	?>
		<?php echo $form->error($model,'ryhma'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'aktiivinen'); ?>
		<?php
		$list = array(1=>Yii::t('main', 'Kyllä'),0=>Yii::t('main', 'Ei'));
        	echo $form->dropDownList($model, 'aktiivinen', $list,
		array('empty'=>'Valitse tilanne','class'=>'form-control input-sm'));	
        	?>
		<?php echo $form->error($model,'aktiivinen'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'myyja'); ?>
		<?php echo $form->dropDownList($model, 'myyja', CHtml::listData(Administrators::model()->findAll(), 'id', 'adm_nimi'), 
		array('empty'=>'Valitse', 'class'=>'form-control input-sm')); 
		?>
		<?php echo $form->error($model,'myyja'); ?>
	</div>

  </div>

<?php if(isset($model->id)): ?>
  <?php if(in_array('3',$tas)) : ?>
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
		array('empty'=>'Valitse','class'=>'form-control input-sm'));
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
		array('empty'=>'Valitse','class'=>'form-control input-sm'));
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
		array('class'=>'form-control input-sm'));
        	?>
		<?php echo $form->error($model,'muistutuslasku_auto'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'ovt_tunnus'); ?>
		<?php echo $form->textField($model,'ovt_tunnus',array('class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'ovt_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'verkkolaskuosoite'); ?>
		<?php echo $form->textField($model,'verkkolaskuosoite',array('class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'verkkolaskuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'valittajan_tunnus'); ?>
		<?php echo $form->textField($model,'valittajan_tunnus',array('class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'valittajan_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'maksuehto'); ?>
		<?php echo $form->textField($model,'maksuehto',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
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
		array('empty'=>'Valitse','class'=>'form-control input-sm',
		'options' => array('24'=>array('selected'=>true))
		));
        	?>
		<?php echo $form->error($model,'alv'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta_tyyppi'); ?>
		<?php
		$list = array(1=>'tunti',2=>'kk');
        	echo $form->dropDownList($model, 'hinta_tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control input-sm'));	
        	?>
		<?php echo $form->error($model,'hinta_tyyppi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'hinta'); ?>
		<?php echo $form->textField($model,'hinta',array('size'=>10,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'hinta'); ?>
	</div>
  </div>
  <?php endif; ?>
<?php endif; ?>

<div class="col-sm-4">

	<?php if(isset($model->id)): ?>
	<legend><h3><?php echo Yii::t('main', 'Asiakkaaseen liittyviä kohteita'); ?></h3></legend>

	<div class="section fill mb5">
		<label><?php echo Yii::t('main', 'Luo kohde'); ?></label>
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
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary')); ?>
	</div>

  </div>
</div>
<?php $this->endWidget(); ?>



<script type="text/javascript">
$(document).ready(function(){

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


});
</script>


