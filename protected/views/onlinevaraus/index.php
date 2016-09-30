<?php
/* @var $this OnlinevarausController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Onlinevarauses',
);

$this->menu=array(
	array('label'=>'Create Onlinevaraus', 'url'=>array('create')),
	array('label'=>'Manage Onlinevaraus', 'url'=>array('admin')),
);
?>

<h1>Onlinevarauses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
