<?php
/* @var $this PalautteetController */
/* @var $model Palautteet */

$this->breadcrumbs=array(
	'Palautteets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Palautteet', 'url'=>array('index')),
	array('label'=>'Create Palautteet', 'url'=>array('create')),
	array('label'=>'View Palautteet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Palautteet', 'url'=>array('admin')),
);
?>

<h1>Update Palautteet <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>