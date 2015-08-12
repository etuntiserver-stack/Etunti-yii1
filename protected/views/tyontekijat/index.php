<?php
/* @var $this TyontekijatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tyontekijats',
);

$this->menu=array(
	array('label'=>'Create Tyontekijat', 'url'=>array('create')),
	array('label'=>'Manage Tyontekijat', 'url'=>array('admin')),
);
?>

<h1>Tyontekijats</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
