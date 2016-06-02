<?php
/* @var $this TyotodistusController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tyotodistuses',
);

$this->menu=array(
	array('label'=>'Create Tyotodistus', 'url'=>array('create')),
	array('label'=>'Manage Tyotodistus', 'url'=>array('admin')),
);
?>

<h1>Tyotodistuses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
