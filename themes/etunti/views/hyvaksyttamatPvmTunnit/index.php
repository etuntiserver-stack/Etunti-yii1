<?php
/* @var $this HyvaksyttamatPvmTunnitController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Hyvaksyttamat Pvm Tunnits',
);

$this->menu=array(
	array('label'=>'Create HyvaksyttamatPvmTunnit', 'url'=>array('create')),
	array('label'=>'Manage HyvaksyttamatPvmTunnit', 'url'=>array('admin')),
);
?>

<h1>Hyvaksyttamat Pvm Tunnits</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
