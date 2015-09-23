<?php
/* @var $this LaskunRivitController */
/* @var $model LaskunRivit */

$this->breadcrumbs=array(
	'Laskun Rivits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LaskunRivit', 'url'=>array('index')),
	array('label'=>'Manage LaskunRivit', 'url'=>array('admin')),
);
?>

<h1>Create LaskunRivit</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>