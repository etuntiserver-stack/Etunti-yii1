<?php
/* @var $this KorvauksetController */
/* @var $model Korvaukset */

$this->breadcrumbs=array(
	'Korvauksets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Korvaukset', 'url'=>array('index')),
	array('label'=>'Manage Korvaukset', 'url'=>array('admin')),
);
?>

<h1>Create Korvaukset</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>