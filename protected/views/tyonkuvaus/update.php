<?php
/* @var $this TyonkuvausController */
/* @var $model Tyonkuvaus */

$this->breadcrumbs=array(
	'Tyonkuvauses'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Tyonkuvaus', 'url'=>array('index')),
	array('label'=>'Create Tyonkuvaus', 'url'=>array('create')),
	array('label'=>'View Tyonkuvaus', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Tyonkuvaus', 'url'=>array('admin')),
);
?>

<h1>Update Tyonkuvaus <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>