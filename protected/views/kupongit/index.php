<?php
/* @var $this KupongitController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Kupongits',
);

$this->menu=array(
	array('label'=>'Create Kupongit', 'url'=>array('create')),
	array('label'=>'Manage Kupongit', 'url'=>array('admin')),
);
?>

<h1>Kupongits</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
