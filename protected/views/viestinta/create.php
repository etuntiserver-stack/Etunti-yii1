<?php
/* @var $this ViestintaController */
/* @var $model Viestinta */

$this->breadcrumbs=array(
	Yii::t('main', 'Viestintä')=>array('index'),
	Yii::t('main', 'Luominen'),
);
/*
$this->menu=array(
	array('label'=>'List Viestinta', 'url'=>array('index')),
	array('label'=>'Manage Viestinta', 'url'=>array('admin')),
);
*/
?>

<legend>
<h1> <?php echo Yii::t('main', 'LUO VIESTI'); ?> <i class="glyphicon glyphicon-envelope"></i></h1>
</legend>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
