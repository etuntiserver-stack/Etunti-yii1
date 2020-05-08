<?php
/* @var $this OhjevideotController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ohjevideots',
);

$this->menu=array(
	array('label'=>'Luo uusi video', 'url'=>array('create')),
);
?>

<h1>Ohjevideots</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
