<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

$this->breadcrumbs=array(
	'Kohteets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Kohteet', 'url'=>array('index')),
	array('label'=>'Create Kohteet', 'url'=>array('create')),
	array('label'=>'View Kohteet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Kohteet', 'url'=>array('admin')),
);
?>

<h1>Update Kohteet <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>