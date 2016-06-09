<?php
/* @var $this KirjeidenHallintaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Kirjeiden Hallintas',
);

$this->menu=array(
	array('label'=>'Create KirjeidenHallinta', 'url'=>array('create')),
	array('label'=>'Manage KirjeidenHallinta', 'url'=>array('admin')),
);
?>

<h1>Kirjeiden Hallintas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
