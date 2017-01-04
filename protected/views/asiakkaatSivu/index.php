<?php
/* @var $this AsiakkaatSivuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Asiakkaat Sivus',
);

$this->menu=array(
	array('label'=>'Create AsiakkaatSivu', 'url'=>array('create')),
	array('label'=>'Manage AsiakkaatSivu', 'url'=>array('admin')),
);
?>

<h1>Asiakkaat Sivus</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
