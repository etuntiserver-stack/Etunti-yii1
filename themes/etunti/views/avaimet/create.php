<?php
/* @var $this AvaimetController */
/* @var $model Avaimet */

$this->breadcrumbs=array(
	'Avaimets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Avaimet', 'url'=>array('index')),
	array('label'=>'Manage Avaimet', 'url'=>array('admin')),
);
?>

<h1>Create Avaimet</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>