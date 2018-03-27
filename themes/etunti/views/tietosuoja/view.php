<?php
/* @var $this TietosuojaController */
/* @var $model Tietosuoja */

$this->breadcrumbs=array(
	'Tietosuojas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tietosuoja', 'url'=>array('index')),
	array('label'=>'Create Tietosuoja', 'url'=>array('create')),
	array('label'=>'Update Tietosuoja', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tietosuoja', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tietosuoja', 'url'=>array('admin')),
);
?>

<h1>View Tietosuoja #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'col_1',
	),
)); ?>
