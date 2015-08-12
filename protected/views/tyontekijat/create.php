<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */

$this->breadcrumbs=array(
	'Tyontekijats'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tyontekijat', 'url'=>array('index')),
	array('label'=>'Manage Tyontekijat', 'url'=>array('admin')),
);
?>

<h1>Create Tyontekijat</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>