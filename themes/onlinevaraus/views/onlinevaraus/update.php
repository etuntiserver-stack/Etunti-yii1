<?php
/* @var $this OnlinevarausController */
/* @var $model Onlinevaraus */

$this->breadcrumbs=array(
	'Onlinevarauses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Onlinevaraus', 'url'=>array('index')),
	array('label'=>'Create Onlinevaraus', 'url'=>array('create')),
	array('label'=>'View Onlinevaraus', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Onlinevaraus', 'url'=>array('admin')),
);
?>

<h1>Update Onlinevaraus <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>