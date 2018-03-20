<?php
/* @var $this HinnastotController */
/* @var $model Hinnastot */

$this->breadcrumbs=array(
	'Hinnastots'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Hinnastot', 'url'=>array('index')),
	array('label'=>'Create Hinnastot', 'url'=>array('create')),
	array('label'=>'Update Hinnastot', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Hinnastot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Hinnastot', 'url'=>array('admin')),
);
?>

<h1>View Hinnastot #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'hinnaston_otsikko',
		'aktiivinen',
	),
)); ?>
