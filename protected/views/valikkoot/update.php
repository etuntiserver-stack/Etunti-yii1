<?php
/* @var $this ValikkootController */
/* @var $model Valikkoot */

$this->breadcrumbs=array(
	'Valikkoots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Valikkoot', 'url'=>array('index')),
	array('label'=>'Create Valikkoot', 'url'=>array('create')),
	array('label'=>'View Valikkoot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Valikkoot', 'url'=>array('admin')),
);
?>

<h1>Update Valikkoot <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>