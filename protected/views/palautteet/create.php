<?php
/* @var $this PalautteetController */
/* @var $model Palautteet */

$this->breadcrumbs=array(
	'Palautteets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Palautteet', 'url'=>array('index')),
	array('label'=>'Manage Palautteet', 'url'=>array('admin')),
);
?>

<h1>Create Palautteet</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>