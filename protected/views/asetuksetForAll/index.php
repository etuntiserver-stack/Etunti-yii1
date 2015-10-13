<?php
/* @var $this AsetuksetForAllController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Asetukset For Alls',
);

$this->menu=array(
	array('label'=>'Create AsetuksetForAll', 'url'=>array('create')),
	array('label'=>'Manage AsetuksetForAll', 'url'=>array('admin')),
);
?>

<h1>Asetukset For Alls</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
