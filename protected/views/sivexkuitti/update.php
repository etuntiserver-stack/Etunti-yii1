<?php
/* @var $this SivexkuittiController */
/* @var $model Sivexkuitti */

$this->breadcrumbs=array(
	'Sivexkuittis'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Sivexkuitti', 'url'=>array('index')),
	array('label'=>'Create Sivexkuitti', 'url'=>array('create')),
	array('label'=>'View Sivexkuitti', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Sivexkuitti', 'url'=>array('admin')),
);
?>

<h1>Update Sivexkuitti <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>