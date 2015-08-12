<?php
/* @var $this SivexkuittiController */
/* @var $model Sivexkuitti */

$this->breadcrumbs=array(
	'Sivexkuittis'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Sivexkuitti', 'url'=>array('index')),
	array('label'=>'Create Sivexkuitti', 'url'=>array('create')),
	array('label'=>'Update Sivexkuitti', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Sivexkuitti', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Sivexkuitti', 'url'=>array('admin')),
);
?>

<h1>View Sivexkuitti #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
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
		'etaisyys',
		'status',
		'tietoja',
		'admin',
		'hyvaksytty',
	),
)); ?>
