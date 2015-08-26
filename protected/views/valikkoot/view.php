<?php
/* @var $this ValikkootController */
/* @var $model Valikkoot */

$this->breadcrumbs=array(
	'Valikkoots'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Valikkoot', 'url'=>array('index')),
	array('label'=>'Create Valikkoot', 'url'=>array('create')),
	array('label'=>'Update Valikkoot', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Valikkoot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Valikkoot', 'url'=>array('admin')),
);
?>

<h1>View Valikkoot #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'value',
		'select_type',
	),
)); ?>
