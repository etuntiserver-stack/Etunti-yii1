<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */

$this->breadcrumbs=array(
	'Asetukset For Alls'=>array('index'),
	$model->id,
);
/*
$this->menu=array(
	array('label'=>'List AsetuksetForAll', 'url'=>array('index')),
	array('label'=>'Create AsetuksetForAll', 'url'=>array('create')),
	array('label'=>'Update AsetuksetForAll', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AsetuksetForAll', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AsetuksetForAll', 'url'=>array('admin')),
);
*/
?>

<legend>
<h1><?php echo Yii::t('main','KATSOA'); ?> #<?php echo $model->id; ?></h1>
</legend>


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'asetus',
		'api_access_key',
		'ohjesivu',
	),
)); ?>
