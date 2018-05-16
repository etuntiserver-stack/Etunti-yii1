<?php
/* @var $this EdicoViestintaController */
/* @var $model EdicoViestinta */

$this->breadcrumbs=array(
	'Edico Viestintas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List EdicoViestinta', 'url'=>array('index')),
	array('label'=>'Manage EdicoViestinta', 'url'=>array('admin')),
);
?>

<h1>Create EdicoViestinta</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>