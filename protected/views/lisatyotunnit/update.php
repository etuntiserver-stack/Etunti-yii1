<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */

$this->breadcrumbs=array(
	'Lisatyotunnits'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Lisatyotunnit', 'url'=>array('index')),
	array('label'=>'Create Lisatyotunnit', 'url'=>array('create')),
	array('label'=>'View Lisatyotunnit', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Lisatyotunnit', 'url'=>array('admin')),
);
?>

<h1>Update Lisatyotunnit <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>