<?php
/* @var $this KupongitController */
/* @var $model Kupongit */

$this->breadcrumbs=array(
	'Kupongits'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Kupongit', 'url'=>array('index')),
	array('label'=>'Create Kupongit', 'url'=>array('create')),
	array('label'=>'View Kupongit', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Kupongit', 'url'=>array('admin')),
);
?>

<h1>Update Kupongit <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>