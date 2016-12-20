<?php
/* @var $this TyosopimuksetController */
/* @var $data Tyosopimukset */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time')); ?>:</b>
	<?php echo CHtml::encode($data->time); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('key')); ?>:</b>
	<?php echo CHtml::encode($data->key); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyonantaja')); ?>:</b>
	<?php echo CHtml::encode($data->tyonantaja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('osoite')); ?>:</b>
	<?php echo CHtml::encode($data->osoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postinumero')); ?>:</b>
	<?php echo CHtml::encode($data->postinumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('postitoimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->postitoimipaikka); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('puhelin')); ?>:</b>
	<?php echo CHtml::encode($data->puhelin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('y_tunnus')); ?>:</b>
	<?php echo CHtml::encode($data->y_tunnus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sahkoposti')); ?>:</b>
	<?php echo CHtml::encode($data->sahkoposti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_email')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tid')); ?>:</b>
	<?php echo CHtml::encode($data->tid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_nimi')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_nimi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_katuosoite')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_katuosoite); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_pnumero')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_pnumero); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_ptoimipaikka')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_ptoimipaikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_puh')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_puh); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tekijan_henkilotunnus')); ?>:</b>
	<?php echo CHtml::encode($data->tekijan_henkilotunnus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sopimus')); ?>:</b>
	<?php echo CHtml::encode($data->sopimus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ToistaVoimaSopimus')); ?>:</b>
	<?php echo CHtml::encode($data->ToistaVoimaSopimus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('MaaraVoimaSopimusAlkaa')); ?>:</b>
	<?php echo CHtml::encode($data->MaaraVoimaSopimusAlkaa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('MaaraVoimaSopimusPaattyy')); ?>:</b>
	<?php echo CHtml::encode($data->MaaraVoimaSopimusPaattyy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('peruste')); ?>:</b>
	<?php echo CHtml::encode($data->peruste); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('koeaika')); ?>:</b>
	<?php echo CHtml::encode($data->koeaika); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('SoveltavaSopimus')); ?>:</b>
	<?php echo CHtml::encode($data->SoveltavaSopimus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Tyotehtavat')); ?>:</b>
	<?php echo CHtml::encode($data->Tyotehtavat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyonSuorittamisPaikka')); ?>:</b>
	<?php echo CHtml::encode($data->tyonSuorittamisPaikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PalkanMaaraytymisperuste')); ?>:</b>
	<?php echo CHtml::encode($data->PalkanMaaraytymisperuste); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('PalkanMaaraytymisperusteMuu')); ?>:</b>
	<?php echo CHtml::encode($data->PalkanMaaraytymisperusteMuu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TyokokemusVuotta')); ?>:</b>
	<?php echo CHtml::encode($data->TyokokemusVuotta); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TyokokemusKuu')); ?>:</b>
	<?php echo CHtml::encode($data->TyokokemusKuu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('palkka_kk')); ?>:</b>
	<?php echo CHtml::encode($data->palkka_kk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Palkkaluokka')); ?>:</b>
	<?php echo CHtml::encode($data->Palkkaluokka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('palkka_h')); ?>:</b>
	<?php echo CHtml::encode($data->palkka_h); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Luontaiseudut')); ?>:</b>
	<?php echo CHtml::encode($data->Luontaiseudut); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Raha_arvo')); ?>:</b>
	<?php echo CHtml::encode($data->Raha_arvo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Verotusarvo')); ?>:</b>
	<?php echo CHtml::encode($data->Verotusarvo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('palkka_muu2')); ?>:</b>
	<?php echo CHtml::encode($data->palkka_muu2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Palkanmaksukausi')); ?>:</b>
	<?php echo CHtml::encode($data->Palkanmaksukausi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Palkanmaksupaivat')); ?>:</b>
	<?php echo CHtml::encode($data->Palkanmaksupaivat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Palkka_tilille')); ?>:</b>
	<?php echo CHtml::encode($data->Palkka_tilille); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyoaika_hvrk')); ?>:</b>
	<?php echo CHtml::encode($data->tyoaika_hvrk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyoaika_hvko')); ?>:</b>
	<?php echo CHtml::encode($data->tyoaika_hvko); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyoaika_h_jakso')); ?>:</b>
	<?php echo CHtml::encode($data->tyoaika_h_jakso); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tyoaika_vko_jaksossa')); ?>:</b>
	<?php echo CHtml::encode($data->tyoaika_vko_jaksossa); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('RuokataukonPituus')); ?>:</b>
	<?php echo CHtml::encode($data->RuokataukonPituus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Muu_tyoaika')); ?>:</b>
	<?php echo CHtml::encode($data->Muu_tyoaika); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lomasta_sovittu')); ?>:</b>
	<?php echo CHtml::encode($data->lomasta_sovittu); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Salassapito')); ?>:</b>
	<?php echo CHtml::encode($data->Salassapito); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('IrtisanomisaikaM')); ?>:</b>
	<?php echo CHtml::encode($data->IrtisanomisaikaM); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Muut_sopimusehdot')); ?>:</b>
	<?php echo CHtml::encode($data->Muut_sopimusehdot); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Muutospaiva')); ?>:</b>
	<?php echo CHtml::encode($data->Muutospaiva); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('LisayksetSopimukseen')); ?>:</b>
	<?php echo CHtml::encode($data->LisayksetSopimukseen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Paivays')); ?>:</b>
	<?php echo CHtml::encode($data->Paivays); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Paikka')); ?>:</b>
	<?php echo CHtml::encode($data->Paikka); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('TyonantajanEdustaja')); ?>:</b>
	<?php echo CHtml::encode($data->TyonantajanEdustaja); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('NimikeTehtava')); ?>:</b>
	<?php echo CHtml::encode($data->NimikeTehtava); ?>
	<br />

	*/ ?>

</div>