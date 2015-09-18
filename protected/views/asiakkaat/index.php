<?php
/* @var $this AsiakkaatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Asiakkaats',
);

$this->menu=array(
	array('label'=>'Create Asiakkaat', 'url'=>array('create')),
	array('label'=>'Manage Asiakkaat', 'url'=>array('admin')),
);
?>

<h1>Asiakkaats</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
