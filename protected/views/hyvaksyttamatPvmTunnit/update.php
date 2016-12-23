<?php
/* @var $this HyvaksyttamatPvmTunnitController */
/* @var $model HyvaksyttamatPvmTunnit */

$this->breadcrumbs=array(
	'Hyvaksyttamat Pvm Tunnits'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List HyvaksyttamatPvmTunnit', 'url'=>array('index')),
	array('label'=>'Create HyvaksyttamatPvmTunnit', 'url'=>array('create')),
	array('label'=>'View HyvaksyttamatPvmTunnit', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage HyvaksyttamatPvmTunnit', 'url'=>array('admin')),
);
?>

<h1>Update HyvaksyttamatPvmTunnit <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>