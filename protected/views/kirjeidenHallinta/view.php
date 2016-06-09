<?php
/* @var $this KirjeidenHallintaController */
/* @var $model KirjeidenHallinta */

$this->breadcrumbs=array(
	'Kirjeiden Hallintas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List KirjeidenHallinta', 'url'=>array('index')),
	array('label'=>'Create KirjeidenHallinta', 'url'=>array('create')),
	array('label'=>'Update KirjeidenHallinta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete KirjeidenHallinta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage KirjeidenHallinta', 'url'=>array('admin')),
);
?>

<h1>View KirjeidenHallinta #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'ryhma',
		'teksti',
		'hyvaksyn_koodi',
		'status',
		'liite',
	),
)); ?>
