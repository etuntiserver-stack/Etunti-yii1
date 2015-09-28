<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */

$this->breadcrumbs=array(
	'Lisatyotunnits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Lisatyotunnit', 'url'=>array('index')),
	array('label'=>'Manage Lisatyotunnit', 'url'=>array('admin')),
);
?>

<h1>Create Lisatyotunnit</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>