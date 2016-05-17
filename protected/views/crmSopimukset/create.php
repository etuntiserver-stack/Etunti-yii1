<?php
/* @var $this CrmSopimuksetController */
/* @var $model CrmSopimukset */

$this->breadcrumbs=array(
	'Crm Sopimuksets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CrmSopimukset', 'url'=>array('index')),
	array('label'=>'Manage CrmSopimukset', 'url'=>array('admin')),
);
?>

<h1>Create CrmSopimukset</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>