<?php
/* @var $this KohteetController */
/* @var $model Kohteet */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet')=>array('index'),
	Yii::t('main', 'Luo'),
);

$this->menu=array(
	array('label'=>Yii::t('main', 'Kohde lista'), 'url'=>array('index')),
	array('label'=>Yii::t('main', 'Kohde hallinta'), 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Luo kohde'); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
