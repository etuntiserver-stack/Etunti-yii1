<?php
/* @var $this MobileController */
/* @var $model Mobile */

$this->breadcrumbs=array(
	'Mobiles'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Mobile', 'url'=>array('index')),
	array('label'=>'Create Mobile', 'url'=>array('create')),
	array('label'=>'Update Mobile', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Mobile', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Mobile', 'url'=>array('admin')),
);
?>

<h1>View Mobile #<?php echo $model->id; ?></h1>

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
		'tid',
		'tekijan_nimi',
		'etaisyys',
		'status',
		'tietoja',
		'admin',
		'hyvaksytty',
	),
)); ?>
