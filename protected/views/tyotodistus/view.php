<?php
/* @var $this TyotodistusController */
/* @var $model Tyotodistus */

$this->breadcrumbs=array(
	'Tyotodistuses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tyotodistus', 'url'=>array('index')),
	array('label'=>'Create Tyotodistus', 'url'=>array('create')),
	array('label'=>'Update Tyotodistus', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tyotodistus', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tyotodistus', 'url'=>array('admin')),
);
?>

<h1>View Tyotodistus #<?php echo $model->id; ?></h1>

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
		'Alku',
		'Loppu',
		'Tyokohde',
		'Tyotehtavat',
		'TyosuhteenPaattamisenSyy',
		'Tyotaito',
		'Kaytos',
		'Arvio',
		'Paivays',
		'Paikka',
		'TyonantajanEdustaja',
		'NimikeTehtava',
		'tiedosto',
	),
)); ?>
