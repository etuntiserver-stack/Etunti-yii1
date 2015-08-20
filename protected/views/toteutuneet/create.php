<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */

$this->breadcrumbs=array(
	'Toteutuneets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Toteutuneet', 'url'=>array('index')),
	array('label'=>'Manage Toteutuneet', 'url'=>array('admin')),
);
?>

<h1>Create Toteutuneet</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>