<?php
/* @var $this TarjouslaskentaController */
/* @var $model Tarjouslaskenta */

$this->breadcrumbs=array(
	'Tarjouslaskentas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tarjouslaskenta', 'url'=>array('index')),
	array('label'=>'Create Tarjouslaskenta', 'url'=>array('create')),
	array('label'=>'View Tarjouslaskenta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tarjouslaskenta', 'url'=>array('admin')),
);
?>

<h1>Update Tarjouslaskenta <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>