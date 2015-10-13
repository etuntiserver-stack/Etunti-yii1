<?php
/* @var $this AsetuksetForAllController */
/* @var $model AsetuksetForAll */

$this->breadcrumbs=array(
	'Asetukset For Alls'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AsetuksetForAll', 'url'=>array('index')),
	array('label'=>'Create AsetuksetForAll', 'url'=>array('create')),
	array('label'=>'View AsetuksetForAll', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AsetuksetForAll', 'url'=>array('admin')),
);
?>

<h1>Update AsetuksetForAll <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>