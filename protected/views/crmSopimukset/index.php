<?php
/* @var $this CrmTarjouksetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Crm Tarjouksets',
);

$this->menu=array(
	array('label'=>'Create CrmTarjoukset', 'url'=>array('create')),
	array('label'=>'Manage CrmTarjoukset', 'url'=>array('admin')),
);
?>

<h1>Crm Tarjouksets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
