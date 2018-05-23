<?php
/* @var $this EdicoViestintaController */
/* @var $model EdicoViestinta */

$this->breadcrumbs=array(
	'Edico Viestintas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List EdicoViestinta', 'url'=>array('index')),
	array('label'=>'Create EdicoViestinta', 'url'=>array('create')),
	array('label'=>'Update EdicoViestinta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete EdicoViestinta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EdicoViestinta', 'url'=>array('admin')),
);
?>

<h1>View EdicoViestinta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'asiakas_id',
		'otsikko',
		'status',
	),
)); ?>
