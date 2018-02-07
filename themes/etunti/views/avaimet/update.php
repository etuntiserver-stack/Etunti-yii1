<?php
/* @var $this AvaimetController */
/* @var $model Avaimet */

$this->breadcrumbs=array(
	'Avaimets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Avaimet', 'url'=>array('index')),
	array('label'=>'Create Avaimet', 'url'=>array('create')),
	array('label'=>'View Avaimet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Avaimet', 'url'=>array('admin')),
);
?>

<h1>Update Avaimet <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>