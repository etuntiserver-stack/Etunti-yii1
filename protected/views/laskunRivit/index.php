<?php
/* @var $this LaskunRivitController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Laskun Rivits',
);

$this->menu=array(
	array('label'=>'Create LaskunRivit', 'url'=>array('create')),
	array('label'=>'Manage LaskunRivit', 'url'=>array('admin')),
);
?>

<h1>Laskun Rivits</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
