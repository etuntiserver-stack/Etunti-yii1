<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $model AsiakasHyvaksynta */

$this->breadcrumbs=array(
	'Asiakas Hyvaksyntas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AsiakasHyvaksynta', 'url'=>array('index')),
	array('label'=>'Create AsiakasHyvaksynta', 'url'=>array('create')),
	array('label'=>'View AsiakasHyvaksynta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AsiakasHyvaksynta', 'url'=>array('admin')),
);
?>

<h1>Update AsiakasHyvaksynta <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>