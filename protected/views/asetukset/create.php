<?php
/* @var $this AsetuksetController */
/* @var $model Asetukset */

$this->breadcrumbs=array(
	'Asetuksets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Asetukset', 'url'=>array('index')),
	array('label'=>'Manage Asetukset', 'url'=>array('admin')),
);
?>

<h1>Create Asetukset</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>