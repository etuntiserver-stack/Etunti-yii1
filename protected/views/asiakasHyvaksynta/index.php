<?php
/* @var $this AsiakasHyvaksyntaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Asiakas Hyvaksyntas',
);

$this->menu=array(
	array('label'=>'Create AsiakasHyvaksynta', 'url'=>array('create')),
	array('label'=>'Manage AsiakasHyvaksynta', 'url'=>array('admin')),
);
?>

<legend>
<h1><?php echo Yii::t('main','HYVÄKSYTTY TUNNIT'); ?></h1>
</legend>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
