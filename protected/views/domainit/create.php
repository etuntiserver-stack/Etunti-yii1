<?php
/* @var $this DomainitController */
/* @var $model Domainit */

$this->breadcrumbs=array(
	'Domainits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Domainit', 'url'=>array('index')),
	array('label'=>'Manage Domainit', 'url'=>array('admin')),
);
?>

<h1>Create Domainit</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>