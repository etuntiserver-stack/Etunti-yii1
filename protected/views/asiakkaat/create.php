<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	'Asiakkaats'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Asiakkaat', 'url'=>array('index')),
	array('label'=>'Manage Asiakkaat', 'url'=>array('admin')),
);
?>

<h1>Create Asiakkaat</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>