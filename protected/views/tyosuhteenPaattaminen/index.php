<?php
/* @var $this TyosuhteenPaattaminenController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tyosuhteen Paattaminens',
);

$this->menu=array(
	array('label'=>'Create TyosuhteenPaattaminen', 'url'=>array('create')),
	array('label'=>'Manage TyosuhteenPaattaminen', 'url'=>array('admin')),
);
?>

<h1>Tyosuhteen Paattaminens</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
