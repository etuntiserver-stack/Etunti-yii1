<?php
/* @var $this TasotController */
/* @var $model Tasot */

$this->breadcrumbs=array(
	'Tasots'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tasot', 'url'=>array('index')),
	array('label'=>'Manage Tasot', 'url'=>array('admin')),
);
?>

<h1>Create Tasot</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>