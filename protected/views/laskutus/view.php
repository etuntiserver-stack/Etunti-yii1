<?php
/* @var $this LaskutusController */
/* @var $model Laskutus */

$this->breadcrumbs=array(
	'Laskutuses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Laskutus', 'url'=>array('index')),
	array('label'=>'Create Laskutus', 'url'=>array('create')),
	array('label'=>'Update Laskutus', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Laskutus', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Laskutus', 'url'=>array('admin')),
);
?>

<h1>View Laskutus #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'kohdenID',
		'trtd',
		'trtd7',
		'mista',
		'mihin',
		'yhteensa',
		'muoto',
		'asiakkaan_koodi',
	),
)); ?>
