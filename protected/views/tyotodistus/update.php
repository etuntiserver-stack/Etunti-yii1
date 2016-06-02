<?php
/* @var $this TyotodistusController */
/* @var $model Tyotodistus */

$this->breadcrumbs=array(
	'Tyotodistuses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tyotodistus', 'url'=>array('index')),
	array('label'=>'Create Tyotodistus', 'url'=>array('create')),
	array('label'=>'View Tyotodistus', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tyotodistus', 'url'=>array('admin')),
);
?>

<h1>Update Tyotodistus <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>