<?php
/* @var $this LaskutusController */
/* @var $model Laskutus */

$this->breadcrumbs=array(
	'Laskutuses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Laskutus', 'url'=>array('index')),
	array('label'=>'Create Laskutus', 'url'=>array('create')),
	array('label'=>'View Laskutus', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Laskutus', 'url'=>array('admin')),
);
?>

<h1>Update Laskutus <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>