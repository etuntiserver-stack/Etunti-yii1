<?php
/* @var $this EnnakkoController */
/* @var $model Ennakko */

$this->breadcrumbs=array(
	'Ennakkos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ennakko', 'url'=>array('index')),
	array('label'=>'Create Ennakko', 'url'=>array('create')),
	array('label'=>'View Ennakko', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Ennakko', 'url'=>array('admin')),
);
?>

<h1>Update Ennakko <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>