<?php
/* @var $this KirjeidenHallintaController */
/* @var $model KirjeidenHallinta */

$this->breadcrumbs=array(
	'Kirjeiden Hallintas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List KirjeidenHallinta', 'url'=>array('index')),
	array('label'=>'Create KirjeidenHallinta', 'url'=>array('create')),
	array('label'=>'View KirjeidenHallinta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage KirjeidenHallinta', 'url'=>array('admin')),
);
?>

<h1>Update KirjeidenHallinta <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>