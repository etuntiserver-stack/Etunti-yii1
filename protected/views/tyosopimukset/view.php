<?php
/* @var $this TyosopimuksetController */
/* @var $model Tyosopimukset */

$this->breadcrumbs=array(
	'Tyosopimuksets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tyosopimukset', 'url'=>array('index')),
	array('label'=>'Create Tyosopimukset', 'url'=>array('create')),
	array('label'=>'Update Tyosopimukset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tyosopimukset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tyosopimukset', 'url'=>array('admin')),
);
?>

<h1>View Tyosopimukset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'key',
		'tyonantaja',
		'osoite',
		'postinumero',
		'postitoimipaikka',
		'puhelin',
		'y_tunnus',
		'sahkoposti',
		'tekijan_email',
		'tid',
		'tekijan_nimi',
		'tekijan_katuosoite',
		'tekijan_pnumero',
		'tekijan_ptoimipaikka',
		'tekijan_puh',
		'tekijan_henkilotunnus',
		'sopimus',
		'ToistaVoimaSopimus',
		'MaaraVoimaSopimusAlkaa',
		'MaaraVoimaSopimusPaattyy',
		'peruste',
		'koeaika',
		'SoveltavaSopimus',
		'Tyotehtavat',
		'tyonSuorittamisPaikka',
		'PalkanMaaraytymisperuste',
		'PalkanMaaraytymisperusteMuu',
		'TyokokemusVuotta',
		'TyokokemusKuu',
		'palkka_kk',
		'Palkkaluokka',
		'palkka_h',
		'Luontaiseudut',
		'Raha_arvo',
		'Verotusarvo',
		'palkka_muu2',
		'Palkanmaksukausi',
		'Palkanmaksupaivat',
		'Palkka_tilille',
		'tyoaika_hvrk',
		'tyoaika_hvko',
		'tyoaika_h_jakso',
		'tyoaika_vko_jaksossa',
		'RuokataukonPituus',
		'Muu_tyoaika',
		'lomasta_sovittu',
		'Salassapito',
		'IrtisanomisaikaM',
		'Muut_sopimusehdot',
		'Muutospaiva',
		'LisayksetSopimukseen',
		'Paivays',
		'Paikka',
		'TyonantajanEdustaja',
		'NimikeTehtava',
	),
)); ?>
