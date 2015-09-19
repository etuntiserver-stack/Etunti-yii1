<?php
/* @var $this AsiakkaatController */
/* @var $model Asiakkaat */

$this->breadcrumbs=array(
	Yii::t('main', 'Asiakaat')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'Päivitä'),
);

$this->menu=array(
	array('label'=>'List Asiakkaat', 'url'=>array('index')),
	array('label'=>'Create Asiakkaat', 'url'=>array('create')),
	array('label'=>'View Asiakkaat', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Asiakkaat', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Asiakas'); ?> <?php echo $model->etunimi." ".$model->sukunimi; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
