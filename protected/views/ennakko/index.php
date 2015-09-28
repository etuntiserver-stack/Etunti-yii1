<?php
/* @var $this EnnakkoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ennakkos',
);

$this->menu=array(
	array('label'=>'Create Ennakko', 'url'=>array('create')),
	array('label'=>'Manage Ennakko', 'url'=>array('admin')),
);
?>

<h1>Ennakkos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
