<?php
/* @var $this LaskuController */
/* @var $model Lasku */

$this->breadcrumbs=array(
	Yii::t('main', 'Laskut')=>array('index'),
	Yii::t('main', 'LUO'),
);
/*
$this->menu=array(
	array('label'=>'List Lasku', 'url'=>array('index')),
	array('label'=>'Manage Lasku', 'url'=>array('admin')),
);
*/
?>

<legend>
<h1> <?php echo Yii::t('main', 'LUO LASKU'); ?> <i class="glyphicon glyphicon-time"></i></h1>
</legend>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
