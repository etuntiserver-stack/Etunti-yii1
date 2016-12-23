<?php
/* @var $this HyvaksyttamatPvmTunnitController */
/* @var $model HyvaksyttamatPvmTunnit */

$this->breadcrumbs=array(
	'Hyvaksyttamat Pvm Tunnits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List HyvaksyttamatPvmTunnit', 'url'=>array('index')),
	array('label'=>'Manage HyvaksyttamatPvmTunnit', 'url'=>array('admin')),
);
?>

<h1>Create HyvaksyttamatPvmTunnit</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>