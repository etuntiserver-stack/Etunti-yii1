<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */

$this->breadcrumbs=array(
	'Lisatyotunnits'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Lisatyotunnit', 'url'=>array('index')),
	array('label'=>'Create Lisatyotunnit', 'url'=>array('create')),
	array('label'=>'Update Lisatyotunnit', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Lisatyotunnit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Lisatyotunnit', 'url'=>array('admin')),
);
?>

<h1>View Lisatyotunnit #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tid',
		'time',
		'pvm',
		'syy',
		'prosentti',
		'tunnimaara',
	),
)); ?>
