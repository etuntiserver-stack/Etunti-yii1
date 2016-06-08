<?php
/* @var $this PalautteetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Palautteets',
);

$this->menu=array(
	array('label'=>'Create Palautteet', 'url'=>array('create')),
	array('label'=>'Manage Palautteet', 'url'=>array('admin')),
);
?>

<h1>Palautteets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
