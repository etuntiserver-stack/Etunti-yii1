<?php
/* @var $this TyosopimuksetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tyosopimuksets',
);

$this->menu=array(
	array('label'=>'Create Tyosopimukset', 'url'=>array('create')),
	array('label'=>'Manage Tyosopimukset', 'url'=>array('admin')),
);
?>

<h1>Tyosopimuksets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
