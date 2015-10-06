<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $model AsiakasHyvaksynta */

$this->breadcrumbs=array(
	'Asiakas Hyvaksyntas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AsiakasHyvaksynta', 'url'=>array('index')),
	array('label'=>'Manage AsiakasHyvaksynta', 'url'=>array('admin')),
);
?>

<h1>Create AsiakasHyvaksynta</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>