<?php
/* @var $this AsiakkaatSivuController */
/* @var $model AsiakkaatSivu */

$this->breadcrumbs=array(
	'Asiakkaat Sivus'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AsiakkaatSivu', 'url'=>array('index')),
	array('label'=>'Manage AsiakkaatSivu', 'url'=>array('admin')),
);
?>

<h1>Create AsiakkaatSivu</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>