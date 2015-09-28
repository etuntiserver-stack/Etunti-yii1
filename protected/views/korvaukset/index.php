<?php
/* @var $this KorvauksetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Korvauksets',
);

$this->menu=array(
	array('label'=>'Create Korvaukset', 'url'=>array('create')),
	array('label'=>'Manage Korvaukset', 'url'=>array('admin')),
);
?>

<h1>Korvauksets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
