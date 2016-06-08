<?php
/* @var $this PalautteetController */
/* @var $model Palautteet */

$this->breadcrumbs=array(
	'Palautteets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Palautteet', 'url'=>array('index')),
	array('label'=>'Create Palautteet', 'url'=>array('create')),
	array('label'=>'Update Palautteet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Palautteet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Palautteet', 'url'=>array('admin')),
);
?>

<h1>View Palautteet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'keskustelu_id',
		'time',
		'asiakas_id',
		'teksti',
		'otsikko',
		'status',
	),
)); ?>
