<?php
/* @var $this KuviaKohteestaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Kuvia Kohteestas',
);

$this->menu=array(
	array('label'=>'Create KuviaKohteesta', 'url'=>array('create')),
	array('label'=>'Manage KuviaKohteesta', 'url'=>array('admin')),
);
?>

<h1>Kuvia Kohteestas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
