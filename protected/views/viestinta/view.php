<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$this->breadcrumbs=array(
	'Viestintas'=>array('index'),
	$model->id,
);
/*
$this->menu=array(
	array('label'=>'List Viestinta', 'url'=>array('index')),
	array('label'=>'Create Viestinta', 'url'=>array('create')),
	array('label'=>'Update Viestinta', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Viestinta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Viestinta', 'url'=>array('admin')),
);
*/
?>
<legend>
<h1><?php echo Yii::t('main', 'VIESTI'); ?> ID:<?php echo $model->id; ?></h1>
</legend>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'pvm',
		'tekija',
		'viesti',
		'admin',
	),
)); ?>
