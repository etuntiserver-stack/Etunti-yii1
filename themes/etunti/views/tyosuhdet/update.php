<?php
/* @var $this TyosuhdetController */
/* @var $model Tyosuhdet */

$this->breadcrumbs=array(
	'Tyosuhdets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tyosuhdet', 'url'=>array('index')),
	array('label'=>'Create Tyosuhdet', 'url'=>array('create')),
	array('label'=>'View Tyosuhdet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tyosuhdet', 'url'=>array('admin')),
);
?>

<h1>Update Tyosuhdet <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>