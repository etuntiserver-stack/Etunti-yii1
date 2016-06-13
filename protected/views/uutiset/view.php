<?php
/* @var $this UutisetController */
/* @var $model Uutiset */

$this->breadcrumbs=array(
	'Uutisets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Uutiset', 'url'=>array('index')),
	array('label'=>'Create Uutiset', 'url'=>array('create')),
	array('label'=>'Update Uutiset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Uutiset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Uutiset', 'url'=>array('admin')),
);
?>

<h1>View Uutiset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'otsikko',
		'teksti',
		'luoja',
	),
)); ?>
