<?php
/* @var $this LaskuController */
/* @var $model Lasku */
/* @var $form CActiveForm */
$asetukset = Asetukset::model()->findbypk(1);

if(isset($model->id))
{
/* POSTITA */
	$criteria = new CDbCriteria();
	$criteria->order = " id DESC ";
	$criteria->condition = " lid='".$model->id."' ";
  	$laskuHistoria = LaskuHistoria::model()->find($criteria);
/* POSTITA */
}

if(!isset($model->id) and isset($asetukset->id)){
	$model->laskutus = $asetukset->asiakas_laskutus_kanava;
	$model->kirjeenluokka = $asetukset->asiakas_kirjeenluokka;
	$model->viivastyskorko = $asetukset->asiakas_viivastyskorko;
	$model->maksuehto = $asetukset->asiakas_maksuehto;

	$model->paivays = date("d.m.Y");
	$model->erapaiva = date("d.m.Y", strtotime($model->paivays." +$model->maksuehto day"));
}

?>
<style>
#TableRivit.table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
	padding: 0;
}
</style>


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
$model->yid = $asetukset->id;
$model->saaja_iban = $asetukset->iban;
if(empty($model->viivastyskorko))
$model->viivastyskorko = $asetukset->viivastyskorko;


if(isset($model->id)){
$model->paivays = date("d.m.Y", strtotime($model->paivays));
$model->erapaiva = date("d.m.Y", strtotime($model->erapaiva));

echo '<input type="hidden" id="modelID" value="1">';
echo '<input type="hidden" id="forLaskutusTyyppi" value="'.$model->laskutus.'">';
echo '<input type="hidden" id="forTilanne" value="'.$model->tilanne.'">';
echo '<input type="hidden" id="trust_jobid" value="'.$model->trust_jobid.'">';
echo '<input type="hidden" id="postita_jobid" value="'.$model->postita_jobid.'">';
} else {
echo '<input type="hidden" id="forTilanne" value="0">';
}

echo '<input type="hidden" id="palvelu_tyyppi" value="'.$asetukset->palvelu_tyyppi.'">';
?>

	<?php echo $form->errorSummary($model); ?>

<div class="row">
  <div class="col-sm-3">
  <legend><?php echo Yii::t('main', 'ASIAKAS'); ?></legend>

	<?php if(isset($_GET['digisten_tunnit_id'])) : ?>
	<div class="section fill mb5">
		<?php
		$digisten_tunnit_id = $_GET['digisten_tunnit_id'];
		?>
		<?php echo $form->hiddenField($model,'digisten_tunnit_id',array('value'=>$digisten_tunnit_id, 'class'=>'form-control')); ?>
	</div>
	<?php endif; ?>

	<?php if(isset($_GET['edico_tilaus_id'])) : ?>
	<div class="section fill mb5">
		<?php
		$edico_tilaus_id = $_GET['edico_tilaus_id'];
		?>
		<?php echo $form->hiddenField($model,'edico_tilaus_id',array('value'=>$edico_tilaus_id, 'class'=>'form-control')); ?>
	</div>
	<?php endif; ?>


	<?php if(isset($model->id)) : ?> 
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'as_nro'); ?>
		<?php echo $form->textField($model,'as_nro',array('size'=>60,'maxlength'=>100,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'as_nro'); ?>
	</div>
	<?php endif; ?> 

	<?php if($asetukset->lasku_laskunumero == 1): ?>
	<?php
		$ln = 0;
		$criteria = new CDbCriteria();
       		$criteria->select = " id, MAX(ABS(laskunumero)) as laskunumero ";
		$vm = Lasku::model()->find($criteria);
		if( isset($vm->id) and !isset($model->id)){ 
			$ln = $vm->laskunumero+1; 
		} elseif(isset($model->laskunumero) and !empty($model->laskunumero)){
			$ln = $model->laskunumero;
		}

	?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'laskunumero'); ?>
		<?php echo $form->textField($model,'laskunumero',array('value'=>$ln,'size'=>60,'maxlength'=>11,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'laskunumero'); ?>
	</div>
	<?php endif; ?> 

	<?php if(!isset($model->id)) : ?>
	<div class="section fill mb5">
		<label><?php echo Yii::t('main', 'Osoite haku'); ?></label>
		<input type="text" id="osoiteHaku" class="form-control">
		<div id="osoiteHakuResult"></div>
	</div>
	<?php endif; ?>

	<?php if(!isset($model->id)) : ?> 
	<div class="section fill mb5 asiakas">
		<?php echo $form->labelEx($model,'as_nro'); ?>
    		<?php 
       		$criteria = new CDbCriteria();
		$criteria->condition = " asiakasnumero!='' ";

        	$a = Asiakkaat::model()->findAll($criteria);
		$a_sort = array();
		foreach($a as $aa)
		{
		  if(!empty($aa->yrityksen_nimi) and $aa->tyyppi == 'yritys'){
		    $a_sort[$aa->yrityksen_nimi] = array('asiakasnumero' => $aa->asiakasnumero, 'asiakas_id' => $aa->id);
		  }
		  if(!empty($aa->yhteyshenkilo) and $aa->tyyppi == 'henkilo'){
		    $a_sort[$aa->yhteyshenkilo] = array('asiakasnumero' => $aa->asiakasnumero, 'asiakas_id' => $aa->id);
		  }
		}
		ksort($a_sort);


		echo '<select name="Lasku[as_nro]" class="form-control" id="Lasku_as_nro">';
		echo '<option value=>'.Yii::t('main', 'Valitse asiakas').'</option>';
		if(isset($model->asiakas_id) and !empty($model->asiakas_id))
		{
        	  $aon = Asiakkaat::model()->findbypk($model->asiakas_id);
		  if(isset($aon->id) and !empty($aon->asiakasnumero) and !empty($aon->yrityksen_nimi) and $aon->tyyppi == 'yritys'){
		    echo '<option value="'.$aon->asiakasnumero.'" asiakas_id="'.$aon->id.'">'.$aon->yrityksen_nimi.'</option>';
		  }
		  if(isset($aon->id) and !empty($aon->asiakasnumero) and !empty($aon->yhteyshenkilo) and $aon->tyyppi == 'henkilo'){
		    echo '<option value="'.$aon->asiakasnumero.'" asiakas_id="'.$aon->id.'">'.$aon->yhteyshenkilo.'</option>';
		  }
		}

		foreach($a_sort as $k => $v)
		{
		    echo '<option value="'.$v['asiakasnumero'].'" asiakas_id="'.$v['asiakas_id'].'">'.$k.'</option>';
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

	<?php if( $asetukset->netvisor_kaytto == 1 ): ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'netvisor_dimension_name'); ?>
		<?php 
		echo '<select class="form-control" name="Lasku[netvisor_dimension_name]" id="Lasku_netvisor_dimension_name">';
	 	echo '<option>Valitse</option>';
		foreach($this->netvisorLaskentaKohteetLista() as $k => $v){
		 foreach($v->DimensionName as $k1 => $v1){
		 	echo '<optgroup label="'.$v1->Name.'">';
			foreach($v1->DimensionDetails->DimensionDetail as $k2 => $v2){
			 	echo '<option value="'.$v1->Name.'//'.$v2->Name.'" '.(( isset($model->netvisor_dimension_name) and !empty($model->netvisor_dimension_name) and isset($model->netvisor_dimension_item) and !empty($model->netvisor_dimension_item) and $model->netvisor_dimension_name.'//'.$model->netvisor_dimension_item == $v1->Name.'//'.$v2->Name )? 'selected':'').'>'.$v2->Name.'</option>';
			}
		 }
		}
		echo '</select>';
		?>
	</div>
	<?php endif; ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'alv_muoto'); ?>
		<?php
		$list = array(0=>'Hinnat ALV 0%',1=>'Hinnat sis. ALV');
        	echo $form->dropDownList($model, 'alv_muoto', $list,
		array('class'=>'form-control'));
        	?>
		<?php echo $form->error($model,'alv_muoto'); ?>
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

		if($asetukset->palvelu_tyyppi == 3)
		unset($list['verkkolasku']);

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
		<?php echo $form->textField($model,'paivays',array('size'=>20,'maxlength'=>20,'class'=>'form-control  datepickerFI')); ?>
		<?php echo $form->error($model,'paivays'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'erapaiva'); ?>
		<?php echo $form->textField($model,'erapaiva',array('size'=>20,'maxlength'=>20,'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'erapaiva'); ?>
	</div>

	<?php if($asetukset->palvelu_tyyppi == 2) : ?>
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
	<?php endif; ?>

	<?php if($asetukset->palvelu_tyyppi == 2) : ?>
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
	<?php endif; ?>

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

	<?php if($asetukset->palvelu_tyyppi == 2) : ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viitenne'); ?>
		<?php echo $form->textField($model,'viitenne',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viitenne'); ?>
	</div>
	<?php endif; ?>

	<?php if($asetukset->palvelu_tyyppi == 2) : ?>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'viitemme'); ?>
		<?php echo $form->textField($model,'viitemme',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'viitemme'); ?>
	</div>
	<?php endif; ?>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'freetext'); ?>
		<?php echo $form->textarea($model,'freetext',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'freetext'); ?>
	</div>

	<?php if($asetukset->palvelu_tyyppi == 2) : ?>
	<div class="section fill mb5 vatperiod">
		<?php echo $form->labelEx($model,'vatperiod'); ?>
		<?php echo $form->textField($model,'vatperiod',array('size'=>50,'maxlength'=>50,'class'=>'form-control datepicker')); ?>
		<?php echo $form->error($model,'vatperiod'); ?>
	</div>
	<?php endif; ?>

  </div>
</div>


<div class="row form tosoite" style="display:none">
  <div class="col-sm-3">
  <br>
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

<!--<span class="pull-right  btn btn-info" data-toggle="collapse"  data-target="#kalut"><?php echo Yii::t('main', 'Työkalut'); ?> <b class="caret"></b></span>
<br>
-->
<div class="row" id="tuotteet_palvelut_muoto" style="display:none">
	<div class="col-sm-3 section fill mb5">
		<br>
		<?php echo $form->labelEx($model,'tuotteet_palvelut_muoto'); ?>
		<?php 
        	$tal = array(
			0=>'Hinnaston mukaan',
			1=>'Asiakkaan / kohteen hinta'
		);
		$model->tuotteet_palvelut_muoto = $asetukset->tuotteet_palvelut_muoto;
		echo $form->dropDownList($model,'tuotteet_palvelut_muoto', $tal, 
		array('class'=>'form-control input-lg')) ?>
		<?php echo $form->error($model,'tuotteet_palvelut_muoto'); ?>
		<br>
	</div>
</div>

<div class="row form kht" id="kalut">
 <div class="col-sm-6" id="tuntiKalut">
  <div class="panel heading-border">
   <div class="panel-body">

    <legend><?php echo Yii::t('main', 'TUNNIT'); ?></legend>
      <div class="row section fill mb5">
	<div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="from" class="form-control form-group datepickerFI" value="<?php echo date('d.m.Y',strtotime('first day of last month', time())); ?>">
	</div><div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="to" class="form-control form-group datepickerFI" value="<?php echo date('d.m.Y',strtotime('last day of last month', time())); ?>">
	</div>
      </div>

      <div class="row section fill mb5">
	<div class="col-sm-6">
		<div id="getkohdeT" class="form-group"></div>
	</div><div class="col-sm-6">
	<br>
	<?php
	$criteria = new CDbCriteria();
       	$criteria->condition = " 
		hinta_alv_0!=0 AND (yksikko='h' OR yksikko='kpl') 
		AND kategoria NOT LIKE '%eDico%' AND kategoria NOT LIKE '%onlinevaraus%'
	";
	echo CHtml::dropdownList('palvelu','palvelu', CHtml::listData(TuotteetPalvelut::model()->findAll($criteria), 'id', 'nimike'), 
	array('empty'=>'Valitse tuote/palvelu','class'=>'form-control valitseTuote'));
	?>
	<span class="text-danger">Tuotteet jolla yksikkö "h" tai "kpl"</span>
	</div>
      </div>
      
      <div class="row section fill mb5">
      <div class="col-sm-6">

      </div>
      <div class="col-sm-6">
	<br>
        	<b class="btn btn-default btn-lg pull-right luoRiviTunti" jakso="tunti"><?php echo Yii::t('main', 'Luo rivit'); ?></b>
      </div>
      </div>

   </div>
  </div>
 </div>
 <div class="col-sm-6" id="kkKalut">
  <div class="panel heading-border">
   <div class="panel-body">

    <legend><?php echo Yii::t('main', 'KK'); ?></legend>
      <div class="row section fill mb5">
	<div class="col-sm-6">
		<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="kuukausi" class="form-control form-group datepickerMY" value="<?php echo date('Y-m'); ?>">
	</div><div class="col-sm-6">
		<!--<b class="glyphicon glyphicon-calendar"></b> 
   		<input type="text" id="to" class="form-control form-group datepickerMY" value="<?php echo date('Y-m'); ?>">-->
	</div>
      </div>

      <div class="row section fill mb5">
	<div class="col-sm-6">
		<div id="getkohdeKK" class="form-group"></div>
	</div><div class="col-sm-6">
	<br>
	<?php
	$criteria = new CDbCriteria();
       	$criteria->condition = " 
		hinta_alv_0!=0 AND yksikko='kk' 
		AND kategoria NOT LIKE '%eDico%' AND kategoria NOT LIKE '%onlinevaraus%'
	";
	echo CHtml::dropdownList('kk_palvelu','kk_palvelu', CHtml::listData(TuotteetPalvelut::model()->findAll($criteria), 'id', 'nimike'), 
	array('empty'=>'Valitse tuote/palvelu','class'=>'form-control valitseTuote'));
	?>
	<span class="text-danger">Tuotteet jolla yksikkö "kk"</span>
	</div>
      </div>
      
      <div class="row section fill mb5">
      <div class="col-sm-6">

      </div>
      <div class="col-sm-6">
	<br>
        	<b class="btn btn-default btn-lg pull-right luoRiviTunti" jakso="kk"><?php echo Yii::t('main', 'Luo rivit'); ?></b>
      </div>
      </div>

   </div>
  </div>
 </div>
</div>




	<input type="hidden" class="form-control" id="kohteistaRivit" readonly><br>
	<div id="tuntienTulos"></div>

	<?php if(!isset($model->id)) : ?> 
	<div id="ilmoitusAllennusta"></div>
	<?php endif; ?> 

<br>


	<!-- Digisten -->
        <?php if(isset($_GET['jv']) and $_GET['jv'] > 0) : ?>
		<input type="hidden" id="tr_rivit_jarjestelmavalvojat" value="1">
		<input type="hidden" id="jv_maara" value="<?=$_GET['jv']?>">
        <?php endif; ?>
        <?php if(isset($_GET['edico_tilaus_id']) and $_GET['edico_tilaus_id'] > 0) : ?>
		<input type="hidden" id="edico_tilaus_id" value="<?=$_GET['edico_tilaus_id']?>">
        <?php endif; ?>
	<!-- Digisten -->

	<!-- l_asiakkaat -->
        <?php if(isset($_GET['asiakasnumero']) and $_GET['asiakasnumero'] > 0) : ?>
		<input type="hidden" id="asiakasnumero" value="<?=$_GET['asiakasnumero']?>">
        <?php endif; ?>
	<!-- l_asiakkaat -->

<div id="rivit" class="table-responsive">
<TABLE class="table well" id="TableRivit">

     <TR>
     <thead class="myBgColors">
	<TH style="width:1%"><span id="uusiRivi" class="link" style="font-size: 150%;"><i class="fa fa-plus-square"></i></span></TH>
	<TH class="col-sm-2">Tuote/Palvelu</TH>
	<TH class="col-sm-1">Määrä</TH>
	<TH class="col-sm-1">Yksikkö <span class="btn btn-primary btn-xs myBgColors muokaValiko" for="laskutus_yksikko"><i class="fa fa-pencil-square-o"></i></span></TH>
	<TH class="col-sm-1">Hinta</TH>
	<TH class="col-sm-1">ALV %</TH>
	<TH class="col-sm-1">ALV</TH>
	<TH class="col-sm-1">Ale %</TH>
	<TH class="col-sm-1">Veroton</TH>
	<TH class="col-sm-1">Yhteensä</TH>
	<TH class="col-sm-1">Viesti</TH>
     </thead>
     </TR>

     <tbody>
     <?php 
	$num = 0;
	if(isset($model->id)){
		foreach($laskunRivit as $rivi){ 
			$num++;
			echo $this->renderPartial("//lasku/tr_rivi_update",array('num'=>$num,'rivi'=>$rivi));
		}
	}
	if(isset($_POST['tr_rivit'])){
		$kohde_ids = [];
		foreach(json_decode($_POST['tr_rivit'], true) as $arr)
			$kohde_ids[$arr['kohde']] = $arr['kohde']; 
		$hinnat = [];
		foreach($kohde_ids as $kohde)
			$hinnat[$kohde] = $this->getHintaForKohde($kohde);

		foreach(json_decode($_POST['tr_rivit'], true) as $arr){ 
			$num++;
			echo $this->renderPartial("//lasku/tr_rivit_tyhja",[
				'num'=>$num, 
				'arr'=>$arr, 
				'hinnat' => $hinnat, 
				'tuotePalvelu' => $_POST['tp_palvelu']
			]);
		}
	}
     ?>
     </tbody>

     <tfoot>
     <TR>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD></TD>
	<TD><input type="text" class="form-control" size="10" name="Lasku[yhteensa_total_verot]" id="yhteensa_total_verot" readonly></TD>
	<TD></TD>
	<TD><input type="text" class="form-control" size="10" name="Lasku[yhteensa_total_veroton]" id="yhteensa_total_veroton" readonly></TD>
	<TD><input type="text" class="form-control" size="10" name="Lasku[yhteensa_total]" id="yhteensa_total" readonly></TD>
	<TD></TD>
     </TR>
     </tfoot>
</TABLE>
</div>

<br>
	<div id="hinnoitelu"></div>

<br><br><br><br><br><br>

	<div class="section fill mb5 subm">
		<?php if(!isset($model->id) or $model->tilanne == '0') : ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Tallenna' : 'Tallenna',array('class'=>'btn  btn-primary myBgColors')); ?>
		<?php endif; ?>


		<?php /* if(
			isset($model->id) 
		) : ?>
		<a href="lasku_pdf?id=<?php echo $model->id; ?>" target="_blank" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Esikatselu'); ?></a>
		<?php endif; */?>

		<?php if(isset($model->id) and $model->tilanne == '1' and $asetukset->palvelu_tyyppi == 1) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&finvoice=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä finvoice (POSTITA.FI)'); ?></a>
		<?php endif; ?>


  <?php 
  if(isset($model->id) 
 	and $model->tilanne == 1
	and $asetukset->palvelu_tyyppi == 1
	and $model->tilanne != 999 // mitatoiny, eli ei arvostele
  ){
  echo '<a href="finvoice?id='.$model->id.'&laskutus='.$model->laskutus.'" class="btn btn-success btn-group myBgColors">'.Yii::t('main','Luo POSTITA:ssa (ei viellä lähetä)').'</a>';
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

		<?php
		if (isset($model->id) && $model->tilanne == 1 && $asetukset->palvelu_tyyppi == 5) {
			echo CHtml::link('Lähetä', ['finvoice', 'id' => $model->id, 'procountor' => true, 'merkitseLahetettavaksi' => true], ['class' => 'btn btn-success btn-group myBgColors']);
		}
		?>

		<?php if(isset($model->id) 
			and $model->tilanne == 1 
			and $asetukset->palvelu_tyyppi == 4
			and $asetukset->netvisor_kaytto == 1
			and $model->netvisorkey == 0
			//and $model->laskun_nimetys != "Hyvityslasku"
			and $model->tilanne != 999
		): ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&lahetaNetvisor=true" class="btn  btn-success btn-group myBgColors" data-toggle="tooltip" data-placement="top" title="<?php echo Yii::t('main', 'Lähetä Netvisoriin'); ?>"><?php echo Yii::t('main','Lähetä'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) 
			and $model->tilanne == 1 
			and empty($model->trust_jobid) 
			and $asetukset->palvelu_tyyppi == 2 
			and $model->laskun_nimetys != "Hyvityslasku"
			and $model->tilanne != 999
		): ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&finvoiceTrust=true" class="btn  btn-success btn-group myBgColors" data-toggle="tooltip" data-placement="top" title="<?php echo Yii::t('main', 'Lähetä Trustiin'); ?>"><?php echo Yii::t('main','Lähetä'); ?></a>
		<?php endif; ?>

		<?php $procountor_param = $asetukset->palvelu_tyyppi == 5 ? '&procountor=1' : ''; ?>
		<?php if(isset($model->id) and $model->tilanne == '0') : ?>
			<a href="finvoice?id=<?php echo $model->id; ?>&hyvaksyminen=true<?php echo $procountor_param; ?>" class="btn btn-success btn-group myBgColors" id="hyvaksytaan_lasku"><?php echo Yii::t('main','Hyväksy'); ?></a>
		<?php endif; ?>

		<?php if(
			isset($model->id)
			and $asetukset->palvelu_tyyppi != 4
			and $asetukset->palvelu_tyyppi != 5
			and $model->tilanne != 999
		) : ?>
		<a href="lasku_pdf?id=<?php echo $model->id; ?>&muistutuslasku=true" target="_blank" class="btn  btn-primary btn-group myBgColors"><?php echo Yii::t('main','Maksumuistutus'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) 
			and $model->laskun_nimetys != "Hyvityslasku" 
			//and $asetukset->palvelu_tyyppi == 2
			//and $model->trust_jobid != ''
			and $model->tilanne != 999
			)
		: ?>
		<a href="hyvityslasku?id=<?php echo $model->id; ?>" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Hyvityslasku'); ?></a>
		<?php endif; ?>

		<?php if(isset($model->id) 
			and $model->laskun_nimetys == "Hyvityslasku"
			and $asetukset->palvelu_tyyppi == 2
			//and $model->tilanne != 98
			and $model->trust_jobid != ''
			and $model->tilanne != 999
			) 
		: ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&hyvityslasku=true&refundtojobid=<?php echo $model->trust_jobid; ?>" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä Hyvityslasku'); ?></a>
		<?php endif; ?>

		<?php /* if(
			isset($model->id) 
			and $model->tilanne == '1' 
			and $asetukset->palvelu_tyyppi == 1
			and $model->tilanne != 999
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&pdf=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä PDF (POSTITA.FI)'); ?></a>
		<?php endif; */ ?>


		<?php if(
			isset($model->id) 
			and $model->tilanne != 0 
			and $asetukset->palvelu_tyyppi != 2
			and $asetukset->palvelu_tyyppi != 4
			and $asetukset->palvelu_tyyppi != 5
			and $model->tilanne != 999
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&lahetaSahkopostilla=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä sähköpostilla'); ?></a>
		<?php endif; ?>


		<?php if(
			isset($model->id) 
			and $model->tilanne != 0 
			and $asetukset->palvelu_tyyppi != 2
			and $asetukset->palvelu_tyyppi != 4
			and $asetukset->palvelu_tyyppi != 5
			and !empty($asetukset->trust_url)
			and !empty($asetukset->trust_cid)
			and !empty($asetukset->trust_api)
			and $model->tilanne != 999
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&finvoiceTrust=true&jobtype=2" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Lähetä perintään'); ?></a>
		<?php endif; ?>

		<?php if(
			isset($model->id)
			and $model->tilanne != 999
		) : ?>
		<a href="finvoice?id=<?php echo $model->id; ?>&mitatointi=true<?php echo $procountor_param; ?>" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Mitätöi'); ?></a>
		<a href="finvoice?id=<?php echo $model->id; ?>&kopio=true" class="btn  btn-success btn-group myBgColors"><?php echo Yii::t('main','Kopio'); ?></a>
		<?php endif; ?>

		<?php /*
		<hr>
		<p><b>Laskun tila: </b><?php echo $this->tilanneCheck($model,null); ?></p>
		<br> */ ?>

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



<?php // Trustpoint PDF
if(isset($model->id))
{

  $exists = Yii::app()->basePath."/../tiedostot/laskut/trust/".Yii::app()->user->domain;
  $pathForTrust = Yii::app()->basePath;
  $pdfFile = "/../tiedostot/laskut/trust/".Yii::app()->user->domain."/".$model->id.'.pdf';

  if (!file_exists($exists)) {
  	mkdir($exists, 0777, true);
  }

	$trust_ws_cid = $asetukset->trust_ws_cid;
	$trust_ws_salasana = $asetukset->trust_ws_salasana;
	$trust_cid = $asetukset->trust_cid;
	$trust_api = $asetukset->trust_api;
	$trust_ws_api_url = $asetukset->trust_ws_api_url;

  if($asetukset->palvelu_tyyppi == 2 and !empty($model->trust_jobid) and !file_exists($pathForTrust.$pdfFile)
  and !empty($trust_ws_cid) and !empty($trust_ws_salasana) and !empty($trust_ws_api_url))
  {

	$trust_jobid = $model->trust_jobid;

	$client = new SoapClient($trust_ws_api_url.'/?wsdl', array(
						'login'=>$trust_ws_cid,
						'password'=>$trust_ws_salasana));

	$result = $client->doLogin(array('cid'=>$trust_cid, 'apiCode'=>$trust_api, 'apiVersion'=>'1'));
	$sessionId = $result['authResponse']->sessionId;

	//echo $sessionId.'<br>';
	//echo $trust_jobid.'<br>';

	$pdf = $client->getJobPdf($sessionId, array('id'=>$trust_jobid, 'idType'=>'jobid'));

	if(!empty($pdf['getPdfResponse']))
	file_put_contents($pathForTrust.$pdfFile, base64_decode($pdf['getPdfResponse']));


	//echo '<pre>';
	//print_r($result);
	//print_r($pdf);
	//print_r($client->__GetFunctions());
	//echo '</pre>';

  }
  if(file_exists($pathForTrust.$pdfFile))
  echo '<a href="'.$pdfFile.'" class="btn btn-primary" target="_blank">PDF</a>';

}
// Trustpoint PDF ?>

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

if(parseInt($("#forTilanne").val()) !== 0){
  $("input").prop("disabled", true);
  $("select").prop("disabled", true);
  $("textarea").prop("disabled", true);
  $(".poista").remove();
  $("#uusiRivi").remove();
}

if($("#modelID").val() != '1'){
    var rivi = $("#samaRivi").html();
    var rowCount = $('table#TableRivit tbody tr').length;

    if( $('#Lasku_digisten_tunnit_id').length ){
	var digisten_tunnit_id = 0;
	digisten_tunnit_id = parseInt($('#Lasku_digisten_tunnit_id').val());
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/tr_rivit_tyhja',
           type: "POST",
           data: {num : rowCount, digisten_tunnit_id : digisten_tunnit_id},
           success: function(html){
         	$("table#TableRivit tbody tr").last().after(html);
	  	Rivi();
		eachLaskenta();
           }
        });
    }

    if( $('#tr_rivit_jarjestelmavalvojat').length )
    {
    	rowCount = rowCount+1;
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/tr_rivit_jarjestelmavalvojat?jv='+$("#jv_maara").val(),
           type: "POST",
           data: {num : rowCount},
           success: function(html){
         	$("table#TableRivit tbody tr").last().after(html);
	  	Rivi();
		eachLaskenta();
           }
        });

    }
    /* <-- Edisco Tilaus */
    if( $('#edico_tilaus_id').length )
    {

	var edico_tilaus_id = $('#edico_tilaus_id').val();

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/edico_tilaus_get_asiakas?edico_tilaus_id='+edico_tilaus_id,
           type: "GET",
           success: function(data){
         	d = JSON.parse(data);
		console.log(d);
		if(d['asiakas_id'] && d['asiakas_id'] > 0)
		{
			$('#Lasku_as_nro').val(d['asiakas_id']).trigger('change');
		}
           }
        });

    	rowCount = rowCount+1;
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/tr_rivit_edico_tilaus',
           type: "POST",
           data: {num : rowCount, edico_tilaus_id : edico_tilaus_id },
           success: function(html){
        	$("table#TableRivit tbody tr").empty();
         	$("table#TableRivit tbody tr").last().after(html);
	  	Rivi();
		eachLaskenta();
           }
        });

    }
    /*   Edisco Tilaus --> */


}

$("#uusiRivi").click(function() {
    var rivi = $("#samaRivi").html();
    var rowCount = makeid();

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

function makeid()
{
    var text = "";
    var possible = "123456789";

    for( var i=0; i < 7; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

function jumpToPageBottom() {
    $('html, body').animate({scrollTop:1000}, 'slow');
    return false;
}


$(document).delegate("input, select","change keyup paste",function(){
    $("#hyvaksytaan_lasku").addClass('disabled');
});

$(document).delegate("table#TableRivit .valitseTuote","change",function(){

    var tuoteID = $(this).val();
    var num = $(this).attr("num");
    var asiakas_nro = $("#Lasku_as_nro option:selected").val();
    if(!asiakas_nro && '<?=$model->as_nro?>' !== '')
    {
	asiakas_nro = '<?=$model->as_nro?>';
    }

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/valitsetuote',
           type: "POST",
           data: { tuoteID : tuoteID, asiakas_nro : asiakas_nro },
           success: function(data){
		var sp = JSON.parse(data);

		if(sp['id'])
		{
			//$("#kpl_"+num).val(1);
			$("#tkoodi_"+num).val(sp['tuotenimi']);
			$("#hinta_"+num).val(parseFloat(sp['hinta_alv_0']));
			$("#hinnasto_rivi_id_"+num).val(sp['hinnasto_rivi_id']);
			$("#hinta_"+num).closest('tr').find('.hinnaston_otsikko').attr("title", sp['hinnaston_otsikko']);
			$("#yksikko_"+num+" option[value="+sp['yksikko']+"]").attr('selected','selected');
			$("#alv_"+num+" option[value="+sp['alv']+"]").attr('selected','selected');
			$("#tuoteID_"+num).val(sp['id']);
		}

		eachLaskenta();
		//console.log(data)
           }
        });

});

$(document).delegate("table#TableRivit .valitseTuote_tuoteonly","change",function(){

    var tuoteID = $(this).val();
    var num = $(this).attr("num");
    var asiakas_nro = $("#Lasku_as_nro option:selected").val();
    if(!asiakas_nro && '<?=$model->as_nro?>' !== '')
    {
	asiakas_nro = '<?=$model->as_nro?>';
    }

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/valitsetuote',
           type: "POST",
           data: { tuoteID : tuoteID, asiakas_nro : asiakas_nro },
           success: function(data){
		var sp = JSON.parse(data);

		if(sp['id'])
		{
			$("#tkoodi_"+num).val(sp['tuotenimi']);
			$("#tuoteID_"+num).val(sp['id']);
		}

		eachLaskenta();
		//console.log(data)
           }
        });

});

$(document).delegate(".poista","click",function(){
	$(this).closest('tr').remove();
	yhteensaTotal();
});

Rivi();
function Rivi(){

  $(".onlyDigits ").attr('type', 'number').attr('step', 'any');

}

 $('#Lasku_alv_muoto').change(function(){
  eachLaskenta();
 });

  eachLaskenta();

var aleAsiakkaasta = '';
function eachLaskenta(){

  var alvsis = $('#Lasku_alv_muoto').val();
  $("#rivit input").each(function() {

	var hinta_alv_0 = 0;
	var alv = 0;
	var kpl = 0;
	var ale = 0;

	var inputKenta = $(this).attr("id").split("_");
	if($("#hinta_"+inputKenta[1]).val()) { hinta_alv_0 = parseFloat($("#hinta_"+inputKenta[1]).val()) };
	if($("#alv_"+inputKenta[1]).val()) { alv = parseFloat($("#alv_"+inputKenta[1]).val()) };
	if($("#kpl_"+inputKenta[1]).val()) { kpl = parseFloat($("#kpl_"+inputKenta[1]).val()) };
	if($("#ale_"+inputKenta[1]).val()) { ale = parseFloat($("#ale_"+inputKenta[1]).val()) };


	inputKenta[1] = parseFloat(inputKenta[1], 10);

	if(ale > 0)
	hinta_alv_0 = hinta_alv_0-((hinta_alv_0/100)*ale);

	if( alvsis == '0'){
		var laske = (hinta_alv_0*kpl)/100*alv;
		var veroton = hinta_alv_0*kpl;
		var yhteensa = laske+veroton;
	}
	if( alvsis == '1'){
		var yhteensa = hinta_alv_0*kpl;
		var jakaa = '1.'+alv;
		var l = yhteensa/parseFloat(jakaa);
		var veroton = l;
		var laske = yhteensa-veroton;
	}

	$("#hinta_alv_"+inputKenta[1]).val(laske.toFixed(2));
	$("#veroton_"+inputKenta[1]).val(veroton.toFixed(2));
	$("#yhteensa_alv_"+inputKenta[1]).val(yhteensa.toFixed(2));

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


$(document).delegate('#rivit input[type="number"]','keyup, change',function(){
  	eachLaskenta();
    	yhteensaTotal();
});

var getkohdeT = '';
$("#Lasku_as_nro").change(function() {
    var l_asiakkaat = '<?=((isset($_GET["l_asiakkaat"]))? "true" : "false") ?>';
    var asiakas = $("#Lasku_as_nro option:selected").val();
    var asiakas_id = $("#Lasku_as_nro option:selected").attr('asiakas_id');
    if(!asiakas)
    {
	alert("Asiakasnumero puuttuu");
	return false;
    }
	if(l_asiakkaat == "false")
		$("#kalut").show('slow');

	$("#tuotteet_palvelut_muoto").show('slow');
	palvelu_muoto();

	if(l_asiakkaat == "false"){
        $.ajax({
           url: 'etsikohde?asiakasnumero='+asiakas,
	   async : false,
           success: function(data){
		var spdata = JSON.parse(data);
               	//console.log(spdata);

		if(spdata['is_true'] == true)
		{

			asiakas_id = spdata['asiakas_id'];
			$("#getkohdeT").html(spdata['kohteet']);
			getkohdeT = spdata['kohteet'];
			$("#getkohdeKK").html(spdata['kohteet']);
			$("#tuntiKalut").show();

			multiselectLaatikko();

		} else {
			$("#tuntiKalut").hide();
			$("#kkKalut").hide();
		}

           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });
	} // l_asiakkaat

        $.ajax({
           url: 'etsiasiakas?id='+asiakas_id,
           success: function(data){
               	//console.log(data);
		var sp = JSON.parse(data).split("//");
		$(".tyyppi").show('slow');


		laskutus(sp[0]);
		if(sp[0]){
		  $("#Lasku_laskutus").val(sp[0]);
		}
		if(sp[1]){
		  $("#Lasku_maksuehto").val(sp[1]);
		}


		if(sp[2]){
		  var spR = sp[2].split("**");
		  $("#Lasku_tyyppi").val(spR[0]);
		
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


		    $("#Lasku_osoite").val(sp[3])
		    $("#Lasku_postinumero").val(sp[4])
		    $("#Lasku_toimipaikka").val(sp[5])
		    $("#Lasku_yhteyshenkilo").val(sp[6])
		    $("#Lasku_puhelin").val(sp[7])
		    $("#Lasku_erapaiva").val(sp[9])
		    $("#Lasku_v_tunnus").val(sp[10])
		    $("#Lasku_verkkolaskuosoite").val(sp[11])
		    $("#Lasku_muistutuslasku_auto").val(sp[12]);
		    $("#Lasku_kirjeenluokka").val(sp[13]);
		    $("#Lasku_sahkoposti").val(sp[14])
		    $("#Lasku_viivastyskorko").val(sp[15])
		    $("#Lasku_netvisor_dimension_name").val(sp[16] + '//' + sp[17])

           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });

});


var t_palvelut_hinnastosta = $("#palvelu").html();
var kk_palvelut_hinnastosta = $("#kk_palvelu").html();
$("#Lasku_tuotteet_palvelut_muoto").change(function() {
	palvelu_muoto();
});


function palvelu_muoto(){
	if( $("#Lasku_tuotteet_palvelut_muoto").val() == '1' ){
		$("#palvelu").html('<option value="1">h</option><option value="3">kpl</option>').addClass('for-muoto-1');
		$("#kk_palvelu").html('<option value="2">kk</option>');
		etsiKohteetByYksikkoPalveluMuoto1(1);
	}
	if( $("#Lasku_tuotteet_palvelut_muoto").val() == '0' ){
		$("#palvelu").html(t_palvelut_hinnastosta).removeClass('for-muoto-1');
		$("#kk_palvelu").html(kk_palvelut_hinnastosta);
		if( getkohdeT !== '' ){	
			$("#getkohdeT").html(getkohdeT);
			$("#getkohdeKK").html(getkohdeT);
			multiselectLaatikko();
		}
	}
}

$(document).delegate(".for-muoto-1","change",function(){
	etsiKohteetByYksikkoPalveluMuoto1($(this).val());
});

function etsiKohteetByYksikkoPalveluMuoto1(hinta_tyyppi){

	var asiakas = $("#Lasku_as_nro option:selected").val();
	if(!asiakas){ alert('Valitse asiakas'); return false; }
        $.ajax({
           url: 'etsikohde_by_yksikko?asiakasnumero='+ asiakas +'&hinta_tyyppi='+ hinta_tyyppi,
	   type : 'POST',
	   //data : {}
           success: function(data){
		var spdata = JSON.parse(data);
               	//console.log(spdata);
		if(spdata['is_true'] == true)
		{
			$("#getkohdeT").html(spdata['kohteet']);
			//$("#getkohdeKK").html(spdata['kohteet']);
			multiselectLaatikko();

			$("#getkohdeT").find(".multiselect").removeClass('btn-default').addClass('btn-success').text('Kohteet päivitetty');
			setTimeout(function() { 
			$("#getkohdeT").find(".multiselect").removeClass('btn-success').addClass('btn-default').text('Valitse kohde');
			}, 3000);

		} else {
			$("#getkohdeT").html('<br><p><span class="btn btn-danger btn-block">Ei kohteitta.</span></p>');
		}
           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });

        $.ajax({
           url: 'etsikohde_by_yksikko?asiakasnumero='+ asiakas +'&hinta_tyyppi=2',
	   type : 'POST',
	   //data : {}
           success: function(data){
		var spdata = JSON.parse(data);
               	//console.log(spdata);
		if(spdata['is_true'] == true)
		{
			$("#getkohdeKK").html(spdata['kohteet']);
			multiselectLaatikko();

			$("#getkohdeKK").find(".multiselect").removeClass('btn-default').addClass('btn-success').text('Kohteet päivitetty');
			setTimeout(function() { 
			$("#getkohdeKK").find(".multiselect").removeClass('btn-success').addClass('btn-default').text('Valitse kohde');
			}, 3000);


		} else {
			$("#getkohdeKK").html('<br><p><span class="btn btn-danger btn-block">Ei kohteitta.</span></p>');
		}
           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });
}

function multiselectLaatikko(){
			// <--multiselect
			$('.etsikohde_alasvetovaliko').multiselect({
				//inheritClass: true,
				//enableFiltering: true,
			        includeSelectAllOption: true,
				nonSelectedText: '<?php echo Yii::t("main", "Valitse kohde"); ?>',
				selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
				allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
				nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
				numberDisplayed: 0,
				buttonWidth: '100%',
			        maxHeight: 300,
			});
			//    multiselect -->
}

$(".luoRiviTunti").click(function() {

	var jakso = $(this).attr("jakso");
	var kuukausi = $(this).closest('.panel-body').find("#kuukausi").val();
	var from = $(this).closest('.panel-body').find("#from").val();
	var to = $(this).closest('.panel-body').find("#to").val();
	var valinnat = $(this).closest('.panel-body').find('.etsikohde_alasvetovaliko').val();
	var tuotePalvelu = $(this).closest(".panel-body").find('.valitseTuote option:selected').val();
	var tuotePalveluFor = $(this).closest(".panel-body").find('.etsikohde_alasvetovaliko option:selected').attr('for');

	var rivien_teko = ('<?=$asetukset->rivien_teko?>' == '0')? 'perkohde' : 'perpvmkohde';
	//console.log(tuotePalvelu);

	if (from  === '' && jakso == 'tunti') 
	{
	     $(this).closest('.panel-body').find("#from").css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (to  === '' && jakso == 'tunti') 
	{
	     $(this).closest('.panel-body').find("#to").css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (kuukausi  === '' && jakso == 'kk') 
	{
	     $(this).closest('.panel-body').find("#kuukausi").css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (tuotePalvelu  === '' && $("#Lasku_tuotteet_palvelut_muoto").val() == '0' ) 
	{
	     $(this).closest(".panel-body").find('.valitseTuote').css({"border" : "2px #f14010 solid"}).focus();
	     return false;
	}
	if (!valinnat) 
	{ 
	    alert('Valitse kohde')
	    return false;

	} 

	if(rivien_teko == 'perpvmkohde' && tuotePalveluFor == 'kohde')
	{
		//console.log('perpvmkohde?from='+from+'&to='+to+'&for='+tuotePalveluFor+'&valinnat='+valinnat)
	        $.ajax({
	           url: 'perpvmkohde?from='+from+'&to='+to+'&for='+tuotePalveluFor+'&valinnat='+valinnat,
	           success: function(data){
	               	//console.log(data);
			data = JSON.parse(data);
			index = 0;
			$.each(data, function( pvm, kohde_arr ) {
				$.each(kohde_arr, function( kohde_id, yhteensa ) {
					index += 1;
					RivienLuonti(index, kohde_id, rivien_teko, pvm, pvm, yhteensa, jakso, kuukausi, tuotePalvelu, tuotePalveluFor)
				});
			});
	           },
	           error: function(XMLHttpRequest, textStatus, errorThrown){
	               	console.log(XMLHttpRequest);
		   }
	        });

	} else {
		pyyntoRiville(jakso, kuukausi, valinnat, rivien_teko, from, to, tuotePalvelu, tuotteet_palvelut_muoto, tuotePalveluFor);
	}
});

function pyyntoRiville(jakso,kuukausi,valinnat,rivien_teko,from,to,tuotePalvelu,tuotteet_palvelut_muoto,tuotePalveluFor){
	$("#tuntienTulos").removeClass("alert bg-danger").html('');
	var kpl = 1;
	var yksikko = 'kpl';
	var hinta = 0;
	$.each(valinnat, function( index, value ) {
		RivienLuonti(index, value, rivien_teko, from, to, 0, jakso, kuukausi, tuotePalvelu, tuotePalveluFor)
	});
	jumpToPageBottom();
}

function RivienLuonti(index, value, rivien_teko, from, to, yhteensa, jakso, kuukausi, tuotePalvelu, tuotePalveluFor){

		var tuotteet_palvelut_muoto = parseInt($("#Lasku_tuotteet_palvelut_muoto").val());

		var postdata = { 
			rivien_teko : rivien_teko,
			jakso : jakso, 
			kuukausi : kuukausi, 
			asiakasnumero : $("#Lasku_as_nro option:selected").val(), 
			from : from, to : to, 
			tuotePalvelu : tuotePalvelu, 
			tuotteet_palvelut_muoto : tuotteet_palvelut_muoto
		};
	        $.ajax({
	           url: 'luoKohteista?id='+value+'&for='+tuotePalveluFor, // +'&tunnit='+yhteensa
		   type: 'POST',
		   data: postdata,
	           success: function(data){
	               	console.log(data);
			data = JSON.parse(data);

			if(tuotteet_palvelut_muoto == 0 && parseInt(data['rivi_kpl']) == 0 && data['yksikko'] !== 'kk')
			{
				$("#tuntienTulos").addClass("alert bg-danger").append('<p><b>Ei löydy tuntia osoitteesta: </b>' + data['osoite'] + '</p>');
				return true;
			}

			   var num = 0;
			   num = $("table#TableRivit tbody tr").length+index;
			   var sendData = { 
					kohde_id : value,
					num : num,
					from : data['from'],
					to : data['to'],
					kpl : data['kpl'],
					hinta : data['hinta'],
					alv : data['alv'],
					hinnasto_rivi_id : data['hinnasto_rivi_id'],
					yksikko : data['yksikko'],
					free_text : data['free_text'],
					rivi_lisays : data['free_text'],
					tuotePalvelu : tuotePalvelu,
					tuotteet_palvelut_muoto : tuotteet_palvelut_muoto
			   };

	        	   $.ajax({
		              url: 'tr_rivit',
			      type: 'POST',
			      data: sendData,
		              success: function(data){
				console.log(value);
				$("table#TableRivit tbody tr").last().after(data);
				$("#rivit").show('slow');
				$(".subm").show('slow');

				Rivi();
				eachLaskenta();
	
		              },
		              error: function(XMLHttpRequest, textStatus, errorThrown){
		               	console.log(XMLHttpRequest);
			      }
		           });
	

	           },
	           error: function(XMLHttpRequest, textStatus, errorThrown){
	               	console.log(XMLHttpRequest);
		   }
	        });
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
    if((value == 'verkkolasku') && (parseInt($('#palvelu_tyyppi').val()) !== 3)){
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


// hinnoitelu
$(document).delegate(".etsikohde_alasvetovaliko","change",function(){

  var jakso = $(this).closest('.panel-body').find(".luoRiviTunti").attr("jakso");
  var kuukausi = $(this).closest('.panel-body').find("#kuukausi").val();
  var from = $(this).closest('.panel-body').find("#from").val();
  var to = $(this).closest('.panel-body').find("#to").val();

  $('#hinnoitelu').hide('370').html('');
  console.log($(this, 'option:selected').val());

  if( $(this, 'option:selected').val() )
  {
  $.each($(this, 'option:selected').val(), function( index, value ) {

	var thisVal = value;
        $.ajax({
           url: 'kohteen_tieto?id='+ thisVal,
	   type: 'POST',
	   data: { jakso : jakso, kuukausi : kuukausi, from : from, to : to },
           success: function(data){
               	console.log(data);
		var d = JSON.parse(data);
		if(d['return']){
		 $('#hinnoitelu').append(d['return']).show('370');
		}

           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });

   });
   }
});

$("#osoiteHaku").keyup(function() {
    var thisVal = $(this).val();

    if(thisVal.length > 4)
    {
        $.ajax({
           url: 'osoite_haku',
	   type: 'POST',
	   data: { word : thisVal },
           success: function(data){
		var data = JSON.parse(data);
		$("#osoiteHakuResult").html(data);
           },
           error: function(XMLHttpRequest, textStatus, errorThrown){
               	console.log(XMLHttpRequest);
	   }
        });
    }
});


$(document).delegate("#loytyiOsoitteet","change",function(){
    var thisVal = $(this).val();
    $("#Lasku_as_nro").val(thisVal).change();

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

    /*  <-- l_asiakkaat */
    if( $('#asiakasnumero').length )
    {
	$('#Lasku_as_nro').val($('#asiakasnumero').val()).trigger('change');
    }

});
</script>

