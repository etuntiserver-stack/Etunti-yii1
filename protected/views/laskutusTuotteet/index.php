<?php
/* @var $this LaskutusTuotteetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Laskutus Tuotteets',
);

$this->menu=array(
	array('label'=>'Create LaskutusTuotteet', 'url'=>array('create')),
	array('label'=>'Manage LaskutusTuotteet', 'url'=>array('admin')),
);
?>

<h1>Laskutus Tuotteets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
