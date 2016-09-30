<?php
/* @var $this KorvauksetController */
/* @var $model Korvaukset */

$this->breadcrumbs=array(
	'Korvauksets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Korvaukset', 'url'=>array('index')),
	array('label'=>'Create Korvaukset', 'url'=>array('create')),
	array('label'=>'Update Korvaukset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Korvaukset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Korvaukset', 'url'=>array('admin')),
);
?>

<h1>View Korvaukset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tid',
		'time',
		'pvm',
		'syy',
		'korvaus',
	),
)); ?>
