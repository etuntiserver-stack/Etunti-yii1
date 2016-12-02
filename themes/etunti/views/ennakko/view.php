<?php
/* @var $this EnnakkoController */
/* @var $model Ennakko */

$this->breadcrumbs=array(
	'Ennakkos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Ennakko', 'url'=>array('index')),
	array('label'=>'Create Ennakko', 'url'=>array('create')),
	array('label'=>'Update Ennakko', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Ennakko', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ennakko', 'url'=>array('admin')),
);
?>

<h1>View Ennakko #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tid',
		'time',
		'pvm',
		'syy',
		'ennakko',
	),
)); ?>
