<?php
/* @var $this AvaimetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Avaimets',
);

$this->menu=array(
	array('label'=>'Create Avaimet', 'url'=>array('create')),
	array('label'=>'Manage Avaimet', 'url'=>array('admin')),
);
?>

<h1>Avaimets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
