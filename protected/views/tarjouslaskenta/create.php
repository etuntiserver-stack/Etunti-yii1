<?php
/* @var $this TarjouslaskentaController */
/* @var $model Tarjouslaskenta */

$this->breadcrumbs=array(
	'Tarjouslaskentas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tarjouslaskenta', 'url'=>array('index')),
	array('label'=>'Manage Tarjouslaskenta', 'url'=>array('admin')),
);
?>

<h1>Create Tarjouslaskenta</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>