<?php
/* @var $this TyosuhdetController */
/* @var $model Tyosuhdet */

$this->breadcrumbs=array(
	'Tyosuhdets'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Tyosuhdet', 'url'=>array('index')),
	array('label'=>'Create Tyosuhdet', 'url'=>array('create')),
	array('label'=>'Update Tyosuhdet', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tyosuhdet', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tyosuhdet', 'url'=>array('admin')),
);
?>

<h1>View Tyosuhdet #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'tid',
		'alku',
		'loppu',
		'vktyoaika',
		'nimike',
		'palkkausmuoto',
		'tuntihinta',
		'matka_thinta',
		'lippu_kuumaks',
		'koe_loppu',
		'koe_hinta',
		'tuloraja_ajalle',
		'perusprosentti',
		'lisaprosentti',
		'kuukaudessa',
		'kahdessa_viikossa',
		'viikossa',
		'paivassa',
		'atk_varten',
		'yksi_tuloraja',
	),
)); ?>
