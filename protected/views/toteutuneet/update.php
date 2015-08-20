<?php
/* @var $this ToteutuneetController */
/* @var $model Toteutuneet */

$this->breadcrumbs=array(
	'Toteutuneets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Toteutuneet', 'url'=>array('index')),
	array('label'=>'Create Toteutuneet', 'url'=>array('create')),
	array('label'=>'View Toteutuneet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Toteutuneet', 'url'=>array('admin')),
);
?>

<h1>Update Toteutuneet <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>