<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	'Asiakkaats'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Asiakkaat', 'url'=>array('index')),
	array('label'=>'Create Asiakkaat', 'url'=>array('create')),
	array('label'=>'View Asiakkaat', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Asiakkaat', 'url'=>array('admin')),
);
?>

<h1>Update Asiakkaat <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>