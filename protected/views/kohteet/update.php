<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohde')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'päiväys'),
);

$this->menu=array(
	array('label'=>'List Kohteet', 'url'=>array('index')),
	array('label'=>'Create Kohteet', 'url'=>array('create')),
	array('label'=>'View Kohteet', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Kohteet', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Kohde ID:'); ?> <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
