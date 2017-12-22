<?php
/* @var $this CrmTarjouksetController */
/* @var $model CrmTarjoukset */

$this->breadcrumbs=array(
	'Crm Tarjouksets'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CrmTarjoukset', 'url'=>array('index')),
	array('label'=>'Manage CrmTarjoukset', 'url'=>array('admin')),
);
?>

<h1>Create CrmTarjoukset</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>