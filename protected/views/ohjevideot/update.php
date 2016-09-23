<?php
/* @var $this OhjevideotController */
/* @var $model Ohjevideot */

$this->breadcrumbs=array(
	'Ohjevideots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ohjevideot', 'url'=>array('index')),
	array('label'=>'Create Ohjevideot', 'url'=>array('create')),
	array('label'=>'View Ohjevideot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Ohjevideot', 'url'=>array('admin')),
);
?>

<h1>Update Ohjevideot <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>