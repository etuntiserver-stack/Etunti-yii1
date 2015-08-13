<?php
/* @var $this DomainitController */
/* @var $model Domainit */

$this->breadcrumbs=array(
	'Domainits'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Domainit', 'url'=>array('index')),
	array('label'=>'Create Domainit', 'url'=>array('create')),
	array('label'=>'View Domainit', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Domainit', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Päivitä'); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
