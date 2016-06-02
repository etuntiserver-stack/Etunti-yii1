<?php
/* @var $this TyotodistusController */
/* @var $model Tyotodistus */

$this->breadcrumbs=array(
	'Tyotodistuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tyotodistus', 'url'=>array('index')),
	array('label'=>'Manage Tyotodistus', 'url'=>array('admin')),
);
?>

<h1>Create Tyotodistus</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>