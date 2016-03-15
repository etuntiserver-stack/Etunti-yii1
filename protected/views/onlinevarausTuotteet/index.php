<?php
/* @var $this OnlinevarausTuotteetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Onlinevaraus Tuotteets',
);

$this->menu=array(
	array('label'=>'Create OnlinevarausTuotteet', 'url'=>array('create')),
	array('label'=>'Manage OnlinevarausTuotteet', 'url'=>array('admin')),
);
?>

<h1>Onlinevaraus Tuotteets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
