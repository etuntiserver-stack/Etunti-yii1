<?php
/* @var $this LaskunRivitController */
/* @var $model LaskunRivit */

$this->breadcrumbs=array(
	'Laskun Rivits'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LaskunRivit', 'url'=>array('index')),
	array('label'=>'Create LaskunRivit', 'url'=>array('create')),
	array('label'=>'View LaskunRivit', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage LaskunRivit', 'url'=>array('admin')),
);
?>

<h1>Update LaskunRivit <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>