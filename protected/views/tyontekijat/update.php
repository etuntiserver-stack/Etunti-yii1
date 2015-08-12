<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */

$this->breadcrumbs=array(
	'Tyontekijats'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tyontekijat', 'url'=>array('index')),
	array('label'=>'Create Tyontekijat', 'url'=>array('create')),
	array('label'=>'View Tyontekijat', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tyontekijat', 'url'=>array('admin')),
);
?>

<h1>Update Tyontekijat <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>