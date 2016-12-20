<?php
/* @var $this TyosopimuksetController */
/* @var $model Tyosopimukset */

$this->breadcrumbs=array(
	'Tyosopimuksets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tyosopimukset', 'url'=>array('index')),
	array('label'=>'Create Tyosopimukset', 'url'=>array('create')),
	array('label'=>'View Tyosopimukset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tyosopimukset', 'url'=>array('admin')),
);
?>

<h1>Update Tyosopimukset <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>