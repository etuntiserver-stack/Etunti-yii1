<?php
/* @var $this TyosopimuksetController */
/* @var $model Tyosopimukset */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time'); ?>
		<?php echo $form->textField($model,'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'key'); ?>
		<?php echo $form->textField($model,'key'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyonantaja'); ?>
		<?php echo $form->textField($model,'tyonantaja',array('size'=>60,'maxlength'=>70)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osoite'); ?>
		<?php echo $form->textField($model,'osoite',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'postinumero'); ?>
		<?php echo $form->textField($model,'postinumero',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'postitoimipaikka'); ?>
		<?php echo $form->textField($model,'postitoimipaikka',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'puhelin'); ?>
		<?php echo $form->textField($model,'puhelin',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'y_tunnus'); ?>
		<?php echo $form->textField($model,'y_tunnus',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sahkoposti'); ?>
		<?php echo $form->textField($model,'sahkoposti',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_email'); ?>
		<?php echo $form->textField($model,'tekijan_email',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tid'); ?>
		<?php echo $form->textField($model,'tid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_nimi'); ?>
		<?php echo $form->textField($model,'tekijan_nimi',array('size'=>60,'maxlength'=>70)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_katuosoite'); ?>
		<?php echo $form->textField($model,'tekijan_katuosoite',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_pnumero'); ?>
		<?php echo $form->textField($model,'tekijan_pnumero',array('size'=>7,'maxlength'=>7)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_ptoimipaikka'); ?>
		<?php echo $form->textField($model,'tekijan_ptoimipaikka',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_puh'); ?>
		<?php echo $form->textField($model,'tekijan_puh',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tekijan_henkilotunnus'); ?>
		<?php echo $form->textField($model,'tekijan_henkilotunnus',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sopimus'); ?>
		<?php echo $form->textField($model,'sopimus',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ToistaVoimaSopimus'); ?>
		<?php echo $form->textField($model,'ToistaVoimaSopimus',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'MaaraVoimaSopimusAlkaa'); ?>
		<?php echo $form->textField($model,'MaaraVoimaSopimusAlkaa',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'MaaraVoimaSopimusPaattyy'); ?>
		<?php echo $form->textField($model,'MaaraVoimaSopimusPaattyy',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'peruste'); ?>
		<?php echo $form->textArea($model,'peruste',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'koeaika'); ?>
		<?php echo $form->textField($model,'koeaika',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SoveltavaSopimus'); ?>
		<?php echo $form->textField($model,'SoveltavaSopimus',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Tyotehtavat'); ?>
		<?php echo $form->textArea($model,'Tyotehtavat',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyonSuorittamisPaikka'); ?>
		<?php echo $form->textArea($model,'tyonSuorittamisPaikka',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PalkanMaaraytymisperuste'); ?>
		<?php echo $form->textField($model,'PalkanMaaraytymisperuste',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'PalkanMaaraytymisperusteMuu'); ?>
		<?php echo $form->textField($model,'PalkanMaaraytymisperusteMuu',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TyokokemusVuotta'); ?>
		<?php echo $form->textField($model,'TyokokemusVuotta',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TyokokemusKuu'); ?>
		<?php echo $form->textField($model,'TyokokemusKuu',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'palkka_kk'); ?>
		<?php echo $form->textField($model,'palkka_kk',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Palkkaluokka'); ?>
		<?php echo $form->textField($model,'Palkkaluokka',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'palkka_h'); ?>
		<?php echo $form->textField($model,'palkka_h',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Luontaiseudut'); ?>
		<?php echo $form->textArea($model,'Luontaiseudut',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Raha_arvo'); ?>
		<?php echo $form->textField($model,'Raha_arvo',array('size'=>60,'maxlength'=>70)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Verotusarvo'); ?>
		<?php echo $form->textField($model,'Verotusarvo',array('size'=>60,'maxlength'=>70)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'palkka_muu2'); ?>
		<?php echo $form->textField($model,'palkka_muu2',array('size'=>60,'maxlength'=>70)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Palkanmaksukausi'); ?>
		<?php echo $form->textField($model,'Palkanmaksukausi',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Palkanmaksupaivat'); ?>
		<?php echo $form->textField($model,'Palkanmaksupaivat',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Palkka_tilille'); ?>
		<?php echo $form->textField($model,'Palkka_tilille',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyoaika_hvrk'); ?>
		<?php echo $form->textField($model,'tyoaika_hvrk',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyoaika_hvko'); ?>
		<?php echo $form->textField($model,'tyoaika_hvko',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyoaika_h_jakso'); ?>
		<?php echo $form->textField($model,'tyoaika_h_jakso',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tyoaika_vko_jaksossa'); ?>
		<?php echo $form->textField($model,'tyoaika_vko_jaksossa',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'RuokataukonPituus'); ?>
		<?php echo $form->textField($model,'RuokataukonPituus',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Muu_tyoaika'); ?>
		<?php echo $form->textArea($model,'Muu_tyoaika',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lomasta_sovittu'); ?>
		<?php echo $form->textArea($model,'lomasta_sovittu',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Salassapito'); ?>
		<?php echo $form->textArea($model,'Salassapito',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'IrtisanomisaikaM'); ?>
		<?php echo $form->textField($model,'IrtisanomisaikaM',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Muut_sopimusehdot'); ?>
		<?php echo $form->textArea($model,'Muut_sopimusehdot',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Muutospaiva'); ?>
		<?php echo $form->textField($model,'Muutospaiva',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'LisayksetSopimukseen'); ?>
		<?php echo $form->textArea($model,'LisayksetSopimukseen',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Paivays'); ?>
		<?php echo $form->textField($model,'Paivays',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Paikka'); ?>
		<?php echo $form->textField($model,'Paikka',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TyonantajanEdustaja'); ?>
		<?php echo $form->textField($model,'TyonantajanEdustaja',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'NimikeTehtava'); ?>
		<?php echo $form->textField($model,'NimikeTehtava',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->