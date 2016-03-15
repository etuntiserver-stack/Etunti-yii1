<?php
/* @var $this OnlinevarausController */
/* @var $model Onlinevaraus */

$this->breadcrumbs=array(
	'Onlinevarauses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Onlinevaraus', 'url'=>array('index')),
	array('label'=>'Manage Onlinevaraus', 'url'=>array('admin')),
);
?>

<h1>Create Onlinevaraus</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>