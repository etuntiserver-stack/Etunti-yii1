<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

$this->breadcrumbs=array(
	'Kohteets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Kohteet', 'url'=>array('index')),
	array('label'=>'Create Kohteet', 'url'=>array('create')),
	array('label'=>'Update Kohteet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Kohteet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Kohteet', 'url'=>array('admin')),
);
?>

<h1>View Kohteet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'tag_id',
		'gps_sijainti',
		'lyhenne',
		'osoite',
		'katuosoite',
		'kaupunki',
		'toimipaikka',
		'pnumero',
		'email',
		'aikataulu',
		'hinnoittelu',
		'muut',
		'toimenpiteet',
		'tietoja',
		'tyoryhma',
		'ryhma',
		'aktiivinen',
		'avain',
		'kenella_on_avain',
		'puh_nro',
		'siivous',
		'etu_suku_nimet',
		'maksuehto_paiva',
		'viivastyskorko',
		'lasku_tiedot',
	),
)); ?>
