<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */

$this->breadcrumbs=array(
	'Asetukset For Alls'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AsetuksetForAll', 'url'=>array('index')),
	array('label'=>'Manage AsetuksetForAll', 'url'=>array('admin')),
);
?>

<h1>Create AsetuksetForAll</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>