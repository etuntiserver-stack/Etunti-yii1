<?php
/* @var $this IrtisanomisilmoituksetController */
/* @var $model Irtisanomisilmoitukset */

$this->breadcrumbs=array(
	'Irtisanomisilmoituksets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Irtisanomisilmoitukset', 'url'=>array('index')),
	array('label'=>'Create Irtisanomisilmoitukset', 'url'=>array('create')),
	array('label'=>'Update Irtisanomisilmoitukset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Irtisanomisilmoitukset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Irtisanomisilmoitukset', 'url'=>array('admin')),
);
?>

<h1>View Irtisanomisilmoitukset #<?php echo $model->id; ?></h1>

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
		'teksti',
		'Paivays',
		'Paikka',
		'TyonantajanEdustaja',
		'NimikeTehtava',
		'tiedosto',
	),
)); ?>
