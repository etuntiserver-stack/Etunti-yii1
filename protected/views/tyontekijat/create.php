<?php
/* @var $this TyontekijatController */
/* @var $model Tyontekijat */

$this->breadcrumbs=array(
	Yii::t('main', 'Työntekijä')=>array('index'),
	Yii::t('main', 'Luo'),
);

$this->menu=array(
	array('label'=>'List Tyontekijat', 'url'=>array('index')),
	array('label'=>'Manage Tyontekijat', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Luo työntekijä'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
