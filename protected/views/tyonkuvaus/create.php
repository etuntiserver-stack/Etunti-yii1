<?php
/* @var $this TyonkuvausController */
/* @var $model Tyonkuvaus */

$this->breadcrumbs=array(
	'Tyonkuvauses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tyonkuvaus', 'url'=>array('index')),
	array('label'=>'Manage Tyonkuvaus', 'url'=>array('admin')),
);
?>

<h1>Create Tyonkuvaus</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>