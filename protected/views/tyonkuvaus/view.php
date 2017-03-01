<?php
/* @var $this TyonkuvausController */
/* @var $model Tyonkuvaus */

$this->breadcrumbs=array(
	'Tyonkuvauses'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tyonkuvaus', 'url'=>array('index')),
	array('label'=>'Create Tyonkuvaus', 'url'=>array('create')),
	array('label'=>'Update Tyonkuvaus', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tyonkuvaus', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tyonkuvaus', 'url'=>array('admin')),
);
?>

<h1>View Tyonkuvaus #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'yhteystiedot_id',
		'asiakas_id',
		'otsikko',
	),
)); ?>
