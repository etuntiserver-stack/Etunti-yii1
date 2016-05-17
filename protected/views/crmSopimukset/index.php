<?php
/* @var $this CrmSopimuksetController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Crm Sopimuksets',
);

$this->menu=array(
	array('label'=>'Create CrmSopimukset', 'url'=>array('create')),
	array('label'=>'Manage CrmSopimukset', 'url'=>array('admin')),
);
?>

<h1>Crm Sopimuksets</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
