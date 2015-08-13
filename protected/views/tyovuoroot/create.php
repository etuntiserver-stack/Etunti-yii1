<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */

$this->breadcrumbs=array(
	'Tyovuoroots'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tyovuoroot', 'url'=>array('index')),
	array('label'=>'Manage Tyovuoroot', 'url'=>array('admin')),
);
?>

<h1>Create Tyovuoroot</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>