<?php
/* @var $this EdicoTulauksetController */
/* @var $model EdicoTulaukset */

$this->breadcrumbs=array(
	'Edico Tulauksets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List EdicoTulaukset', 'url'=>array('index')),
	array('label'=>'Create EdicoTulaukset', 'url'=>array('create')),
	array('label'=>'Update EdicoTulaukset', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete EdicoTulaukset', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage EdicoTulaukset', 'url'=>array('admin')),
);
?>

<h1>View EdicoTulaukset #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'asiakas_id',
		'kohde_id',
		'osoite',
		'postinumero',
		'postitoimipaikka',
		'asiakas_puhelinnumero',
		'toivottu_pvm',
		'toivottu_aloitus',
		'toivottu_lopetus',
		'viesti',
		'tuotteet',
	),
)); ?>
