<?php
/* @var $this LisatyotunnitController */
/* @var $model Lisatyotunnit */

$this->breadcrumbs=array(
	'Lisatyotunnits'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Lisatyotunnit', 'url'=>array('index')),
	array('label'=>'Manage Lisatyotunnit', 'url'=>array('admin')),
);
?>

<legend>
<h1> <?php echo Yii::t('main', 'Lisätyötunnin luominen'); ?> <i class="glyphicon glyphicon-ok"></i></h1>
</legend>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
