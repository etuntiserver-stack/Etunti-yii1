<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */

$this->breadcrumbs=array(
	'Toteutuneets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Toteutuneet', 'url'=>array('index')),
	array('label'=>'Create Toteutuneet', 'url'=>array('create')),
	array('label'=>'Update Toteutuneet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Toteutuneet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Toteutuneet', 'url'=>array('admin')),
);
?>

<h1>View Toteutuneet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'kid',
		'asiakas_num',
		'time',
		'requests',
		'puh_numero',
		'imei',
		'bluetooth_name',
		'sim_serial_number',
		'subscriber_id',
		'my_location',
		'osoite',
		'kohde_kannasta',
		'kohdenID',
		'aloitan',
		'loppui',
		'viesti',
		'tekijan_nimi',
		'tid',
		'etaisyys',
		'status',
		'tietoja',
		'admin',
		'tyoajanlaatu',
		'tyoajanmerkinta',
	),
)); ?>
