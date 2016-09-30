<?php
/* @var $this UutisetController */
/* @var $model Uutiset */

$this->breadcrumbs=array(
	'Uutisets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Uutiset', 'url'=>array('index')),
	array('label'=>'Manage Uutiset', 'url'=>array('admin')),
);
?>

<h1>Create Uutiset</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>