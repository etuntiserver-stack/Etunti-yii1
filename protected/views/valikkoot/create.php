<?php
/* @var $this ValikkootController */
/* @var $model Valikkoot */

$this->breadcrumbs=array(
	'Valikkoots'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Valikkoot', 'url'=>array('index')),
	array('label'=>'Manage Valikkoot', 'url'=>array('admin')),
);
?>

<h1>Create Valikkoot</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>