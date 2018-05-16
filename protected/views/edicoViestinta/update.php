<?php
/* @var $this EdicoViestintaController */
/* @var $model EdicoViestinta */

$this->breadcrumbs=array(
	'Edico Viestintas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List EdicoViestinta', 'url'=>array('index')),
	array('label'=>'Create EdicoViestinta', 'url'=>array('create')),
	array('label'=>'View EdicoViestinta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage EdicoViestinta', 'url'=>array('admin')),
);
?>

<h1>Update EdicoViestinta <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>