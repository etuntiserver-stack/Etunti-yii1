<?php
/* @var $this TyosuhteenPaattaminenController */
/* @var $model TyosuhteenPaattaminen */

$this->breadcrumbs=array(
	'Tyosuhteen Paattaminens'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TyosuhteenPaattaminen', 'url'=>array('index')),
	array('label'=>'Manage TyosuhteenPaattaminen', 'url'=>array('admin')),
);
?>

<h1>Create TyosuhteenPaattaminen</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>