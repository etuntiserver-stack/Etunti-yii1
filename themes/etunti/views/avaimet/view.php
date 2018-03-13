<?php
/* @var $this AvaimetController */
/* @var $model Avaimet */

$this->breadcrumbs=array(
	'Avaimets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Avaimet', 'url'=>array('index')),
	array('label'=>'Create Avaimet', 'url'=>array('create')),
	array('label'=>'Update Avaimet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Avaimet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Avaimet', 'url'=>array('admin')),
);
?>

<h1>View Avaimet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'avainnumero',
		'kohde_id',
		'tid',
		'sijainti',
		'lisatiedot',
		'status',
	),
)); ?>
