<?php
/* @var $this BlogController */
/* @var $model Blog */

$this->breadcrumbs=array(
	'Blogs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Blog', 'url'=>array('index')),
	array('label'=>'Manage Blog', 'url'=>array('admin')),
);
?>

<h1>Create Blog</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>