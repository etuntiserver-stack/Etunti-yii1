<?php
/* @var $this HyvaksyttamatPvmTunnitController */
/* @var $model HyvaksyttamatPvmTunnit */

$this->breadcrumbs=array(
	'Hyvaksyttamat Pvm Tunnits'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List HyvaksyttamatPvmTunnit', 'url'=>array('index')),
	array('label'=>'Create HyvaksyttamatPvmTunnit', 'url'=>array('create')),
	array('label'=>'Update HyvaksyttamatPvmTunnit', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete HyvaksyttamatPvmTunnit', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HyvaksyttamatPvmTunnit', 'url'=>array('admin')),
);
?>

<h1>View HyvaksyttamatPvmTunnit #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'pvm',
		'tid',
		'admin',
		'json_arvot',
	),
)); ?>
