<?php
/* @var $this FirmanTiedotController */
/* @var $model FirmanTiedot */

$this->breadcrumbs=array(
	'Firman Tiedots'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
/*
$this->menu=array(
	array('label'=>'List FirmanTiedot', 'url'=>array('index')),
	array('label'=>'Create FirmanTiedot', 'url'=>array('create')),
	array('label'=>'View FirmanTiedot', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FirmanTiedot', 'url'=>array('admin')),
);
*/
?>

<legend>
<h1><?php echo Yii::t('main','FIRMA'); ?></h1>
</legend>
<br>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
