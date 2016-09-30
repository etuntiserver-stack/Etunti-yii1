<?php
/* @var $this TasotController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tasots',
);

$this->menu=array(
	array('label'=>'Create Tasot', 'url'=>array('create')),
	array('label'=>'Manage Tasot', 'url'=>array('admin')),
);
?>

<h1>Tasots</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
