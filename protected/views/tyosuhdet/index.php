<?php
/* @var $this TyosuhdetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tyosuhdets',
);

$this->menu=array(
	array('label'=>'Create Tyosuhdet', 'url'=>array('create')),
	array('label'=>'Manage Tyosuhdet', 'url'=>array('admin')),
);
?>

<h1>Tyosuhdets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
