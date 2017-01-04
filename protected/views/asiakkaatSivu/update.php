<?php
/* @var $this AsiakkaatSivuController */
/* @var $model AsiakkaatSivu */

$this->breadcrumbs=array(
	'Asiakkaat Sivus'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AsiakkaatSivu', 'url'=>array('index')),
	array('label'=>'Create AsiakkaatSivu', 'url'=>array('create')),
	array('label'=>'View AsiakkaatSivu', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AsiakkaatSivu', 'url'=>array('admin')),
);
?>

<h1>Update AsiakkaatSivu <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>