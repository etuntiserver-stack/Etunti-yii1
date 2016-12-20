<?php
/* @var $this IrtisanomisilmoituksetController */
/* @var $model Irtisanomisilmoitukset */

$this->breadcrumbs=array(
	'Irtisanomisilmoituksets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Irtisanomisilmoitukset', 'url'=>array('index')),
	array('label'=>'Create Irtisanomisilmoitukset', 'url'=>array('create')),
	array('label'=>'View Irtisanomisilmoitukset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Irtisanomisilmoitukset', 'url'=>array('admin')),
);
?>

<h1>Update Irtisanomisilmoitukset <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>