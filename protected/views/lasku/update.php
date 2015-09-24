<?php
/* @var $this LaskuController */
/* @var $model Lasku */

$this->breadcrumbs=array(
	'Laskus'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Lasku', 'url'=>array('index')),
	array('label'=>'Create Lasku', 'url'=>array('create')),
	array('label'=>'View Lasku', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Lasku', 'url'=>array('admin')),
);
?>

<h1>Update Lasku <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>