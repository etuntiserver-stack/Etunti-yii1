<?php
/* @var $this TyosuhdetController */
/* @var $model Tyosuhdet */

$this->breadcrumbs=array(
	'Tyosuhdets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tyosuhdet', 'url'=>array('index')),
	array('label'=>'Manage Tyosuhdet', 'url'=>array('admin')),
);
?>

<h1>Create Tyosuhdet</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>