<?php
/* @var $this BlogCommentsController */
/* @var $model BlogComments */

$this->breadcrumbs=array(
	'Blog Comments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List BlogComments', 'url'=>array('index')),
	array('label'=>'Create BlogComments', 'url'=>array('create')),
	array('label'=>'View BlogComments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage BlogComments', 'url'=>array('admin')),
);
?>

<h1>Update BlogComments <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>