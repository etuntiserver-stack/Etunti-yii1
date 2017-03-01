<?php
/* @var $this TarjouslaskentaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tarjouslaskentas',
);

$this->menu=array(
	array('label'=>'Create Tarjouslaskenta', 'url'=>array('create')),
	array('label'=>'Manage Tarjouslaskenta', 'url'=>array('admin')),
);
?>

<h1>Tarjouslaskentas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
