<?php
/* @var $this LaskuHistoriaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Lasku Historias',
);

$this->menu=array(
	array('label'=>'Create LaskuHistoria', 'url'=>array('create')),
	array('label'=>'Manage LaskuHistoria', 'url'=>array('admin')),
);
?>

<h1>Lasku Historias</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
