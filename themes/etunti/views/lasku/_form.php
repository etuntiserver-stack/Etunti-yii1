<?php
/* @var $this LaskuController */
/* @var $model Lasku */
/* @var $form CActiveForm */

if(isset($model->id))
{
/* POSTITA */
	$criteria = new CDbCriteria();
	$criteria->order = " id DESC ";
	$criteria->condition = " lid='".$model->id."' ";
  	$laskuHistoria = LaskuHistoria::model()->find($criteria);
/* POSTITA */
}
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lasku-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php if(isset($model->id) and $model->tyyppi == 'henkilo') : ?> 
<style>
.yritys,.y_tunnus, #kalut, .toimitus{
	display:none;
}
</style>
<?php elseif(isset($model->id) and $model->tyyppi == 'yritys') : ?> 
<style>
.nimi, #kalut, .toimitus{
	display:none;
}
</style>
<?php else : ?> 
<style>
.hidd,.ashidd,.ashidd_a,.tyyppi, #kalut, .toimitus{
	display:none;
}
</style>
<?php endif; ?> 

<?php
if(isset($model->id)){
echo '<input type="hidden" id="modelID" value="1">';
echo '<input type="hidden" id="forLaskutusTyyppi" value="'.$model->laskutus.'">';
echo '<input type="hidden" id="forTilanne" value="'.$model->tilanne.'">';
echo '<input type="hidden" id="trust_jobid" value="'.$model->trust_jobid.'">';
echo '<input type="hidden" id="postita_jobid" value="'.$model->postita_jobid.'">';
} else {
echo '<input type="hidden" id="forTilanne" value="0">';
}

$asetukset = Asetukset::model()->findbypk(1);
$model->yid = $asetukset->id;
$model->saaja_iban = $asetukset->iban;
if(empty($model->viivastyskorko))
$model->viivastyskorko = $asetukset->viivastyskorko;

?>

	<?php echo $form->errorSummary($model); ?>

<div class="row">
  <div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'ASIAKAS'); ?></legend>

	<?php if(isset($model->id)) : ?> 
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'as_nro'); ?>
		<?php echo $form->textField($model,'as_nro',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'as_nro'); ?>
	</div>
	<?php else : ?> 

	<?php if($asetukset->palvelu_tyyppi != 2) : ?>
	<?php
       		$criteria = new CDbCriteria();
       		$criteria->order = "laskunumero DESC";
       		$criteria->condition = "laskunumero!='0'";
		$ln = '';
		$vm = Lasku::model()->find($criteria);
		if(isset($vm->id))
		$ln = $vm->laskunumero+1;
	?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskunumero'); ?>
		<?php echo $form->textField($model,'laskunumero',array('value'=>$ln,'size'=>60,'maxlength'=>11,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'laskunumero'); ?>
	</div>
	<?php endif; ?>

	<div class="section fill mb5 asiakas">
		<?php echo $form->labelEx($model,'as_nro'); ?>
    		<?php 
       		$criteria = new CDbCriteria();
		//$criteria->select = " COALESCE(NULLIF(yhteyshenkilo,yhteyshenkilo),'gg') AS yht ";
		$criteria->order = " yhteyshenkilo ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="Lasku[as_nro]" class="form-control" id="Lasku_as_nro">';

		if(isset($model->asiakas_id) and !empty($model->asiakas_id))
		{
        	  $aon = Asiakkaat::model()->findbypk($model->asiakas_id);
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aon->asiakasnumero.'">'.$aon->yrityksen_nimi.'</option>';
		  elseif(empty($aon->yhteyshenkilo) and empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->asiakasnumero.'">nimet puutuu '.$aon->id.'</option>';
		  else
		    echo '<option value="'.$aon->asiakasnumero.'">'.$aon->yhteyshenkilo.'</option>';
		} else {
	        echo '<option></option>';
		}

		foreach($a as $aa)
		{
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->asiakasnumero.'">'.$aa->yrityksen_nimi.'</option>';
		  elseif(empty($aa->yhteyshenkilo) and empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->asiakasnumero.'">nimet puutuu '.$aa->id.'</option>';
		  else
		    echo '<option value="'.$aa->asiakasnumero.'">'.$aa->yhteyshenkilo.'</option>';
		}
		echo '</select>';
		?>
		<?php echo $form->error($model,'as_nro'); ?>
	</div>
	<?php endif; ?> 


	<div class="section fill mb5 tyyppi">
		<?php echo $form->labelEx($model,'tyyppi'); ?>
		<?php
		$list = array('yritys'=>Yii::t('main', 'Yritys'),'henkilo'=>Yii::t('main', 'Yksityishenkilö'));
        	echo $form->dropDownList($model, 'tyyppi', $list,
		array('empty'=>'Valitse tyyppi','class'=>'form-control'));	
        	?>
		<?php echo $form->error($model,'tyyppi'); ?>
	</div>

	<div class="section fill mb5 yritys ashidd">
		<?php echo $form->labelEx($model,'yritys'); ?>
		<?php echo $form->textField($model,'yritys',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yritys'); ?>
	</div>

	<div class="section fill mb5 y_tunnus ashidd">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section fill mb5 nimi ashidd">
		<?php echo $form->labelEx($model,'nimi'); ?>
		<?php echo $form->textField($model,'nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nimi'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'toimipaikka'); ?>
		<?php echo $form->textField($model,'toimipaikka',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'toimipaikka'); ?>
	</div>

	<div class="section fill mb5 ashidd_a">
		<?php echo $form->labelEx($model,'toimitusosoite'); ?>
		<?php
		$list = array(0=>'Ei',1=>'Kyllä');
        	echo $form->dropDownList($model, 'toimitusosoite', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'toimitusosoite'); ?>
	</div>

	<div class="section fill mb5 toimitus">
		<?php echo $form->labelEx($model,'t_yritys'); ?>
		<?php echo $form->textField($model,'t_yritys',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_yritys'); ?>
	</div>

	<div class="section fill mb5 toimitus">
		<?php echo $form->labelEx($model,'t_y_tunnus'); ?>
		<?php echo $form->textField($model,'t_y_tunnus',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_y_tunnus'); ?>
	</div>

	<div class="section fill mb5 toimitus">
		<?php echo $form->labelEx($model,'t_nimi'); ?>
		<?php echo $form->textField($model,'t_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_nimi'); ?>
	</div>

	<div class="section fill mb5 toimitus">
		<?php echo $form->labelEx($model,'t_osoite'); ?>
		<?php echo $form->textField($model,'t_osoite',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_osoite'); ?>
	</div>

	<div class="section fill mb5 toimitus">
		<?php echo $form->labelEx($model,'t_postinumero'); ?>
		<?php echo $form->textField($model,'t_postinumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_postinumero'); ?>
	</div>

	<div class="section fill mb5 toimitus">
		<?php echo $form->labelEx($model,'t_toimipaikka'); ?>
		<?php echo $form->textField($model,'t_toimipaikka',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_toimipaikka'); ?>
	</div>

	<div class="section fill mb5 toimitus">
		<?php echo $form->labelEx($model,'t_puhelin'); ?>
		<?php echo $form->textField($model,'t_puhelin',array('size'=>60,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_puhelin'); ?>
	</div>

	<div class="section fill mb5 toimitus">
		<?php echo $form->labelEx($model,'t_sahkoposti'); ?>
		<?php echo $form->textField($model,'t_sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_sahkoposti'); ?>
	</div>

  </div><div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'LASKUTUS'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskutus'); ?>
		<?php
		$list = array(	'posti'=>Yii::t('main','Posti'),
				'verkkolasku'=>Yii::t('main','Verkkolasku'),
				'sahkoposti'=>Yii::t('main','Sähköposti')
				);
        	echo $form->dropDownList($model, 'laskutus', $list,
		array('empty'=>'Valitse','class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'laskutus'); ?>
	</div>

	<div class="section fill mb5 sahkoposti hidd">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="section fill mb5 verkkolaskuosoite hidd">
		<?php echo $form->labelEx($model,'verkkolaskuosoite'); ?>
		<?php echo $form->textField($model,'verkkolaskuosoite',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'verkkolaskuosoite'); ?>
	</div>

	<div class="section fill mb5 v_tunnus hidd">
		<?php echo $form->labelEx($model,'v_tunnus'); ?>
		<?php echo $form->textField($model,'v_tunnus',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'v_tunnus'); ?>
	</div>

	<div class="section fill mb5 yhteyshenkilo hidd">
		<?php echo $form->labelEx($model,'yhteyshenkilo'); ?>
		<?php echo $form->textField($model,'yhteyshenkilo',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'yhteyshenkilo'); ?>
	</div>

	<div class="section fill mb5 nimitarkenne hidd">
		<?php echo $form->labelEx($model,'nimitarkenne'); ?>
		<?php echo $form->textField($model,'nimitarkenne',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nimitarkenne'); ?>
	</div>

	<div class="section fill mb5 puhelin hidd">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section fill mb5 deliverymethod">
		<?php echo $form->labelEx($model,'deliverymethod'); ?>
		<?php echo $form->textField($model,'deliverymethod',array('size'=>50,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'deliverymethod'); ?>
	</div>

	<div class="section fill mb5 deliveryterm">
		<?php echo $form->labelEx($model,'deliveryterm'); ?>
		<?php echo $form->textField($model,'deliveryterm',array('size'=>50,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'deliveryterm'); ?>
	</div>

  </div><div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'LASKUN TIEDOT'); ?></legend>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'paivays'); ?>
		<?php echo $form->textField($model,'paivays',array('value'=>date("Y-m-d"),'size'=>20,'maxlength'=>20,'class'=>'form-control  datepicker')); ?>
		<?php echo $form->error($model,'paivays'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'erapaiva'); ?>
		<?php echo $form->textField($model,'erapaiva',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'erapaiva'); ?>
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
		<?php echo $form->labelEx($model,'kirjeenluokka'); ?>
		<?php
		$list = array(	'1'=>Yii::t('main','Luokka 1'),
				'2'=>Yii::t('main','Luokka 2')
				);
        	echo $form->dropDownList($model, 'kirjeenluokka', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'kirjeenluokka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'toimituspaiva'); ?>
		<?php echo $form->textField($model,'toimituspaiva',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'toimituspaiva'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'maksuehto'); ?>
		<?php echo $form->textField($model,'maksuehto',array('size'=>20,'maxlength'=>20,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'maksuehto'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viitenumero'); ?>
		<?php echo $form->textField($model,'viitenumero',array('size'=>60,'maxlength'=>100,'class'=>'form-control','placeholder'=>'se tulee luomisen jälkeen')); ?>
		<?php echo $form->error($model,'viitenumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viivastyskorko'); ?>
		<?php echo $form->textField($model,'viivastyskorko',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viivastyskorko'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tilanne'); ?>
		<?php echo $form->textField($model,'tilanne',array('size'=>50,'maxlength'=>50,'class'=>'form-control','readonly'=>'yes')); ?>
		<?php echo $form->error($model,'tilanne'); ?>
	</div>

  </div><div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'YRITYS'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'yid'); ?>
		<?php echo $form->dropDownList($model,'yid', 
		CHtml::listData(FirmanTiedot::model()->findAll(), 'id', 'tyonantaja'), 
		array('empty'=>'Valitse saaja','class'=>'form-control')) ?>
		<?php echo $form->error($model,'yid'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'saaja_iban'); ?>
		<?php echo $form->textField($model,'saaja_iban',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'saaja_iban'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viitenne'); ?>
		<?php echo $form->textField($model,'viitenne',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viitenne'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viitemme'); ?>
		<?php echo $form->textField($model,'viitemme',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viitemme'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'freetext'); ?>
		<?php echo $form->textarea($model,'freetext',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'freetext'); ?>
	</div>

  </div>
</div>

<br>

<div class="row form tosoite" style="display:none">
  <div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'TOIMITUS OSOITE'); ?></legend>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'t_yritys'); ?>
		<?php echo $form->textField($model,'t_yritys',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_yritys'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'t_y_tunnus'); ?>
		<?php echo $form->textField($model,'t_y_tunnus',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_y_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'t_nimi'); ?>
		<?php echo $form->textField($model,'t_nimi',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_nimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'t_osoite'); ?>
		<?php echo $form->textField($model,'t_osoite',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'t_postinumero'); ?>
		<?php echo $form->textField($model,'t_postinumero',array('size'=>10,'maxlength'=>10,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_postinumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'t_toimipaikka'); ?>
		<?php echo $form->textField($model,'t_toimipaikka',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_toimipaikka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'t_puhelin'); ?>
		<?php echo $form->textField($model,'t_puhelin',array('size'=>50,'maxlength'=>50,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_puhelin'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'t_sahkoposti'); ?>
		<?php echo $form->textField($model,'t_sahkoposti',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'t_sahkoposti'); ?>
	</div>
  </div>
</div>

<br>

<!--<span class="pull-right  btn btn-info" data-toggle="collapse"  data-target="#kalut"><?php echo Yii::t('main', 'Työkalut'); ?> <b class="caret"></b></span>
<br>
-->
<br>

<div class="row form kht" id="kalut">

<div id="tuntiKalut">
    <div class="col-sm-6">

  <div class="panel heading-border">
   <div class="panel-body">

    <legend><?php echo Yii::t('main', 'TUNNIT'); ?></legend>
      <div class="section fill mb5">
	<div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="from" class="form-control form-group datepicker" value="<?php echo date('Y-m-d',strtotime('first day of last month', time())); ?>">
	</div><div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="to" class="form-control form-group datepicker" value="<?php echo date('Y-m-d',strtotime('last day of last month', time())); ?>">
	</div>
      </div>

      <div class="section fill mb5">
	<div class="col-sm-6">
		<div id="getkohdeT" class="form-group"></div>
	</div><div class="col-sm-6">
	<br>
	<?php
	echo CHtml::dropdownList('tuntipalvelu','palvelu', CHtml::listData(LaskutusTuotteet::model()->findAll(), 'tuotenimi', 'tuotenimi'), 
	array('empty'=>'Valitse tuote/palvelu','class'=>'form-control valitseTuote'));
	?>
	</div>
      </div>
      <div class="col-sm-12">
        	<b class="btn btn-success  luoRiviTunti"><?php echo Yii::t('main', 'Luo rivit'); ?></b>
      </div>

   </div>
  </div>

    </div>
</div>




<div id="kkKalut">
    <div class="col-sm-6">

  <div class="panel heading-border">
   <div class="panel-body">

    <legend><?php echo Yii::t('main', 'KUUKAUSI'); ?></legend>
 
      <div class="section fill mb5">
	<div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="fromkk" class="form-control form-group datepicker" value="<?php echo date('Y-m-d',strtotime('first day of last month', time())); ?>">
	</div><div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="tokk" class="form-control form-group datepicker" value="<?php echo date('Y-m-d',strtotime('last day of last month', time())); ?>">
	</div>
      </div>

       <div class="section fill mb5">
	<div class="col-sm-6">
		<div id="getkohdeKk" class="form-group"></div>
	</div><div class="col-sm-6">
	<br>
	<?php
	echo CHtml::dropdownList('kkpalvelu','palvelu', CHtml::listData(LaskutusTuotteet::model()->findAll(), 'tuotenimi', 'tuotenimi'), 
	array('empty'=>'Valitse tuote/palvelu','class'=>'form-control valitseTuote'));
	?>
	</div>
      </div>
      <div class="col-sm-12">
        	<b class="btn btn-success  luoRiviKk"><?php echo Yii::t('main', 'Luo rivit'); ?></b>
      </div>

   </div>
  </div>

    </div>
</div>

</div>


	<input type="hidden" class="form-control" id="kohteistaRivit" readonly><br>
	<div id="tuntienTulos"></div>

<br>

<div id="rivit" class="table-responsive">
<TABLE class="table well" id="TableRivit">

     <TR>
     <thead class="myBgColors">
	<TH style="width:1%"><span id="uusiRivi" class="link" style="font-size: 150%;"><i class="fa fa-plus-square"></i></span></TH>
	<TH class="col-sm-2">Tuote/Palvelu</TH>
	<TH class="col-sm-1">Kpl</TH>
	<TH class="col-sm-1">Yksikkö <span class="btn btn-primary btn-xs myBgColors muokaValiko" for="laskutus_yksikko"><i class="fa fa-pencil-square-o"></i></span></TH>
	<TH class="col-sm-1">Hinta</TH>
	<TH class="col-sm-1">ALV%</TH>
	<TH class="col-sm-1">ALV</TH>
	<TH class="col-sm-1">Ale%</TH>
	<TH class="col-sm-1">Veroton</TH>
	<TH class="col-sm-1">Yhteensä</TH>
     </thead>
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
</div>

  

<br><br><br><br><br><br>

	<div class="section fill mb5 subm">
		<?php if(!isset($model->id) or $model->tilanne == '0') : ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Tallenna' : 'Tallenna',array('class'=>'btn  btn-primary myBgColors')); ?>
		<?php endif; ?>


		<?php if(
			isset($model->id) 
			and $asetukset->palvelu_tyyppi == 3
		) : ?>
		<a href="lasku_pdf?id=<?php echo $model->id; ?>" target="_blank" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Esikatselu'); ?></a>


		<a href="lasku_pdf?id=<?php echo $model->id; ?>&muistutuslasku=true" target="_blank" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Maksumuistutus'); ?></a>
		<?php endif; ?>




		<?php if(isset($model->id) and $model->tilanne == '1' and $asetukset->palvelu_tyyppi == 1) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&finvoice=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä finvoice (POSTITA.FI)'); ?></a>
		<?php endif; ?>


  <?php 
  if(isset($model->id) 
 	and $model->tilanne == '0'
	and $asetukset->palvelu_tyyppi == 1
  ){
  echo '<a href="finvoice?id='.$model->id.'&laskutus='.$model->laskutus.'" class="btn btn-success btn-group myBgColors">'.Yii::t('main','Hyväksy ja luo POSTITA:ssa (ei viellä lähetä)').'</a>';
  }
  ?>

  <?php 
  if(isset($model->id) 
 	and $model->tilanne == '2'
	and $asetukset->palvelu_tyyppi == 1
	and isset($laskuHistoria->id)
	and $laskuHistoria->postita_statuscode == 'NE'
  ){
  echo '<a href="finvoice?id='.$model->id.'&vahvistus='.$model->postita_jobid.'" class="btn btn-success btn-group myBgColors">'.Yii::t('main','Vahvista ja lähetä').'</a>';
  }
  ?>

  <?php 
  if(isset($model->id) 
	and file_exists(Yii::app()->basePath."/../tiedostot/laskut/".Yii::app()->user->domain.'/'.$model->id.'.pdf')
  ){
  echo '<a href="postita_pdf?id='.$model->id.'" class="btn btn-success btn-group myBgColors">'.Yii::t('main','PDF').'</a>';
  }
  ?>



		<?php if(isset($model->id) and $model->tilanne == '0') : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&hyvaksyminen=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Hyväksy'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) 
			and $model->laskun_nimetys != "Hyvityslasku" 
			and $asetukset->palvelu_tyyppi == 2
			and $model->trust_jobid != ''
			)
		: ?>
		<a href="hyvityslasku?id=<?php echo $model->id; ?>" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Hyvityslasku'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) 
			and $model->laskun_nimetys == "Hyvityslasku"
			and $asetukset->palvelu_tyyppi == 2
			and $model->tilanne != 98
			and $model->trust_jobid != ''
			) 
		: ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&hyvityslasku=true&refundtojobid=<?php echo $model->trust_jobid; ?>" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä Hyvityslasku (TRUST.FI)'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) and $model->tilanne == '1' and $asetukset->palvelu_tyyppi == 1) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&pdf=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä PDF (POSTITA.FI)'); ?></a>
		<?php endif; ?>


		<?php if(isset($model->id) 
			and $model->tilanne == 1 
			and empty($model->trust_jobid) 
			and $asetukset->palvelu_tyyppi == 2 
			and $model->laskun_nimetys != "Hyvityslasku") 
		: ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&finvoiceTrust=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä finvoice (TRUST.FI)'); ?></a>
		<?php endif; ?>

		<?php if(
			isset($model->id) 
			and $model->tilanne != 0 
			and $asetukset->palvelu_tyyppi != 2 
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&lahetaSahkopostilla=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä sähköpostilla'); ?></a>
		<?php endif; ?>


		<?php if(
			isset($model->id) 
			and $model->tilanne != 0 
			and $asetukset->palvelu_tyyppi != 2
			and !empty($asetukset->trust_url)
			and !empty($asetukset->trust_cid)
			and !empty($asetukset->trust_api)
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&finvoiceTrust=true&jobtype=2" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä perintään'); ?></a>
		<?php endif; ?>


		<hr>
		<p><b>Laskun tila: </b><?php echo $this->tilanneCheck($model,null); ?></p>
		<br>

		<?php if(
			isset($model->id) 
			and ($asetukset->palvelu_tyyppi == 1 or $asetukset->palvelu_tyyppi == 3)
			and $model->tilanne != '0'
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&merkitseMaksetuksi=true" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Maksettu'); ?></a>
		<?php endif; ?>

		<?php if(
			isset($model->id) 
			and $asetukset->palvelu_tyyppi == 3
			and $model->tilanne != '0'
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&merkitseLahetettavaksi=true" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Lähetetty'); ?></a>
		<?php endif; ?>

		<?php if(
			isset($model->id) 
			and $asetukset->palvelu_tyyppi == 3
			and $model->tilanne != '0'
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&merkitseMaksumuistutusLahetettavaksi=true" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Maksumuistutus lähetetty'); ?></a>
		<?php endif; ?>


		<?php if(isset($model->id) and $asetukset->palvelu_tyyppi == 1) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&lahetaMuistutusPostita=true" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Lähetä MAKSUMUISTUTUS POSTITA.FI'); ?></a>
		<?php endif; ?>

<!--
		<?php if(isset($model->id) and $model->tilanne != '3') : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&lahetaPerintaan=true" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Lähetä perintään'); ?></a>
		<?php endif; ?>
-->

	</div>

<?php $this->endWidget(); ?>




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

if($("#trust_jobid").val() | $("#postita_jobid").val()){
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



function valitseTuote(){

$("table#TableRivit .valitseTuote").change(function() {
    var tuoteID = $(this).val();
    var num = $(this).attr("num");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/valitsetuote',
           type: "POST",
           data: { tuoteID : tuoteID },
           success: function(data){
		var sp = data.split("//");

		$("#kpl_"+num).val("1");

		if(sp[0])
		$("#tkoodi_"+num).val(sp[0]);
		if(sp[1])
		$("#hinta_"+num).val(sp[1]);
		if(sp[3])
		$("#yksikko_"+num+" option[value="+sp[3]+"]").attr('selected','selected');
		if(sp[2])
		$("#alv_"+num+" option[value="+sp[2]+"]").attr('selected','selected');

		eachLaskenta();
		$("#lt_"+num).hide();
		console.log(data)
           }
        });

});

	poista();
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

  valitseTuote();

  $(".onlyDigits ").attr('type', 'number');

  $('#rivit input[type="number"]').keyup(function() {
  	eachLaskenta();
    	yhteensaTotal();

  });

  $('.for_tkoodi').keyup(function(){
	var forID = $(this).attr("id").split("_");
	$('#lt_'+forID[1]).hide();
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


$(".luoRiviTunti").click(function() {

	var from = $("#from").val();
	var to = $("#to").val();
	var kohteet = $(".selectpicker.h").val();
	var tuotePalvelu = $("#tuntipalvelu").val();

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
	if (!kohteet) 
	{ 
	    alert('Valitse kohde')
	    return false;

	} 

	    pyyntoRiville(kohteet,from,to,tuotePalvelu,"tunti");
});



$(".luoRiviKk").click(function() {

	var from = $("#fromkk").val();
	var to = $("#tokk").val();
	var kohteet = $(".selectpicker.kk").val();
	var tuotePalvelu = $("#kkpalvelu").val();


	if (from  === '') 
	{
	     $('#fromkk').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (to  === '') 
	{
	     $('#tokk').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (!kohteet) 
	{ 
	    alert('Valitse kohde')
	    return false;

	} 

	    pyyntoRiville(kohteet,from,to,tuotePalvelu,"kk");

});


function pyyntoRiville(kohteet,from,to,tuotePalvelu,tuntiVaiKk){

	    //$("#trRivi_1").remove();

	    $.each(kohteet, function( index, value ) {

	    var spH = value.split("//");
	    if(spH[3] == 'onkohde')
	    var mistaLuo = 'luoKohteista';

	    if(spH[3] == 'eikohde')
	    var mistaLuo = 'luoAsiakaasta';


	        $.ajax({
	           url: mistaLuo+'?id='+spH[0],
		   type: 'POST',
		   data: { from : from, to : to },
	           success: function(data){
	               	console.log(data);
			var tunnit = data;			
			var num = 0;
			if((tunnit == 0) && (tuntiVaiKk == "tunti"))
			{
				$("#tuntienTulos").addClass("alert alert-danger").html('<b>Ei löydy tuntia</b>');
				//$("#rivit").hide('slow');
			}

			if((tunnit > 0) || (tuntiVaiKk == "kk"))
			{
			num = $("table#TableRivit tbody tr").length+index;
			
			var kpl = 0;
			var yksikko = 0;
			if(tuntiVaiKk == "kk"){
			  yksikko = 'kk';
			  kpl = '1';
			} else {
			  yksikko = spH[2];
			  kpl = tunnit;
			}

	        	$.ajax({
		           url: 'tr_rivit?id='+spH[0],
			   type: 'POST',
			   data: { num : num, from : from, to : to, kpl : kpl, hinta : spH[1], yksikko : yksikko, onkokohde : spH[3], tuotePalvelu : tuotePalvelu },
		           success: function(data){
				console.log(value);
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
    if(!asiakas)
    {
	alert("Asiakasnumero puuttuu");
	return false;
    }
	
	$("#kalut").show('slow');

        $.ajax({
           url: 'etsikohde?id='+asiakas+'&tuntiTaiKk=1',
           success: function(data){
		var spdata = JSON.parse(data).split("***");
               	console.log(JSON.parse(data)+" asiakas:"+asiakas);

		if(spdata[1] == true)
		{
		$("#getkohdeT").html(spdata[0]);
		$('.selectpicker').selectpicker();
		$("#tuntiKalut").show();
		} else {
		$("#tuntiKalut").hide();
		}

           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });

        $.ajax({
           url: 'etsikohde?id='+asiakas+'&tuntiTaiKk=2',
           success: function(data){
               	console.log(JSON.parse(data)+" asiakas:"+asiakas);
		var spdata = JSON.parse(data).split("***");

		if(spdata[1] == true)
		{
		$("#getkohdeKk").html(spdata[0]);
		$('.selectpicker').selectpicker();
		$("#kkKalut").show();
		} else {
		$("#kkKalut").hide();
		}

           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });


        $.ajax({
           url: 'etsiasiakas?id='+asiakas,
           success: function(data){
               	//console.log(data);
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
		if(sp[10]){
		    $("#Lasku_v_tunnus").val(sp[10])
		}
		if(sp[11]){
		    $("#Lasku_verkkolaskuosoite").val(sp[11])
		}
		if(sp[12]){
		    $("#Lasku_muistutuslasku_auto option[value="+sp[12]+"]").attr('selected','selected');
		}
		if(sp[13]){
		    $("#Lasku_kirjeenluokka option[value="+sp[13]+"]").attr('selected','selected');
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


$("#Lasku_toimitusosoite").change(function() {
    var toimitusosoite = $(this).val();
    if(toimitusosoite == 1){
	$(".toimitus").show('slow');
    }
    if(toimitusosoite == 0){
	$(".toimitus").hide('slow');
    }
});



});
</script>





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
		////console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */


});
</script>

