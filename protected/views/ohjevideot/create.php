<?php
/* @var $this OhjevideotController */
/* @var $model Ohjevideot */

$this->breadcrumbs=array(
	'Ohjevideots'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ohjevideot', 'url'=>array('index')),
	array('label'=>'Manage Ohjevideot', 'url'=>array('admin')),
);
?>

<h1>Create Ohjevideot</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>