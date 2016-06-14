<?php
/* @var $this BlogCommentsController */
/* @var $model BlogComments */

$this->breadcrumbs=array(
	'Blog Comments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List BlogComments', 'url'=>array('index')),
	array('label'=>'Manage BlogComments', 'url'=>array('admin')),
);
?>

<h1>Create BlogComments</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>