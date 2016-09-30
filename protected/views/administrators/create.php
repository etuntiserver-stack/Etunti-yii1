<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */

$this->breadcrumbs=array(
	'Administrators'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Administrators', 'url'=>array('index')),
	array('label'=>'Manage Administrators', 'url'=>array('admin')),
);
?>

<h1>Create Administrators</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>