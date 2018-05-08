<?php
/* @var $this TietosuojaController */
/* @var $model Tietosuoja */

$this->breadcrumbs=array(
	'Tietosuojas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tietosuoja', 'url'=>array('index')),
	array('label'=>'Create Tietosuoja', 'url'=>array('create')),
	array('label'=>'View Tietosuoja', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tietosuoja', 'url'=>array('admin')),
);
?>

<h1>Update Tietosuoja <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>