<?php
/* @var $this VuosilomatController */
/* @var $model Vuosilomat */

$this->breadcrumbs=array(
	'Vuosilomats'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Vuosilomat', 'url'=>array('index')),
	array('label'=>'Create Vuosilomat', 'url'=>array('create')),
	array('label'=>'Update Vuosilomat', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Vuosilomat', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Vuosilomat', 'url'=>array('admin')),
);
?>

<h1>View Vuosilomat #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tid',
		'pvm',
		'status',
	),
)); ?>
