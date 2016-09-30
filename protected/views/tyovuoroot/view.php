<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */

$this->breadcrumbs=array(
	'Tyovuoroots'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tyovuoroot', 'url'=>array('index')),
	array('label'=>'Create Tyovuoroot', 'url'=>array('create')),
	array('label'=>'Update Tyovuoroot', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tyovuoroot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tyovuoroot', 'url'=>array('admin')),
);
?>

<h1>View Tyovuoroot #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tid',
		'time',
		'kohde',
		'pvm',
		'alku',
		'loppu',
		'pituus',
		'ruokatauko',
		'alku_r',
		'kesto',
		'tyoajanlaatu',
		'tyoajanmerkinta',
		'tietoja',
		'osoiteOnline',
	),
)); ?>
