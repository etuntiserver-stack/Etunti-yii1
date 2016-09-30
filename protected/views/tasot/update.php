<?php
/* @var $this TasotController */
/* @var $model Tasot */

$this->breadcrumbs=array(
	'Tasots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tasot', 'url'=>array('index')),
	array('label'=>'Create Tasot', 'url'=>array('create')),
	array('label'=>'View Tasot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tasot', 'url'=>array('admin')),
);
?>

<h1>Update Tasot <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>