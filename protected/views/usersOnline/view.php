<?php
/* @var $this UsersOnlineController */
/* @var $model UsersOnline */

$this->breadcrumbs=array(
	'Users Onlines'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UsersOnline', 'url'=>array('index')),
	array('label'=>'Create UsersOnline', 'url'=>array('create')),
	array('label'=>'Update UsersOnline', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UsersOnline', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UsersOnline', 'url'=>array('admin')),
);
?>

<h1>View UsersOnline #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ip',
		'session',
		'time',
		'user',
		'url',
	),
)); ?>
