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

<legend>
<h1> <?php echo Yii::t('main', 'LUO TYÖNTEKIJÄ'); ?> <i class="glyphicon glyphicon-user"></i></h1>
</legend>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
