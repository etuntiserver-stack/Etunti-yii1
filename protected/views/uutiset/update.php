<?php
/* @var $this UutisetController */
/* @var $model Uutiset */

$this->breadcrumbs=array(
	'Uutisets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Uutiset', 'url'=>array('index')),
	array('label'=>'Create Uutiset', 'url'=>array('create')),
	array('label'=>'View Uutiset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Uutiset', 'url'=>array('admin')),
);
?>

<h1>Update Uutiset <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>