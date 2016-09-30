<?php
/* @var $this LaskuHistoriaController */
/* @var $model LaskuHistoria */

$this->breadcrumbs=array(
	'Lasku Historias'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LaskuHistoria', 'url'=>array('index')),
	array('label'=>'Manage LaskuHistoria', 'url'=>array('admin')),
);
?>

<h1>Create LaskuHistoria</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>