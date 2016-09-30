<?php
/* @var $this VuosilomatController */
/* @var $model Vuosilomat */

$this->breadcrumbs=array(
	'Vuosilomats'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Vuosilomat', 'url'=>array('index')),
	array('label'=>'Create Vuosilomat', 'url'=>array('create')),
	array('label'=>'View Vuosilomat', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Vuosilomat', 'url'=>array('admin')),
);
?>

<h1>Update Vuosilomat <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>