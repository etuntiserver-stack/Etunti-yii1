<?php
/* @var $this DomainitController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Domainits',
);

$this->menu=array(
	array('label'=>'Create Domainit', 'url'=>array('create')),
	array('label'=>'Manage Domainit', 'url'=>array('admin')),
);
?>

<h1>Domainits</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
