<?php
/* @var $this TyosopimuksetController */
/* @var $model Tyosopimukset */
/* @var $form CActiveForm */
?>



<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyosopimukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->hiddenField($model,'tid',array('size'=>60,'maxlength'=>70, 'class'=>'form-control')); ?>
	<?php echo $form->errorSummary($model); ?>


<!-- hattu -->
<div class="row">
     <div class="col-sm-4">
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyontekijat'); ?>
		<?php
		$site = Yii::app()->createController('Site');
		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
			'tyontekija', // name
			'form-control', //class
			'tyontekijat', // id
			null, //selected
			1 // aktiivinen
		);
		echo $tyontekiatLista;
		?>
		<?php echo $form->error($model,'tyontekijat'); ?>
	</div>
     </div>
     <div class="col-sm-4">
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'template'); ?>
		<?php
		$tmp_list = array();
		foreach(glob(Yii::app()->baseUrl.$this->templates_polkku().'/*.docx') as $file) 
		{
			$explNimi = explode("/",$file);
		 	$tmp_list[end($explNimi)] = end($explNimi);
		}
		?>
		<?php
        		echo $form->dropDownList($model, 'template', $tmp_list,
			array('empty'=>'Valitse', 'class'=>'form-control'));
		?>
		<?php echo $form->error($model,'template'); ?>
	</div>

     </div>
</div>
<hr>
<!-- hattu -->


<div class="row">
  <div class="col-sm-6">

	<legend><?php echo Yii::t('main', 'Työnantaja'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyonantaja'); ?>
		<?php echo $form->textField($model,'tyonantaja',array('size'=>60,'maxlength'=>70, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyonantaja'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>7,'maxlength'=>7, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'TyonantajanEdustaja'); ?>
		<?php echo $form->textField($model,'TyonantajanEdustaja',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'TyonantajanEdustaja'); ?>
	</div>


  </div><div class="col-sm-6">

	<legend><?php echo Yii::t('main', 'Työntekijä'); ?></legend>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_email'); ?>
	</div>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>70, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_katuosoite'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_pnumero'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_ptoimipaikka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_puh'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tekijan_henkilotunnus'); ?>
	</div>

  </div>
</div><!-- form -->


<br>
<?php
	if(isset($model->sopimus) and $model->sopimus == 'ToistaVoimaSopimus') $ToistaVoimaSopimusChecked = 'checked'; else $ToistaVoimaSopimusChecked = '';
	if(isset($model->sopimus) and $model->sopimus == 'MaaraSopimus') $MaaraSopimusChecked = 'checked'; else $MaaraSopimusChecked = '';
?>

<div class="row">
  <div class="col-sm-6">

	<legend><input type="radio" name="Tyosopimukset[sopimus]" value="ToistaVoimaSopimus" <?php echo $ToistaVoimaSopimusChecked; ?>> <?php echo Yii::t('main', 'Toistaiseksi voimassa oleva työsopimus'); ?></legend>

	<div class="row">
  	 <div class="col-sm-6">

	  <div class="section fill mb5">
		<?php echo $form->labelEx($model,'ToistaVoimaSopimus'); ?>
		<?php echo $form->textField($model,'ToistaVoimaSopimus',array('size'=>60,'maxlength'=>100, 'class'=>'datepickerFI form-control')); ?>
		<?php echo $form->error($model,'ToistaVoimaSopimus'); ?>
	  </div>

	 </div>
	</div>

  </div><div class="col-sm-6">

	<legend><input type="radio" name="Tyosopimukset[sopimus]" value="MaaraSopimus" <?php echo $MaaraSopimusChecked; ?>> <?php echo Yii::t('main', 'Määräaikainen työsopimus'); ?></legend>

	<div class="row">
  	 <div class="col-sm-6">

	  <div class="section fill mb5">
		<?php echo $form->labelEx($model,'MaaraVoimaSopimusAlkaa'); ?>
		<?php echo $form->textField($model,'MaaraVoimaSopimusAlkaa',array('size'=>60,'maxlength'=>100, 'class'=>'datepickerFI form-control')); ?>
		<?php echo $form->error($model,'MaaraVoimaSopimusAlkaa'); ?>
	  </div>

  	 </div><div class="col-sm-6">

	  <div class="section fill mb5">
		<?php echo $form->labelEx($model,'MaaraVoimaSopimusPaattyy'); ?>
		<?php echo $form->textField($model,'MaaraVoimaSopimusPaattyy',array('size'=>60,'maxlength'=>100, 'class'=>'datepickerFI form-control')); ?>
		<?php echo $form->error($model,'MaaraVoimaSopimusPaattyy'); ?>
	  </div>

	 </div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'peruste'); ?>
		<?php echo $form->textArea($model,'peruste',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'peruste'); ?>
	</div>

  </div>
</div><!-- form -->


<br>

	<legend><?php echo Yii::t('main', 'Työsopimus'); ?></legend>

<div class="row">
  <div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'koeaika'); ?>
		<?php echo $form->textField($model,'koeaika',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'koeaika'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Tyotehtavat'); ?>
		<?php echo $form->textArea($model,'Tyotehtavat',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Tyotehtavat'); ?>
	</div>

  </div><div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'SoveltavaSopimus'); ?>
		<?php echo $form->textField($model,'SoveltavaSopimus',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'SoveltavaSopimus'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyonSuorittamisPaikka'); ?>
		<?php echo $form->textArea($model,'tyonSuorittamisPaikka',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyonSuorittamisPaikka'); ?>
	</div>

  </div>
</div><!-- form -->


<br>

	<legend><?php echo Yii::t('main', 'Palkanmaksu'); ?></legend>


<?php
	if(isset($model->PalkanMaaraytymisperuste) and $model->PalkanMaaraytymisperuste == 'Aikaperuste') $AikaperusteChecked = 'checked'; else $AikaperusteChecked = '';
	if(isset($model->PalkanMaaraytymisperuste) and $model->PalkanMaaraytymisperuste == 'Suoritusperuste') $SuoritusperusteChecked = 'checked'; else $SuoritusperusteChecked = '';
	if(isset($model->PalkanMaaraytymisperuste) and $model->PalkanMaaraytymisperuste == 'MuuMaksu') $MuuMaksuChecked = 'checked'; else $MuuMaksuChecked = '';
?>

<div class="row">
  <div class="col-sm-6">

	<p><b><?php echo Yii::t('main', 'Palkan määräytymisperuste'); ?></b></p>
	<input type="radio" name="Tyosopimukset[PalkanMaaraytymisperuste]" value="Aikaperuste" <?php echo $AikaperusteChecked; ?>> <?php echo Yii::t('main', 'Aikaperuste'); ?><br>
	<input type="radio" name="Tyosopimukset[PalkanMaaraytymisperuste]" value="Suoritusperuste"  <?php echo $SuoritusperusteChecked; ?>> <?php echo Yii::t('main', 'Suoritusperuste'); ?><br>
	

	<div class="form-inline">
	  <div class="form-group">
		<input type="radio" name="Tyosopimukset[PalkanMaaraytymisperuste]" value="MuuMaksu" <?php echo $MuuMaksuChecked; ?>> <?php echo Yii::t('main', 'Muu'); ?>&nbsp;
	  </div><div class="form-group">
		<?php echo $form->textField($model,'PalkanMaaraytymisperusteMuu',array('class'=>'form-control')); ?>
	  </div>
	</div>


  </div><div class="col-sm-6">

	<p><b><?php echo Yii::t('main', 'Työkokemus / kokemuslisiin oikeuttava aika'); ?></b></p>


	<div class="form-inline">
	  <div class="form-group">
		<?php echo $form->textField($model,'TyokokemusVuotta',array('size'=>3,'maxlength'=>20, 'class'=>'form-control')); ?> vuotta &nbsp;
	  </div><div class="form-group">
		<?php echo $form->textField($model,'TyokokemusKuu',array('size'=>3,'maxlength'=>20, 'class'=>'form-control')); ?> kuukautta työsuhteen alussa
	  </div>
	</div>

  </div>
</div><!-- form -->

<br>

<div class="row">
  <div class="col-sm-6">

	<p><b><?php echo Yii::t('main', 'Palkka'); ?></b></p>


	<div class="form-inline">
	  <div class="form-group">
		<label><?php echo Yii::t('main', 'Työsuhteen alussa'); ?></label><br>
		<?php echo $form->textField($model,'palkka_kk',array('size'=>10,'maxlength'=>20, 'class'=>'form-control')); ?> kk &nbsp;
	  </div><div class="form-group">
		<label><?php echo Yii::t('main', 'Palkkaluokka'); ?></label><br>
		<?php echo $form->textField($model,'Palkkaluokka',array('size'=>8,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->textField($model,'palkka_h',array('size'=>4,'maxlength'=>20, 'class'=>'form-control')); ?> /h
	  </div>
	</div>

	<br>


	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Luontaiseudut'); ?>
		<?php echo $form->textArea($model,'Luontaiseudut',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Luontaiseudut'); ?>
	</div>

  </div><div class="col-sm-6">

	<div class="form-inline">
	  <div class="form-group">
		<label><?php echo Yii::t('main', 'Raha arvo'); ?></label><br>
		<?php echo $form->textField($model,'Raha_arvo',array('size'=>8,'maxlength'=>70, 'class'=>'form-control')); ?>&nbsp;
	  </div><div class="form-group">
		<label><?php echo Yii::t('main', 'Verotusarvo'); ?></label><br>
		<?php echo $form->textField($model,'Verotusarvo',array('size'=>8,'maxlength'=>70, 'class'=>'form-control')); ?>&nbsp;
	  </div><div class="form-group">
		<label><?php echo Yii::t('main', 'Muu'); ?></label><br>
		<?php echo $form->textField($model,'palkka_muu2',array('size'=>8,'maxlength'=>70, 'class'=>'form-control')); ?>
	  </div>
	</div>





  </div>
</div><!-- form -->


<div class="row">
  <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Palkanmaksukausi'); ?>
		<?php echo $form->textField($model,'Palkanmaksukausi',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Palkanmaksukausi'); ?>
	</div>

  </div>
  <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Palkanmaksupaivat'); ?>
		<?php echo $form->textField($model,'Palkanmaksupaivat',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Palkanmaksupaivat'); ?>
	</div>

  </div>
  <div class="col-sm-4">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Palkka_tilille'); ?>
		<?php echo $form->textField($model,'Palkka_tilille',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Palkka_tilille'); ?>
	</div>

  </div>
</div><!-- form -->


<br>


	<legend><?php echo Yii::t('main', 'Työaika'); ?></legend>

<div class="row">
  <div class="col-sm-6">

	<p><b><?php echo Yii::t('main', 'Säännöllinen työaika'); ?></b></p>

	<div class="row">
	  <div class="col-sm-6">
	   <div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoaika_hvrk'); ?>
		<?php echo $form->textField($model,'tyoaika_hvrk',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoaika_hvrk'); ?>
	   </div>
	  </div><div class="col-sm-6">
	   <div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoaika_hvko'); ?>
		<?php echo $form->textField($model,'tyoaika_hvko',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoaika_hvko'); ?>
	   </div>
	 </div>
	</div>


	<div class="row">
	  <div class="col-sm-6">
	   <div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoaika_h_jakso'); ?>
		<?php echo $form->textField($model,'tyoaika_h_jakso',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoaika_h_jakso'); ?>
	   </div>
	  </div><div class="col-sm-6">
	   <div class="section fill mb5">
		<?php echo $form->labelEx($model,'tyoaika_vko_jaksossa'); ?>
		<?php echo $form->textField($model,'tyoaika_vko_jaksossa',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tyoaika_vko_jaksossa'); ?>
	   </div>
	 </div>
	</div>


	<div class="row">
	  <div class="col-sm-6">
	   <div class="section fill mb5">
		<?php echo $form->labelEx($model,'RuokataukonPituus'); ?>
		<?php echo $form->textField($model,'RuokataukonPituus',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'RuokataukonPituus'); ?>
	   </div>
	  </div><div class="col-sm-6">
	   <div class="section fill mb5">


	   </div>
	 </div>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'lomasta_sovittu'); ?>
		<?php echo $form->textArea($model,'lomasta_sovittu',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'lomasta_sovittu'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Salassapito'); ?>
		<?php echo $form->textArea($model,'Salassapito',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Salassapito'); ?>
	</div>


  </div><div class="col-sm-6">

	<br>
	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Muu_tyoaika'); ?>
		<?php echo $form->textArea($model,'Muu_tyoaika',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Muu_tyoaika'); ?>
	</div>

<?php
	if(isset($model->IrtisanomisaikaM) and $model->IrtisanomisaikaM == 'LainMukaan') $LainMukaanChecked = 'checked'; else $LainMukaanChecked = '';
	if(isset($model->IrtisanomisaikaM) and $model->IrtisanomisaikaM == 'TyoEhtoMukaan') $TyoEhtoMukaanChecked = 'checked'; else $TyoEhtoMukaanChecked = '';
	if(isset($model->IrtisanomisaikaM) and $model->IrtisanomisaikaM == 'EiKaytossa') $EiKaytossaChecked = 'checked'; else $EiKaytossaChecked = '';
?>

	<p><b><?php echo Yii::t('main', 'Irtisanomisaika määräytyy'); ?></b></p>
	<input type="radio" name="Tyosopimukset[IrtisanomisaikaM]" value="LainMukaan" <?php echo $LainMukaanChecked; ?>> <?php echo Yii::t('main', 'Lain mukaan'); ?><br>
	<input type="radio" name="Tyosopimukset[IrtisanomisaikaM]" value="TyoEhtoMukaan" <?php echo $TyoEhtoMukaanChecked; ?>> <?php echo Yii::t('main', 'Työehtosopimuksen mukaan'); ?><br>
	<input type="radio" name="Tyosopimukset[IrtisanomisaikaM]" value="EiKaytossa" <?php echo $EiKaytossaChecked; ?>> <?php echo Yii::t('main', 'Ei käytossa'); ?><br>

	<br>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Muut_sopimusehdot'); ?>
		<?php echo $form->textArea($model,'Muut_sopimusehdot',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Muut_sopimusehdot'); ?>
	</div>

  </div>
</div><!-- form -->


<br>


	<legend><?php echo Yii::t('main', 'Muutokset työsopimukseen'); ?></legend>

<div class="row">
  <div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Muutospaiva'); ?>
		<?php echo $form->textField($model,'Muutospaiva',array('size'=>50,'maxlength'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Muutospaiva'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'LisayksetSopimukseen'); ?>
		<?php echo $form->textArea($model,'LisayksetSopimukseen',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'LisayksetSopimukseen'); ?>
	</div>

  </div>
</div><!-- form -->


<br>


	<legend><?php echo Yii::t('main', 'Allekirjoitus'); ?></legend>

<div class="row">
  <div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Paivays'); ?>
		<?php echo $form->textField($model,'Paivays',array('size'=>50,'maxlength'=>50, 'class'=>'form-control datepickerFI')); ?>
		<?php echo $form->error($model,'Paivays'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'TyonantajanEdustaja'); ?>
		<?php echo $form->textField($model,'TyonantajanEdustaja',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'TyonantajanEdustaja'); ?>
	</div>

  </div><div class="col-sm-6">

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'Paikka'); ?>
		<?php echo $form->textField($model,'Paikka',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'Paikka'); ?>
	</div>

	<div class="section fill mb5">
		<?php echo $form->labelEx($model,'NimikeTehtava'); ?>
		<?php echo $form->textField($model,'NimikeTehtava',array('size'=>60,'maxlength'=>100, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'NimikeTehtava'); ?>
	</div>

  </div>
</div><!-- form -->


	<div class="section">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Luo' : 'Tallenna',array('class'=>'btn btn-primary myBgColors')); ?>
	</div>

<?php $this->endWidget(); ?>


