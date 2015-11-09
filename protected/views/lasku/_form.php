<?php
/* @var $this LaskuController */
/* @var $model Lasku */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lasku-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php if(isset($model->id) and $model->tyyppi == 'henkilo') : ?> 
<style>
.yritys,.y_tunnus{
	display:none;
}
</style>
<?php elseif(isset($model->id) and $model->tyyppi == 'yritys') : ?> 
<style>
.nimi{
	display:none;
}
</style>
<?php else : ?> 
<style>
.hidd,.ashidd,.ashidd_a,.tyyppi{
	display:none;
}
</style>
<?php endif; ?> 

<?php
if(isset($model->id)){
echo '<input type="hidden" id="modelID" value="1">';
echo '<input type="hidden" id="forLaskutusTyyppi" value="'.$model->laskutus.'">';
echo '<input type="hidden" id="forTilanne" value="'.$model->tilanne.'">';
}

$firma = Asetukset::model()->findbypk(1);
$model->yid = $firma->id;
$model->saaja_iban = $firma->iban;
$model->viivastyskorko = $firma->viivastyskorko;

?>

	<?php echo $form->errorSummary($model); ?>

<div class="row form">
  <div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'ASIAKAS'); ?></legend>

	<?php if(isset($model->id)) : ?> 
	<div class="row">
		<?php echo $form->labelEx($model,'as_nro'); ?>
		<?php echo $form->textField($model,'as_nro',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'as_nro'); ?>
	</div>
	<?php else : ?> 
	<div class="row asiakas">
		<?php echo $form->labelEx($model,'as_nro'); ?>
    		<?php 
       		$criteria = new CDbCriteria();
		//$criteria->select = " COALESCE(NULLIF(yhteyshenkilo,yhteyshenkilo),'gg') AS yht ";
		$criteria->order = " yhteyshenkilo ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="Lasku[as_nro]" class="form-control input-sm" id="Lasku_as_nro">';

		if(isset($model->asiakas_id) and !empty($model->asiakas_id))
		{
        	  $aon = Asiakkaat::model()->findbypk($model->asiakas_id);
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aon->id.'">'.$aon->yrityksen_nimi.'</option>';
		  elseif(empty($aon->yhteyshenkilo) and empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->id.'">nimet puutuu '.$aon->id.'</option>';
		  else
		    echo '<option value="'.$aon->id.'">'.$aon->yhteyshenkilo.'</option>';
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
		    echo '<option value="'.$aa->id.'">'.$aa->yhteyshenkilo.'</option>';
		}
		echo '</select>';
		?>
		<?php echo $form->error($model,'as_nro'); ?>
	</div>
	<?php endif; ?> 


	<div class="row tyyppi">
		<?php echo $form->labelEx($model,'tyyppi'); ?>
		<?php
		$list = array('yritys'=>Yii::t('main', 'Yritys'),'henkilo'=>Yii::t('main', 'Yksityishenkilö'));
        	echo $form->dropDownList($model, 'tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control input-sm'));	
        	?>
		<?php echo $form->error($model,'tyyppi'); ?>
	</div>

	<div class="row yritys ashidd">
		<?php echo $form->labelEx($model,'yritys'); ?>
		<?php echo $form->textField($model,'yritys',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'yritys'); ?>
	</div>

	<div class="row y_tunnus ashidd">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="row nimi ashidd">
		<?php echo $form->labelEx($model,'nimi'); ?>
		<?php echo $form->textField($model,'nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'nimi'); ?>
	</div>

	<div class="row ashidd_a">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row ashidd_a">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>10,'maxlength'=>10,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="row ashidd_a">
		<?php echo $form->labelEx($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'toimipaikka'); ?>
	</div>

	<div class="row ashidd_a">
		<?php echo $form->labelEx($model,'toimitusosoite'); ?>
		<?php
		$list = array(0=>'Ei',1=>'Kyllä');
        	echo $form->dropDownList($model, 'toimitusosoite', $list,
		array('class'=>'form-control input-sm'));
        	?>
		<?php echo $form->error($model,'toimitusosoite'); ?>
	</div>

  </div><div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'LASKUTUS'); ?></legend>

	<div class="row">
		<?php echo $form->labelEx($model,'laskutus'); ?>
		<?php
		$list = array(	'posti'=>Yii::t('main','Posti'),
				'verkkolasku'=>Yii::t('main','Verkkolasku'),
				'sahkoposti'=>Yii::t('main','Sähköposti')
				);
        	echo $form->dropDownList($model, 'laskutus', $list,
		array('empty'=>'Valitse','class'=>'form-control input-sm'));
        	?>
		<?php echo $form->error($model,'laskutus'); ?>
	</div>

	<div class="row sahkoposti hidd">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="row verkkolaskuosoite hidd">
		<?php echo $form->labelEx($model,'verkkolaskuosoite'); ?>
		<?php echo $form->textField($model,'verkkolaskuosoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'verkkolaskuosoite'); ?>
	</div>

	<div class="row v_tunnus hidd">
		<?php echo $form->labelEx($model,'v_tunnus'); ?>
		<?php echo $form->textField($model,'v_tunnus',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'v_tunnus'); ?>
	</div>

	<div class="row yhteyshenkilo hidd">
		<?php echo $form->labelEx($model,'yhteyshenkilo'); ?>
		<?php echo $form->textField($model,'yhteyshenkilo',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'yhteyshenkilo'); ?>
	</div>

	<div class="row nimitarkenne hidd">
		<?php echo $form->labelEx($model,'nimitarkenne'); ?>
		<?php echo $form->textField($model,'nimitarkenne',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'nimitarkenne'); ?>
	</div>

	<div class="row puhelin hidd">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

  </div><div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'LASKUN TIEDOT'); ?></legend>
	<div class="row">
		<?php echo $form->labelEx($model,'paivays'); ?>
		<?php echo $form->textField($model,'paivays',array('value'=>date("Y-m-d"),'size'=>20,'maxlength'=>20,'class'=>'form-control input-sm datepicker')); ?>
		<?php echo $form->error($model,'paivays'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'erapaiva'); ?>
		<?php echo $form->textField($model,'erapaiva',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm datepicker')); ?>
		<?php echo $form->error($model,'erapaiva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'toimituspaiva'); ?>
		<?php echo $form->textField($model,'toimituspaiva',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm datepicker')); ?>
		<?php echo $form->error($model,'toimituspaiva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maksuehto'); ?>
		<?php echo $form->textField($model,'maksuehto',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'maksuehto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viitenumero'); ?>
		<?php echo $form->textField($model,'viitenumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm','placeholder'=>'se tulee luomisen jälkeen')); ?>
		<?php echo $form->error($model,'viitenumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->textField($model,'viivastyskorko',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tilanne'); ?>
		<?php echo $form->textField($model,'tilanne',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'tilanne'); ?>
	</div>

  </div><div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'YRITYS'); ?></legend>

	<div class="row">
		<?php echo $form->labelEx($model,'yid'); ?>
		<?php echo $form->dropDownList($model,'yid', 
		CHtml::listData(FirmanTiedot::model()->findAll(), 'id', 'tyonantaja'), 
		array('empty'=>'Valitse saaja','class'=>'form-control input-sm')) ?>
		<?php echo $form->error($model,'yid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'saaja_iban'); ?>
		<?php echo $form->textField($model,'saaja_iban',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'saaja_iban'); ?>
	</div>

  </div>
</div>

<br>

<div class="row form tosoite" style="display:none">
  <div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'TOIMITUS OSOITE'); ?></legend>


	<div class="row">
		<?php echo $form->labelEx($model,'t_yritys'); ?>
		<?php echo $form->textField($model,'t_yritys',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'t_yritys'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'t_y_tunnus'); ?>
		<?php echo $form->textField($model,'t_y_tunnus',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'t_y_tunnus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'t_nimi'); ?>
		<?php echo $form->textField($model,'t_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'t_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'t_osoite'); ?>
		<?php echo $form->textField($model,'t_osoite',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'t_osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'t_postinumero'); ?>
		<?php echo $form->textField($model,'t_postinumero',array('size'=>10,'maxlength'=>10,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'t_postinumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'t_toimipaikka'); ?>
		<?php echo $form->textField($model,'t_toimipaikka',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'t_toimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'t_puhelin'); ?>
		<?php echo $form->textField($model,'t_puhelin',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'t_puhelin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'t_sahkoposti'); ?>
		<?php echo $form->textField($model,'t_sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'t_sahkoposti'); ?>
	</div>
  </div>
</div>

<br>

<span class="pull-right btn-sm btn btn-info" data-toggle="collapse"  data-target="#kalut"><?php echo Yii::t('main', 'Kalut'); ?> <b class="caret"></b></span>
<br>
<hr>

<div class="row form kht collapse" id="kalut">

    <div class="col-sm-6">
    <legend><?php echo Yii::t('main', 'TUNNIT'); ?></legend>
      <div class="row">
	<div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="from" class="form-control input-sm form-group datepicker" value="<?php echo date("d.m.Y",strtotime('first day of this month', time())); ?>">
	</div><div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="to" class="form-control input-sm form-group datepicker" value="<?php echo date("d.m.Y",strtotime('last day of this month', time())); ?>">
	</div>
      </div>

      <div class="row">
	<div class="col-sm-6">
		<b class="glyphicon glyphicon-th-list"></b>
		<?php
		echo CHtml::dropdownList('','palvelu', CHtml::listData(LaskutusTuotteet::model()->findAll(), 'id', 'tuotenimi'), array('empty'=>'Valitse palvelu','class'=>'form-control input-sm','id'=>'lt'));
		?>
	</div><div class="col-sm-6">
		<div id="getkohde" class="form-group"></div>
	</div>
      </div>
      <div class="col-sm-12">
        	<b class="btn btn-success btn-sm hae"><?php echo Yii::t('main', 'Luo rivit'); ?></b>
      </div>
    </div>

    <div class="col-sm-6">
    <legend><?php echo Yii::t('main', 'KUUKAUSI'); ?></legend>
      <div class="row">
	<div class="col-sm-6">
	<label><?php echo Yii::t('main', 'Kuukausi'); ?></label>
	<?php
	$kk = array(
		"Tammikuu"=>"Tammikuu",
		"Helmikuu"=>"Helmikuu",
		"Maaliskuu"=>"Maaliskuu",
		"Huhtikuu"=>"Huhtikuu",
		"Toukokuu"=>"Toukokuu",
		"Kesäkuu"=>"Kesäkuu",
		"Heinäkuu"=>"Heinäkuu",
		"Elokuu"=>"Elokuu",
		"Syyskuu"=>"Syyskuu",
		"Lokakuu"=>"Lokakuu",
		"Marraskuu"=>"Marraskuu", 
		"Joulukuu"=>"Joulukuu");
	echo CHtml::dropdownList('','kk', $kk, array('empty'=>'Valitse kuukausi','class'=>'form-control input-sm','id'=>'kk'));
	?>
	</div><div class="col-sm-6">
	<label><?php echo Yii::t('main', 'Valitse vuosi'); ?></label>
	<input type="text" id="vuosi" class="form-control input-sm" value="<?php echo date('Y'); ?>">
	</div>
      </div>

      <div class="row">
	<div class="col-sm-6">
		<b class="glyphicon glyphicon-euro"></b> 
		<input type="text" id="hintaForTuntikk" class="form-control input-sm form-group" placeholder="syötä kk hinta">
	</div><div class="col-sm-6">
		<b class="glyphicon glyphicon-th-list"></b>
		<?php
		echo CHtml::dropdownList('','palvelu', CHtml::listData(LaskutusTuotteet::model()->findAll(), 'tuotenimi', 'tuotenimi'), array('empty'=>'Valitse palvelu','class'=>'form-control input-sm','id'=>'palvelu'));
		?>
	</div>
      </div>
      <div class="col-sm-12">
        	<b class="btn btn-success btn-sm haekk"><?php echo Yii::t('main', 'Luo rivit'); ?></b>
      </div>
    </div>


</div>


	<input type="hidden" class="form-control input-sm" id="kohteistaRivit" readonly><br>
	<div id="tuntienTulos"></div>

<br>

<div id="rivit" class="row">
<label><?php echo Yii::t('main', 'Laskun rivit'); ?></label>
<TABLE class="table" id="TableRivit">

     <TR>
	<TH></TH>
	<TH class="col-sm-2">Tuote/Palvelu</TH>
	<TH>Kpl</TH>
	<TH class="col-sm-1">Yksikkö</TH>
	<TH class="col-sm-1">Hinta</TH>
	<TH>ALV%</TH>
	<TH class="col-sm-1">ALV</TH>
	<TH class="col-sm-1">Ale%</TH>
	<TH>Veroton</TH>
	<TH>Yhteensä</TH>
     </TR>

     <tbody>
     <?php if(!isset($model->id)) : ?> 
     <div class="tr_rivit"></div>
     <?php else : ?>

     <?php 
	$num = 0;
	foreach($laskunRivit as $rivi){ 
	$num++;
	echo $this->renderPartial("//lasku/tr_rivi_update",array('num'=>$num,'rivi'=>$rivi));
	}
     ?>

     <?php endif; ?>  
     </tbody>

     <tfoot>
     <TR>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD><input type="text" class="form-control input-sm" size="10" name="Lasku[yhteensa_total_verot]" id="yhteensa_total_verot" readonly></TD>
	<TD></TD>
	<TD><input type="text" class="form-control input-sm" size="10" name="Lasku[yhteensa_total_veroton]" id="yhteensa_total_veroton" readonly></TD>
	<TD><input type="text" class="form-control input-sm" size="10" name="Lasku[yhteensa_total]" id="yhteensa_total" readonly></TD>
     </TR>
     </tfoot>
</TABLE>

  <span id="uusiRivi" class="link text-success"><?php echo Yii::t('main','Uusi rivi'); ?></span>
</div>

<br><br><br><br><br><br>

	<div class="row subm">
		<?php if($model->tilanne != '1' and $model->tilanne != '2') : ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Tallenna' : 'Tallenna',array('class'=>'btn btn-sm btn-primary')); ?>
		<?php endif; ?>

		<?php if(isset($model->id)) : ?>
		<a href="lasku_pdf?id=<?php echo $model->id; ?>" target="_blank" class="btn btn-sm btn-default btn-group"><?php echo Yii::t('main','Esikatselu'); ?></a>

		<?php if(isset($model->id) and $model->tilanne == '1') : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&finvoice=true" class="btn btn-sm btn-success btn-group"><?php echo Yii::t('main','Lähetä Finvoice'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) and $model->tilanne != '1' and $model->tilanne != '2') : ?>
		<a href="update?id=<?php echo $model->id; ?>&tilanne=1" class="btn btn-sm btn-success btn-group"><?php echo Yii::t('main','Hyväksy'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) and $model->tilanne == '1') : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&pdf=true" class="btn btn-sm btn-success btn-group"><?php echo Yii::t('main','Lähetä PDF'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) and $model->tilanne == '2') : ?>
		<?php echo Yii::t('main','Lasku on lähetetty'); ?>
		<?php endif; ?>
		<?php endif; ?>
	</div>

<?php $this->endWidget(); ?>


<br>
<div class="row">
<?php if(isset($model->id) and $model->response != '') : ?>
  <div class="col-sm-6 well">
	<?php 
	$expl = explode("//",$model->response);
	foreach($expl as $e)
	{
	echo $e.'<br>'; 
	}
	?>
  </div>
<?php endif; ?>
<?php if(isset($model->id) and $model->response_finvoice != '') : ?>
  <div class="col-sm-6 well">
	<?php 
	$expl = explode("//",$model->response_finvoice);
	foreach($expl as $e)
	{
	echo $e.'<br>'; 
	}
	?>
  </div>
<?php endif; ?>
</div>

<script type="text/javascript">
$(document).ready(function(){


$("#lasku-form").on('submit',function(e) {

    var checkAll = true;

    $('table#TableRivit .for_tkoodi').each(function() {
	var tkoodi =  $(this).val();

	if(tkoodi == '')
	{	
	    $(this).css({"border" : "2px #f14010 solid"}).focus();
	    checkAll = false;
	}
    });

	if(checkAll == false)
	    return false;
	else
	    return true;
});

if(($("#forTilanne").val() == '1') | ($("#forTilanne").val() == '2')){
  $("input").prop("disabled", true);
  $("select").prop("disabled", true);
  $(".poista").remove();
  $("#uusiRivi").remove();
}

if($("#modelID").val() != '1'){
    var rivi = $("#samaRivi").html();
    var rowCount = $('table#TableRivit tbody tr').length;

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/tr_rivit_tyhja',
           type: "POST",
           data: {num : rowCount},
           success: function(html){
         	$("table#TableRivit tbody tr").last().after(html);
	  	Rivi();
           }
        });
}

$("#uusiRivi").click(function() {
    var rivi = $("#samaRivi").html();
    var rowCount = $('table#TableRivit tbody tr').length;

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/tr_rivit_tyhja',
           type: "POST",
           data: {num : rowCount},
           success: function(html){
         	$("table#TableRivit tbody tr").last().after(html);
	  	Rivi();
           }
        });
});


function jumpToPageBottom() {
    $('html, body').animate({scrollTop:1000}, 'slow');
    return false;
}

poista();
function poista(){
  $(".poista").click(function() {
	var forID = $(this).attr("for").split("_");
	$("#trRivi_"+forID[1]).remove();
	yhteensaTotal();
  });
}

Rivi();

function Rivi(){


  $("#rivit input").keyup(function() {

	var inputKenta = $(this).attr("id").split("_");
	var hinta_alv_0 = $("#hinta_"+inputKenta[1]).val();
	var alv = $("#alv_"+inputKenta[1]).val();
	var kpl = $("#kpl_"+inputKenta[1]).val();
	var ale = $("#ale_"+inputKenta[1]).val();

	var laske = parseFloat(((hinta_alv_0*kpl)/100*alv), 10);
	var laskeAleY = parseFloat((($("#yhteensa_alv_"+inputKenta[1]).val())/100*ale), 10);
	var laskeAleV = parseFloat((($("#veroton_"+inputKenta[1]).val())/100*ale), 10);

	var veroton = parseFloat(hinta_alv_0, 10)*kpl;
	yhteensa = laske+veroton;

	$("#hinta_alv_"+inputKenta[1]).val((laske).toFixed(2));

	if(veroton-laskeAleV > 0)
	  $("#veroton_"+inputKenta[1]).val((veroton-laskeAleV).toFixed(2));
	else
	  $("#veroton_"+inputKenta[1]).val('0.00');

	if(yhteensa-laskeAleY > 0)
	  $("#yhteensa_alv_"+inputKenta[1]).val((yhteensa-laskeAleY).toFixed(2));
	else
	  $("#yhteensa_alv_"+inputKenta[1]).val('0.00');

    	yhteensaTotal();

  });

}

  eachLaskenta();

function eachLaskenta(){

  $("#rivit input").each(function() {

	var inputKenta = $(this).attr("id").split("_");
	var hinta_alv_0 = $("#hinta_"+inputKenta[1]).val();
	var alv = $("#alv_"+inputKenta[1]).val();
	var kpl = $("#kpl_"+inputKenta[1]).val();
	var ale = $("#ale_"+inputKenta[1]).val();


	var laske = parseFloat(((hinta_alv_0*kpl)/100*alv), 10);
	var laskeAleY = parseFloat((($("#yhteensa_alv_"+inputKenta[1]).val())/100*ale), 10);
	var laskeAleV = parseFloat((($("#veroton_"+inputKenta[1]).val())/100*ale), 10);

	var veroton = parseFloat(hinta_alv_0, 10)*kpl;
	yhteensa = laske+veroton;

	$("#hinta_alv_"+inputKenta[1]).val((laske).toFixed(2));

	if(veroton-laskeAleV > 0)
	  $("#veroton_"+inputKenta[1]).val((veroton-laskeAleV).toFixed(2));
	else
	  $("#veroton_"+inputKenta[1]).val('0.00');

	if(yhteensa-laskeAleY > 0)
	  $("#yhteensa_alv_"+inputKenta[1]).val((yhteensa-laskeAleY).toFixed(2));
	else
	  $("#yhteensa_alv_"+inputKenta[1]).val('0.00');

  });
    	yhteensaTotal();

}

function yhteensaTotal(){

	var sum = 0;
	$('.yhteensa_total_verot').each(function(){
	    sum += parseFloat(this.value);
	    $('#yhteensa_total_verot').val(sum.toFixed(2));
	});
	var sum1 = 0;
	$('.yhteensa_total_veroton').each(function(){
	    sum1 += parseFloat(this.value);
	    $('#yhteensa_total_veroton').val(sum1.toFixed(2));
	});
	var sum2 = 0;
	$('.yhteensa_total').each(function(){
	    sum2 += parseFloat(this.value);
	    $('#yhteensa_total').val(sum2.toFixed(2));
	});
}


$(".hae").click(function() {

	var from = $("#from").val();
	var to = $("#to").val();
	var kohteet = $(".selectpicker").val();
	var lt = $("#lt").val();

	if (from  === '') 
	{
	     $('#from').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (to  === '') 
	{
	     $('#to').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (lt  === '') 
	{
	     $('#hintaForTunti').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (!kohteet) 
	{
	    $('.filter-option').css({"color" : "red"}).focus();
	    return false;

	} else {
	    $("#kohteistaRivit").val(kohteet);

	    //$("table#TableRivit tbody .kaikkiTR").remove();

	    $.each(kohteet, function( index, value ) {
	        $.ajax({
	           url: 'luoKohteista?id='+value,
		   type: 'POST',
		   data: { from : from, to : to },
	           success: function(data){
	               	console.log(data);
			var tunnit = data;

			if(tunnit == 0)
			{
				$("#tuntienTulos").addClass("alert alert-danger").html('<h2>Ei löydy tuntia</h2>');
				$("#rivit").hide('slow');
			}

			if(tunnit > 0)
			{

	        	$.ajax({
		           url: 'tr_rivit?num='+index+'&id='+value,
			   type: 'POST',
			   data: { from : from, to : to, kpl : tunnit, lt : lt },
		           success: function(data){
				//console.log(data);
				$("table#TableRivit tbody tr").last().after(data);
				poista();
				eachLaskenta();
				Rivi();
				$("#tuntienTulos").removeClass("alert alert-danger").html('');
				$("#rivit").show('slow');
				$(".subm").show('slow');
		           },
		           error: function(XMLHttpRequest, textStatus, errorThrown){
		               	console.log(XMLHttpRequest);
			   }
		        });

			}
	
	           },
	           error: function(XMLHttpRequest, textStatus, errorThrown){
	               	console.log(XMLHttpRequest);
		   }
	        });
	    });

	    
	    jumpToPageBottom();
	   
	}

});



$(".haekk").click(function() {

	var kk = $("#kk").val();
	var vuosi = $("#vuosi").val();
	var asiakas = $("#Lasku_as_nro").val();
	var hintaForTuntikk = $("#hintaForTuntikk").val();
	var palvelu = $("#palvelu option:selected").text();
	var p = kk+"-"+vuosi+" "+palvelu;

	if (kk  === '') 
	{
	     $('#kk').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (vuosi  === '') 
	{
	     $('#vuosi').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if ($("#palvelu").val()  === '') 
	{
	     $('#palvelu').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (hintaForTuntikk  === '') 
	{
	     $('#hintaForTuntikk').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}


	    //$("table#TableRivit tbody .kaikkiTR").remove();



	        	$.ajax({
		           url: 'tr_rivitkk',
			   type: 'POST',
			   data: { hintaForTunti : hintaForTuntikk, palvelu : p },
		           success: function(data){
				//console.log(data);
				$("table#TableRivit tbody tr").last().after(data);
				poista();
				eachLaskenta();
				Rivi();
				$("#tuntienTulos").removeClass("alert alert-danger").html('');
				$("#rivit").show('slow');
				$(".subm").show('slow');
		           },
		           error: function(XMLHttpRequest, textStatus, errorThrown){
		               	console.log(XMLHttpRequest);
			   }
		        });

	    
	    jumpToPageBottom();
	   


});


$("#Lasku_yid").change(function() {

    var saaja = $(this).val();

        $.ajax({
           url: 'etsisaaja?id='+saaja,
           success: function(data){
               	console.log(data);

		if(data)
		$("#Lasku_saaja_iban").val(data);
		

           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });
});


$("#Lasku_as_nro").change(function() {

    var asiakas = $(this).val();

        $.ajax({
           url: 'etsikohde?id='+asiakas,
           success: function(data){
               	//console.log(data);
		$("#getkohde").html(data);
           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });


        $.ajax({
           url: 'etsiasiakas?id='+asiakas,
           success: function(data){
               	console.log(data);
		var sp = data.split("//");
		$(".tyyppi").show('slow');


		laskutus(sp[0]);
		if(sp[0]){
		  $("#Lasku_laskutus option[value="+sp[0]+"]").attr('selected','selected');
		}
		if(sp[1]){
		  $("#Lasku_maksuehto").val(sp[1]);
		}


		if(sp[2]){
		  var spR = sp[2].split("**");
		  $("#Lasku_tyyppi option[value="+spR[0]+"]").attr('selected','selected');
		  laskutusTyyppi(spR[0]);
		
		  if(spR[0] =='yritys')
		  {
		    $("#Lasku_yritys").val(spR[1])
		    $("#Lasku_y_tunnus").val(spR[2])
		  }

		  if(spR[0] =='henkilo')
		  {
		    $("#Lasku_nimi").val(spR[1])
		  }
		}

		if(sp[3]){
		    $("#Lasku_osoite").val(sp[3])
		}
		if(sp[4]){
		    $("#Lasku_postinumero").val(sp[4])
		}
		if(sp[5]){
		    $("#Lasku_toimipaikka").val(sp[5])
		}
		if(sp[6]){
		    $("#Lasku_yhteyshenkilo").val(sp[6])
		}
		if(sp[7]){
		    $("#Lasku_puhelin").val(sp[7])
		}
		if(sp[9]){
		    $("#Lasku_erapaiva").val(sp[9])
		}

           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });

});


$("#Lasku_tyyppi").change(function() {
    var value = $(this).val();
    laskutusTyyppi(value);
});

$("#Lasku_laskutus").change(function() {
    var value = $(this).val();
    laskutus(value);
});

laskutus($("#forLaskutusTyyppi").val())

function laskutusTyyppi(value){

	$(".ashidd_a").show('slow');
    if(value == 'yritys'){
	$(".ashidd").hide('slow');
	$(".yritys").show('slow');
	$(".y_tunnus").show('slow');
    }
    if(value == 'henkilo'){
	$(".ashidd").hide('slow');
	$(".nimi").show('slow');
    }

}

function laskutus(value){

    if(value == 'sahkoposti'){
	$(".hidd").hide('slow');
	$(".sahkoposti").show('slow');
	$(".yhteyshenkilo").show('slow');
	$(".puhelin").show('slow');
    }
    if(value == 'posti'){
	$(".hidd").hide('slow');
	$(".yhteyshenkilo").show('slow');
	$(".puhelin").show('slow');
    }
    if(value == 'verkkolasku'){
	$(".hidd").hide('slow');
	$(".verkkolaskuosoite").show('slow');
	$(".v_tunnus").show('slow');
	$(".yhteyshenkilo").show('slow');
	$(".puhelin").show('slow');
    }

}


});
</script>

















<?php
/*
	<div class="row">
		<?php echo $form->labelEx($model,'lid'); ?>
		<?php echo $form->textField($model,'lid'); ?>
		<?php echo $form->error($model,'lid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'saaja_virtualkoodi'); ?>
		<?php echo $form->textField($model,'saaja_virtualkoodi',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'saaja_virtualkoodi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'maksettu_euro'); ?>
		<?php echo $form->textField($model,'maksettu_euro',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'maksettu_euro'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hyvityslasku'); ?>
		<?php echo $form->textField($model,'hyvityslasku',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'hyvityslasku'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'laskun_nimetys'); ?>
		<?php echo $form->textField($model,'laskun_nimetys',array('size'=>60,'maxlength'=>100,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'laskun_nimetys'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'yhteensa_total_verot'); ?>
		<?php echo $form->textField($model,'yhteensa_total_verot',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'yhteensa_total_verot'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yhteensa_total_veroton'); ?>
		<?php echo $form->textField($model,'yhteensa_total_veroton',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'yhteensa_total_veroton'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'yhteensa_total'); ?>
		<?php echo $form->textField($model,'yhteensa_total',array('size'=>20,'maxlength'=>20,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'yhteensa_total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tilanne'); ?>
		<?php echo $form->textField($model,'tilanne',array('size'=>50,'maxlength'=>50,'class'=>'form-control input-sm')); ?>
		<?php echo $form->error($model,'tilanne'); ?>
	</div>
*/
?>

