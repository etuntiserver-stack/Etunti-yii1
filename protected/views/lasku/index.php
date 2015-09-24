<?php
/* @var $this LaskuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Laskus',
);

$this->menu=array(
	array('label'=>'Create Lasku', 'url'=>array('create')),
	array('label'=>'Manage Lasku', 'url'=>array('admin')),
);
?>

<h1>Laskus</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
