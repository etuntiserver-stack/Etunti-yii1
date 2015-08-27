<?php
/* @var $this VuosilomatController */
/* @var $model Vuosilomat */

$this->breadcrumbs=array(
	'Vuosilomats'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Vuosilomat', 'url'=>array('index')),
	array('label'=>'Manage Vuosilomat', 'url'=>array('admin')),
);
?>

<h1>Create Vuosilomat</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>