<?php
/* @var $this TyonkuvausController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tyonkuvauses',
);

$this->menu=array(
	array('label'=>'Create Tyonkuvaus', 'url'=>array('create')),
	array('label'=>'Manage Tyonkuvaus', 'url'=>array('admin')),
);
?>

<h1>Tyonkuvauses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
