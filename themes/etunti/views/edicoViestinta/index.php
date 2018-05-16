<?php
/* @var $this EdicoViestintaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Edico Viestintas',
);

$this->menu=array(
	array('label'=>'Create EdicoViestinta', 'url'=>array('create')),
	array('label'=>'Manage EdicoViestinta', 'url'=>array('admin')),
);
?>

<h1>Edico Viestintas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
