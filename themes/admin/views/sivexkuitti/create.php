<?php
/* @var $this SivexkuittiController */
/* @var $model Sivexkuitti */

$this->breadcrumbs=array(
	'Sivexkuittis'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Sivexkuitti', 'url'=>array('index')),
	array('label'=>'Manage Sivexkuitti', 'url'=>array('admin')),
);
?>

<h1>Create Sivexkuitti</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>