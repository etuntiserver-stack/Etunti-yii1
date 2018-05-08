<?php
/* @var $this TietosuojaController */
/* @var $model Tietosuoja */

$this->breadcrumbs=array(
	'Tietosuojas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tietosuoja', 'url'=>array('index')),
	array('label'=>'Manage Tietosuoja', 'url'=>array('admin')),
);
?>

<h1>Create Tietosuoja</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>