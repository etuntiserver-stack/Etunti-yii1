<?php
/* @var $this LaskuHistoriaController */
/* @var $model LaskuHistoria */

$this->breadcrumbs=array(
	'Lasku Historias'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List LaskuHistoria', 'url'=>array('index')),
	array('label'=>'Create LaskuHistoria', 'url'=>array('create')),
	array('label'=>'Update LaskuHistoria', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete LaskuHistoria', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LaskuHistoria', 'url'=>array('admin')),
);
?>

<h1>View LaskuHistoria #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'lid',
		'time',
		'status',
		'yht_euro',
	),
)); ?>
