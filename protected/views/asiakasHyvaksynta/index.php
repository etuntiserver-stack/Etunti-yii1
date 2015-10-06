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

<h1>Asiakas Hyvaksyntas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
