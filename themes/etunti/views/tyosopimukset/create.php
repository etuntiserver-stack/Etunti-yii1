<?php
/* @var $this TyosopimuksetController */
/* @var $model Tyosopimukset */

$this->breadcrumbs=array(
	'Tyosopimuksets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tyosopimukset', 'url'=>array('index')),
	array('label'=>'Manage Tyosopimukset', 'url'=>array('admin')),
);
?>

<h1>Create Tyosopimukset</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>