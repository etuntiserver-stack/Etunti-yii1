<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$this->breadcrumbs=array(
	'Viestintas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Viestinta', 'url'=>array('index')),
	array('label'=>'Manage Viestinta', 'url'=>array('admin')),
);
?>

<h1>Create Viestinta</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>