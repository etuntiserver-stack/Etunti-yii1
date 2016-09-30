<?php
/* @var $this FirmanTiedotController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Firman Tiedots',
);

$this->menu=array(
	array('label'=>'Create FirmanTiedot', 'url'=>array('create')),
	array('label'=>'Manage FirmanTiedot', 'url'=>array('admin')),
);
?>

<h1>Firman Tiedots</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
