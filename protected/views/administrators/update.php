<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */

$this->breadcrumbs=array(
	Yii::t('main', 'Administrators')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'Päivitä'),
);

$this->menu=array(
	array('label'=>'List Administrators', 'url'=>array('index')),
	array('label'=>'Create Administrators', 'url'=>array('create')),
	array('label'=>'View Administrators', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Administrators', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Päivitä administrators').' '.$model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
