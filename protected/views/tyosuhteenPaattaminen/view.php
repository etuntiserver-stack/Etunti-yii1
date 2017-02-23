<?php
/* @var $this TyosuhteenPaattaminenController */
/* @var $model TyosuhteenPaattaminen */

$this->breadcrumbs=array(
	'Tyosuhteen Paattaminens'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TyosuhteenPaattaminen', 'url'=>array('index')),
	array('label'=>'Create TyosuhteenPaattaminen', 'url'=>array('create')),
	array('label'=>'Update TyosuhteenPaattaminen', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TyosuhteenPaattaminen', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TyosuhteenPaattaminen', 'url'=>array('admin')),
);
?>

<h1>View TyosuhteenPaattaminen #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'key',
		'titteli',
		'kuuleminen',
		'tyosuhteen_paattaminen',
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
		'alku_pvm',
		'loppu_pvm',
	),
)); ?>
