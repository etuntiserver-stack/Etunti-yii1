<?php
/* @var $this ToistuvatTyovuorotController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Toistuvat Tyovuorots',
);

$this->menu=array(
	array('label'=>'Create ToistuvatTyovuorot', 'url'=>array('create')),
	array('label'=>'Manage ToistuvatTyovuorot', 'url'=>array('admin')),
);
?>

<h1>Toistuvat Tyovuorots</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
