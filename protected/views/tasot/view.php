<?php
/* @var $this TasotController */
/* @var $model Tasot */

$this->breadcrumbs=array(
	'Tasots'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tasot', 'url'=>array('index')),
	array('label'=>'Create Tasot', 'url'=>array('create')),
	array('label'=>'Update Tasot', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tasot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tasot', 'url'=>array('admin')),
);
?>

<h1>View Tasot #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'taso',
		'nimetys',
		'kuvaus',
	),
)); ?>
