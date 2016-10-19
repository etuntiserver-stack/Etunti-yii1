<?php
/* @var $this TyovuorootController */
/* @var $model Tyovuoroot */

$this->breadcrumbs=array(
	'Tyovuoroots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tyovuoroot', 'url'=>array('index')),
	array('label'=>'Create Tyovuoroot', 'url'=>array('create')),
	array('label'=>'View Tyovuoroot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tyovuoroot', 'url'=>array('admin')),
);
?>


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
