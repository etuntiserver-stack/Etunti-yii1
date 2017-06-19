<?php
/* @var $this KupongitController */
/* @var $model Kupongit */

$this->breadcrumbs=array(
	'Kupongits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Kupongit', 'url'=>array('index')),
	array('label'=>'Manage Kupongit', 'url'=>array('admin')),
);
?>

<h1>Create Kupongit</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>