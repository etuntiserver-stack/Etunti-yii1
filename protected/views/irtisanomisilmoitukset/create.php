<?php
/* @var $this IrtisanomisilmoituksetController */
/* @var $model Irtisanomisilmoitukset */

$this->breadcrumbs=array(
	'Irtisanomisilmoituksets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Irtisanomisilmoitukset', 'url'=>array('index')),
	array('label'=>'Manage Irtisanomisilmoitukset', 'url'=>array('admin')),
);
?>

<h1>Create Irtisanomisilmoitukset</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>