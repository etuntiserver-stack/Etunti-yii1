<?php
/* @var $this OhjevideotController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ohjevideots',
);

$this->menu=array(
	array('label'=>'Create Ohjevideot', 'url'=>array('create')),
	array('label'=>'Manage Ohjevideot', 'url'=>array('admin')),
);
?>

<h1>Ohjevideots</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
