<?php
/* @var $this UutisetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Uutisets',
);

$this->menu=array(
	array('label'=>'Create Uutiset', 'url'=>array('create')),
	array('label'=>'Manage Uutiset', 'url'=>array('admin')),
);
?>

<h1>Uutisets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
