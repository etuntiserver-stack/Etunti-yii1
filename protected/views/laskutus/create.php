<?php
/* @var $this LaskutusController */
/* @var $model Laskutus */

$this->breadcrumbs=array(
	'Laskutuses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Laskutus', 'url'=>array('index')),
	array('label'=>'Manage Laskutus', 'url'=>array('admin')),
);
?>

<h1>Create Laskutus</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>