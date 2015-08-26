<?php
/* @var $this ValikkootController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Valikkoots',
);

$this->menu=array(
	array('label'=>'Create Valikkoot', 'url'=>array('create')),
	array('label'=>'Manage Valikkoot', 'url'=>array('admin')),
);
?>

<h1>Valikkoots</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
