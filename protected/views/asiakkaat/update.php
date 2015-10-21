<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	Yii::t('main', 'Asiakaat')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'Päivitä'),
);
/*
$this->menu=array(
	array('label'=>'List Asiakkaat', 'url'=>array('index')),
	array('label'=>'Create Asiakkaat', 'url'=>array('create')),
	array('label'=>'View Asiakkaat', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Asiakkaat', 'url'=>array('admin')),
);
*/
?>

<legend>
<h1> <?php echo Yii::t('main', 'ASIAKAS')." ID# ".$model->id; ?> <i class="glyphicon glyphicon-user"></i></h1>
</legend>



<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
