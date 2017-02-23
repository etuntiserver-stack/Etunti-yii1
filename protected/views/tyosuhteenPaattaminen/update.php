<?php
/* @var $this TyosuhteenPaattaminenController */
/* @var $model TyosuhteenPaattaminen */

$this->breadcrumbs=array(
	'Tyosuhteen Paattaminens'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TyosuhteenPaattaminen', 'url'=>array('index')),
	array('label'=>'Create TyosuhteenPaattaminen', 'url'=>array('create')),
	array('label'=>'View TyosuhteenPaattaminen', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TyosuhteenPaattaminen', 'url'=>array('admin')),
);
?>

<h1>Update TyosuhteenPaattaminen <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>