<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$this->breadcrumbs=array(
	Yii::t('main', 'Viestintä')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'Päivitä'),
);

$this->menu=array(
	array('label'=>'List Viestinta', 'url'=>array('index')),
	array('label'=>'Create Viestinta', 'url'=>array('create')),
	array('label'=>'View Viestinta', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Viestinta', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Päivitä viesti'); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
