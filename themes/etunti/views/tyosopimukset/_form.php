<?php
/* @var $this TyosopimuksetController */
/* @var $model Tyosopimukset */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tyosopimukset-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
		<?php echo $form->error($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'key'); ?>
		<?php echo $form->textField($model,'key'); ?>
		<?php echo $form->error($model,'key'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyonantaja'); ?>
		<?php echo $form->textField($model,'tyonantaja',array('size'=>60,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'tyonantaja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'osoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'postinumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'postitoimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'puhelin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'y_tunnus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'sahkoposti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tekijan_email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
		<?php echo $form->error($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'tekijan_nimi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'tekijan_katuosoite'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7)); ?>
		<?php echo $form->error($model,'tekijan_pnumero'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_ptoimipaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_puh'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tekijan_henkilotunnus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sopimus'); ?>
		<?php echo $form->textField($model,'sopimus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'sopimus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ToistaVoimaSopimus'); ?>
		<?php echo $form->textField($model,'ToistaVoimaSopimus',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'ToistaVoimaSopimus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'MaaraVoimaSopimusAlkaa'); ?>
		<?php echo $form->textField($model,'MaaraVoimaSopimusAlkaa',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'MaaraVoimaSopimusAlkaa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'MaaraVoimaSopimusPaattyy'); ?>
		<?php echo $form->textField($model,'MaaraVoimaSopimusPaattyy',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'MaaraVoimaSopimusPaattyy'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'peruste'); ?>
		<?php echo $form->textArea($model,'peruste',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'peruste'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'koeaika'); ?>
		<?php echo $form->textField($model,'koeaika',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'koeaika'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SoveltavaSopimus'); ?>
		<?php echo $form->textField($model,'SoveltavaSopimus',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'SoveltavaSopimus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Tyotehtavat'); ?>
		<?php echo $form->textArea($model,'Tyotehtavat',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Tyotehtavat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyonSuorittamisPaikka'); ?>
		<?php echo $form->textArea($model,'tyonSuorittamisPaikka',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tyonSuorittamisPaikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PalkanMaaraytymisperuste'); ?>
		<?php echo $form->textField($model,'PalkanMaaraytymisperuste',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'PalkanMaaraytymisperuste'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'PalkanMaaraytymisperusteMuu'); ?>
		<?php echo $form->textField($model,'PalkanMaaraytymisperusteMuu',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'PalkanMaaraytymisperusteMuu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TyokokemusVuotta'); ?>
		<?php echo $form->textField($model,'TyokokemusVuotta',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'TyokokemusVuotta'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TyokokemusKuu'); ?>
		<?php echo $form->textField($model,'TyokokemusKuu',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'TyokokemusKuu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'palkka_kk'); ?>
		<?php echo $form->textField($model,'palkka_kk',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'palkka_kk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Palkkaluokka'); ?>
		<?php echo $form->textField($model,'Palkkaluokka',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'Palkkaluokka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'palkka_h'); ?>
		<?php echo $form->textField($model,'palkka_h',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'palkka_h'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Luontaiseudut'); ?>
		<?php echo $form->textArea($model,'Luontaiseudut',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Luontaiseudut'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Raha_arvo'); ?>
		<?php echo $form->textField($model,'Raha_arvo',array('size'=>60,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'Raha_arvo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Verotusarvo'); ?>
		<?php echo $form->textField($model,'Verotusarvo',array('size'=>60,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'Verotusarvo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'palkka_muu2'); ?>
		<?php echo $form->textField($model,'palkka_muu2',array('size'=>60,'maxlength'=>70)); ?>
		<?php echo $form->error($model,'palkka_muu2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Palkanmaksukausi'); ?>
		<?php echo $form->textField($model,'Palkanmaksukausi',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'Palkanmaksukausi'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Palkanmaksupaivat'); ?>
		<?php echo $form->textField($model,'Palkanmaksupaivat',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'Palkanmaksupaivat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Palkka_tilille'); ?>
		<?php echo $form->textField($model,'Palkka_tilille',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Palkka_tilille'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoaika_hvrk'); ?>
		<?php echo $form->textField($model,'tyoaika_hvrk',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tyoaika_hvrk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoaika_hvko'); ?>
		<?php echo $form->textField($model,'tyoaika_hvko',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tyoaika_hvko'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoaika_h_jakso'); ?>
		<?php echo $form->textField($model,'tyoaika_h_jakso',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tyoaika_h_jakso'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tyoaika_vko_jaksossa'); ?>
		<?php echo $form->textField($model,'tyoaika_vko_jaksossa',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'tyoaika_vko_jaksossa'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'RuokataukonPituus'); ?>
		<?php echo $form->textField($model,'RuokataukonPituus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'RuokataukonPituus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Muu_tyoaika'); ?>
		<?php echo $form->textArea($model,'Muu_tyoaika',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Muu_tyoaika'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lomasta_sovittu'); ?>
		<?php echo $form->textArea($model,'lomasta_sovittu',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'lomasta_sovittu'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Salassapito'); ?>
		<?php echo $form->textArea($model,'Salassapito',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Salassapito'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'IrtisanomisaikaM'); ?>
		<?php echo $form->textField($model,'IrtisanomisaikaM',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'IrtisanomisaikaM'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Muut_sopimusehdot'); ?>
		<?php echo $form->textArea($model,'Muut_sopimusehdot',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'Muut_sopimusehdot'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Muutospaiva'); ?>
		<?php echo $form->textField($model,'Muutospaiva',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'Muutospaiva'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LisayksetSopimukseen'); ?>
		<?php echo $form->textArea($model,'LisayksetSopimukseen',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'LisayksetSopimukseen'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Paivays'); ?>
		<?php echo $form->textField($model,'Paivays',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'Paivays'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Paikka'); ?>
		<?php echo $form->textField($model,'Paikka',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'Paikka'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TyonantajanEdustaja'); ?>
		<?php echo $form->textField($model,'TyonantajanEdustaja',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'TyonantajanEdustaja'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NimikeTehtava'); ?>
		<?php echo $form->textField($model,'NimikeTehtava',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'NimikeTehtava'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->