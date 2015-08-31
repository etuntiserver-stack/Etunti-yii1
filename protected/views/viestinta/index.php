<?php
/* @var $this ViestintaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Viestintas',
);

$this->menu=array(
	array('label'=>'Create Viestinta', 'url'=>array('create')),
	array('label'=>'Manage Viestinta', 'url'=>array('admin')),
);
?>

<h1>Viestintas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
