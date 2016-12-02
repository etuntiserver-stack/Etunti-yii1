<?php
/* @var $this LisatyotunnitController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Lisatyotunnits',
);

$this->menu=array(
	array('label'=>'Create Lisatyotunnit', 'url'=>array('create')),
	array('label'=>'Manage Lisatyotunnit', 'url'=>array('admin')),
);
?>

<h1>Lisatyotunnits</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
