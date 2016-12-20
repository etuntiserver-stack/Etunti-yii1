<?php
/* @var $this TyosopimuksetController */
/* @var $model Tyosopimukset */

$this->breadcrumbs=array(
	'Tyosopimuksets'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Tyosopimukset', 'url'=>array('index')),
	array('label'=>'Create Tyosopimukset', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tyosopimukset-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Tyosopimuksets</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyosopimukset-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'time',
		'key',
		'tyonantaja',
		'osoite',
		'postinumero',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
