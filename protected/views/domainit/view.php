<?php
/* @var $this DomainitController */
/* @var $model Domainit */

$this->breadcrumbs=array(
	'Domainits'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Domainit', 'url'=>array('index')),
	array('label'=>'Create Domainit', 'url'=>array('create')),
	array('label'=>'Update Domainit', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Domainit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Domainit', 'url'=>array('admin')),
);
?>

<h1>View Domainit #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'domain',
		'paketti',
		'yritys',
		'pakettin_nimetus',
	),
)); ?>
