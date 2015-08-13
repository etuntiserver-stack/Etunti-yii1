<?php
/* @var $this TyontekijatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Työntekijät'),
);

$this->menu=array(
	array('label'=>'Create Tyontekijat', 'url'=>array('create')),
	array('label'=>'Manage Tyontekijat', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main', 'Työntekijät'); ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
