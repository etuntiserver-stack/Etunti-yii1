<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Kohteets',
);

$this->menu=array(
	array('label'=>'Create Kohteet', 'url'=>array('create')),
	array('label'=>'Manage Kohteet', 'url'=>array('admin')),
);
?>

<h1>Kohteets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
