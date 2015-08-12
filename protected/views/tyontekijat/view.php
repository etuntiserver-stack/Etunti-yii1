<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */

$this->breadcrumbs=array(
	'Tyontekijats'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tyontekijat', 'url'=>array('index')),
	array('label'=>'Create Tyontekijat', 'url'=>array('create')),
	array('label'=>'Update Tyontekijat', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tyontekijat', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tyontekijat', 'url'=>array('admin')),
);
?>

<h1>View Tyontekijat #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'imei',
		'laiten_puh',
		'tekijan_nimi',
		'tekijan_henkilotunnus',
		'tekijan_puh',
		'tekijan_email',
		'tekijan_lanka_puh',
		'tekijan_katuosoite',
		'tekijan_pnumero',
		'tekijan_ptoimipaikka',
		'tyoryhma',
		'tyoehtosopimus',
		'tekijan_kulunvalvonta',
		'tekijan_pankkitili',
		'tekijan_konttori',
		'aktiivinen',
		'tekijan_tietoja',
		'tekijan_muisti',
		'salasana',
		'online_varauksen_valmina',
		'kortit',
		'ayjasenyys',
	),
)); ?>
