<?php
/* @var $this LaskunRivitController */
/* @var $model LaskunRivit */

$this->breadcrumbs=array(
	'Laskun Rivits'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List LaskunRivit', 'url'=>array('index')),
	array('label'=>'Create LaskunRivit', 'url'=>array('create')),
	array('label'=>'Update LaskunRivit', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete LaskunRivit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LaskunRivit', 'url'=>array('admin')),
);
?>

<h1>View LaskunRivit #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'lid',
		'rivi',
		'tkoodi',
		'nimike',
		'kpl',
		'yksikko',
		'hinta',
		'alv',
		'hinta_alv',
		'ale',
		'veroton',
		'yhteensa_alv',
	),
)); ?>
