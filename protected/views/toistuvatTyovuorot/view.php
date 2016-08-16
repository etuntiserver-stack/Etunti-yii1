<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $model ToistuvatTyovuorot */

$this->breadcrumbs=array(
	'Toistuvat Tyovuorots'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ToistuvatTyovuorot', 'url'=>array('index')),
	array('label'=>'Create ToistuvatTyovuorot', 'url'=>array('create')),
	array('label'=>'Update ToistuvatTyovuorot', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ToistuvatTyovuorot', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ToistuvatTyovuorot', 'url'=>array('admin')),
);
?>

<h1>View ToistuvatTyovuorot #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'pfrom',
		'pto',
		'viikkoja',
		'viikko_paivat',
		'tid',
		'kohde',
		'pvm',
		'alku',
		'loppu',
		'kesto',
		'tyoajanmerkinta',
		'status',
		'tietoja',
		'tyopaari',
	),
)); ?>
