<?php
/* @var $this KupongitController */
/* @var $model Kupongit */

$this->breadcrumbs=array(
	'Kupongits'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Kupongit', 'url'=>array('index')),
	array('label'=>'Create Kupongit', 'url'=>array('create')),
	array('label'=>'Update Kupongit', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Kupongit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Kupongit', 'url'=>array('admin')),
);
?>

<h1>View Kupongit #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'kupongin_id',
		'voimassa',
		'euro_maara',
		'prosentti_maara',
		'maara_tyyppi',
		'jatkuva',
		'status',
	),
)); ?>
