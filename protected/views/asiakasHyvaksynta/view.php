<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $model AsiakasHyvaksynta */

$this->breadcrumbs=array(
	'Asiakas Hyvaksyntas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AsiakasHyvaksynta', 'url'=>array('index')),
	array('label'=>'Create AsiakasHyvaksynta', 'url'=>array('create')),
	array('label'=>'Update AsiakasHyvaksynta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AsiakasHyvaksynta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AsiakasHyvaksynta', 'url'=>array('admin')),
);
?>

<h1>View AsiakasHyvaksynta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'asiakas_id',
		'time',
		'ids',
		'sahkoposti',
		'code',
		'status',
		'selitys',
		'kirjen_body',
	),
)); ?>
