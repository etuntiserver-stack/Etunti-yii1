<?php
/* @var $this KorvauksetController */
/* @var $model Korvaukset */

$this->breadcrumbs=array(
	'Korvauksets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Korvaukset', 'url'=>array('index')),
	array('label'=>'Create Korvaukset', 'url'=>array('create')),
	array('label'=>'View Korvaukset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Korvaukset', 'url'=>array('admin')),
);
?>

<h1>Update Korvaukset <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>