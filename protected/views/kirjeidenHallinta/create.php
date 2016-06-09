<?php
/* @var $this KirjeidenHallintaController */
/* @var $model KirjeidenHallinta */

$this->breadcrumbs=array(
	'Kirjeiden Hallintas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List KirjeidenHallinta', 'url'=>array('index')),
	array('label'=>'Manage KirjeidenHallinta', 'url'=>array('admin')),
);
?>

<h1>Create KirjeidenHallinta</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>