<?php
/* @var $this IlmoitusKaikkilleController */
/* @var $model IlmoitusKaikkille */

$this->breadcrumbs=array(
	'Ilmoitus Kaikkilles'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List IlmoitusKaikkille', 'url'=>array('index')),
	array('label'=>'Create IlmoitusKaikkille', 'url'=>array('create')),
	array('label'=>'Update IlmoitusKaikkille', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete IlmoitusKaikkille', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage IlmoitusKaikkille', 'url'=>array('admin')),
);
?>

<h1>View IlmoitusKaikkille #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'viesti',
		'voimassa',
	),
)); ?>
