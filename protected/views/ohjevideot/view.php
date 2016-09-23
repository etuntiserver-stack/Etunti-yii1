<?php
/* @var $this OhjevideotController */
/* @var $model Ohjevideot */

$this->breadcrumbs=array(
	'Ohjevideots'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Ohjevideot', 'url'=>array('index')),
	array('label'=>'Create Ohjevideot', 'url'=>array('create')),
	array('label'=>'Update Ohjevideot', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Ohjevideot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ohjevideot', 'url'=>array('admin')),
);
?>

<h1>View Ohjevideot #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'otsiko',
		'kuvaus',
		'tiedoston_nimi',
	),
)); ?>
